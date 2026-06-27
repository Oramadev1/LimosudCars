<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Location;
use App\Models\LocationType;
use App\Models\MaintenanceType;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\PaymentStatus;
use App\Models\PaymentType;
use App\Models\Reservation;
use App\Models\ReservationSource;
use App\Models\ReservationStatus;
use App\Models\Vehicle;
use App\Models\VehicleMaintenance;
use App\Models\VehicleStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardStatisticsModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_statistics_returns_global_kpis_and_monthly_profit(): void
    {
        $this->seed();
        $token = $this->adminToken();
        $month = now();

        $availableVehicle = $this->vehicle('available');
        $this->vehicle('reserved');
        $this->vehicle('rented');
        $this->vehicle('maintenance');
        $this->vehicle('repair');
        $this->vehicle('out_of_service');

        $this->reservation('pending', 'unpaid', $availableVehicle);
        $confirmedReservation = $this->reservation('confirmed', 'partial_paid', $availableVehicle);
        $this->reservation('in_progress', 'paid', $availableVehicle);
        $this->reservation('completed', 'paid', $availableVehicle);
        $this->reservation('cancelled', 'unpaid', $availableVehicle);

        $this->payment($confirmedReservation, 700, $month->copy()->setDay(8), 'paid');
        $this->payment($confirmedReservation, 500, $month->copy()->setDay(8), 'failed');
        Expense::create([
            'vehicle_id' => $availableVehicle->id,
            'expense_category_id' => ExpenseCategory::where('slug', 'maintenance')->value('id'),
            'amount' => 300,
            'expense_date' => $month->copy()->setDay(9)->toDateString(),
        ]);
        Expense::create([
            'expense_category_id' => ExpenseCategory::where('slug', 'taxes')->value('id'),
            'amount' => 200,
            'expense_date' => $month->copy()->setDay(9)->toDateString(),
        ]);

        $this->withToken($token)
            ->getJson('/api/admin/dashboard/statistics?year='.$month->year.'&month='.$month->month)
            ->assertOk()
            ->assertJsonPath('global_kpis.total_vehicles', 6)
            ->assertJsonPath('global_kpis.available_vehicles', 1)
            ->assertJsonPath('global_kpis.reserved_vehicles', 1)
            ->assertJsonPath('global_kpis.rented_vehicles', 1)
            ->assertJsonPath('global_kpis.vehicles_in_maintenance', 1)
            ->assertJsonPath('global_kpis.vehicles_in_repair', 1)
            ->assertJsonPath('global_kpis.out_of_service_vehicles', 1)
            ->assertJsonPath('global_kpis.total_customers', 5)
            ->assertJsonPath('global_kpis.total_reservations', 5)
            ->assertJsonPath('global_kpis.reservations_today', 5)
            ->assertJsonPath('global_kpis.reservations_this_month', 5)
            ->assertJsonPath('global_kpis.confirmed_reservations', 1)
            ->assertJsonPath('global_kpis.in_progress_reservations', 1)
            ->assertJsonPath('global_kpis.completed_reservations', 1)
            ->assertJsonPath('global_kpis.cancelled_reservations', 1)
            ->assertJsonPath('global_kpis.unpaid_reservations', 2)
            ->assertJsonPath('global_kpis.partial_paid_reservations', 1)
            ->assertJsonPath('global_kpis.paid_reservations', 2)
            ->assertJsonPath('global_kpis.monthly_revenue', 700)
            ->assertJsonPath('global_kpis.monthly_expenses', 500)
            ->assertJsonPath('global_kpis.monthly_net_profit', 200);
    }

    public function test_dashboard_monthly_expenses_includes_maintenance_costs_without_linked_expense(): void
    {
        $this->seed();
        $token = $this->adminToken();
        $month = now();
        $vehicle = $this->vehicle('available');

        VehicleMaintenance::create([
            'vehicle_id' => $vehicle->id,
            'maintenance_type_id' => MaintenanceType::where('slug', 'oil_change')->value('id'),
            'maintenance_date' => $month->copy()->setDay(12)->toDateString(),
            'cost' => 350,
        ]);

        $this->withToken($token)
            ->getJson('/api/admin/dashboard/statistics?year='.$month->year.'&month='.$month->month)
            ->assertOk()
            ->assertJsonPath('global_kpis.monthly_expenses', 350);
    }

    public function test_revenue_report_counts_only_paid_payments_and_groups_by_day(): void
    {
        $this->seed();
        $token = $this->adminToken();
        $reservation = $this->reservation('confirmed', 'partial_paid', $this->vehicle('available'));
        $firstDate = now()->copy()->startOfMonth()->addDays(1);
        $secondDate = now()->copy()->startOfMonth()->addDays(2);

        $this->payment($reservation, 100, $firstDate, 'paid');
        $this->payment($reservation, 200, $secondDate, 'paid');
        $this->payment($reservation, 900, $secondDate, 'failed');

        $this->withToken($token)
            ->getJson('/api/admin/dashboard/revenue?start_date='.$firstDate->toDateString().'&end_date='.$secondDate->toDateString().'&group_by=day')
            ->assertOk()
            ->assertJsonPath('date_range_revenue', 300)
            ->assertJsonPath('group_by', 'day')
            ->assertJsonPath('grouped_revenue.0.period', $firstDate->toDateString())
            ->assertJsonPath('grouped_revenue.0.total_amount', 100)
            ->assertJsonPath('grouped_revenue.1.period', $secondDate->toDateString())
            ->assertJsonPath('grouped_revenue.1.total_amount', 200);
    }

    public function test_expense_report_groups_by_month_category_and_vehicle(): void
    {
        $this->seed();
        $token = $this->adminToken();
        $vehicle = $this->vehicle('available');
        $date = now()->copy()->startOfMonth()->addDays(5);

        Expense::create([
            'vehicle_id' => $vehicle->id,
            'expense_category_id' => ExpenseCategory::where('slug', 'fuel')->value('id'),
            'amount' => 150,
            'expense_date' => $date->toDateString(),
            'description' => 'Fuel refill.',
        ]);
        Expense::create([
            'expense_category_id' => ExpenseCategory::where('slug', 'taxes')->value('id'),
            'amount' => 50,
            'expense_date' => $date->toDateString(),
            'description' => 'General tax.',
        ]);

        $this->withToken($token)
            ->getJson('/api/admin/dashboard/expenses?start_date='.$date->copy()->startOfMonth()->toDateString().'&end_date='.$date->copy()->endOfMonth()->toDateString().'&group_by=month')
            ->assertOk()
            ->assertJsonPath('date_range_expenses', 200)
            ->assertJsonPath('group_by', 'month')
            ->assertJsonPath('grouped_expenses.0.period', $date->format('Y-m'))
            ->assertJsonPath('grouped_expenses.0.total_amount', 200)
            ->assertJsonFragment([
                'slug' => 'fuel',
                'name' => 'Fuel',
                'total_amount' => 150,
                'expense_count' => 1,
            ])
            ->assertJsonFragment([
                'name' => 'General',
                'total_amount' => 50,
                'expense_count' => 1,
            ]);
    }

    private function reservation(string $statusSlug, string $paymentStatusSlug, Vehicle $vehicle): Reservation
    {
        [$pickupLocation, $dropoffLocation] = $this->locations();

        return Reservation::create([
            'reservation_number' => 'RSV-DASH-'.fake()->unique()->numerify('####'),
            'customer_id' => Customer::factory()->create()->id,
            'vehicle_id' => $vehicle->id,
            'source_id' => ReservationSource::where('slug', 'website')->value('id'),
            'status_id' => ReservationStatus::where('slug', $statusSlug)->value('id'),
            'payment_status_id' => PaymentStatus::where('slug', $paymentStatusSlug)->value('id'),
            'pickup_location_id' => $pickupLocation->id,
            'dropoff_location_id' => $dropoffLocation->id,
            'start_datetime' => now()->addDays(5)->setTime(10, 0),
            'end_datetime' => now()->addDays(8)->setTime(10, 0),
            'total_days' => 3,
            'price_per_day' => 300,
            'delivery_fee' => 100,
            'deposit_amount' => 0,
            'total_price' => 1000,
        ]);
    }

    private function payment(Reservation $reservation, float $amount, mixed $paymentDate, string $statusSlug): Payment
    {
        return Payment::create([
            'reservation_id' => $reservation->id,
            'payment_method_id' => PaymentMethod::where('slug', 'cash')->value('id'),
            'payment_type_id' => PaymentType::where('slug', 'rental_payment')->value('id'),
            'payment_status_id' => PaymentStatus::where('slug', $statusSlug)->value('id'),
            'amount' => $amount,
            'payment_date' => $paymentDate->toDateString(),
        ]);
    }

    private function vehicle(string $statusSlug): Vehicle
    {
        return Vehicle::factory()->create([
            'status_id' => VehicleStatus::where('slug', $statusSlug)->value('id'),
        ]);
    }

    /**
     * @return array{0: Location, 1: Location}
     */
    private function locations(): array
    {
        $agencyTypeId = LocationType::where('slug', 'agency')->value('id');
        $airportTypeId = LocationType::where('slug', 'airport')->value('id');

        return [
            Location::firstOrCreate(
                ['slug' => 'dashboard-agency'],
                [
                    'location_type_id' => $agencyTypeId,
                    'name' => 'Dashboard Agency',
                    'address' => 'Dakhla Center',
                    'delivery_fee' => 0,
                    'is_active' => true,
                ]
            ),
            Location::firstOrCreate(
                ['slug' => 'dashboard-airport'],
                [
                    'location_type_id' => $airportTypeId,
                    'name' => 'Dashboard Airport',
                    'address' => 'Dakhla Airport',
                    'delivery_fee' => 100,
                    'is_active' => true,
                ]
            ),
        ];
    }

}
