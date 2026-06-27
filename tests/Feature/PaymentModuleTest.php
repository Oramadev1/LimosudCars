<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\FuelType;
use App\Models\Location;
use App\Models\LocationType;
use App\Models\PaymentStatus;
use App\Models\Reservation;
use App\Models\ReservationSource;
use App\Models\ReservationStatus;
use App\Models\TransmissionType;
use App\Models\Vehicle;
use App\Models\VehicleBrand;
use App\Models\VehicleCategory;
use App\Models\VehicleStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_creating_paid_advance_payment_sets_partial_status(): void
    {
        $this->seed();
        $token = $this->adminToken();
        $reservation = $this->reservation(totalPrice: 1000);

        $response = $this->withToken($token)->postJson('/api/admin/payments', [
            'reservation_id' => $reservation->id,
            'payment_method_slug' => 'cash',
            'payment_type_slug' => 'reservation_deposit',
            'payment_status_slug' => 'paid',
            'amount' => 300,
            'payment_date' => now()->toDateString(),
            'paid_by_customer_name' => 'Ahmed Dakhla',
            'reference' => 'ADV-001',
            'notes' => 'Advance payment.',
        ]);

        $response
            ->assertCreated()
            ->assertJsonPath('data.payment_status.slug', 'paid')
            ->assertJsonPath('data.payment_type.slug', 'reservation_deposit')
            ->assertJsonPath('data.paid_by_customer_name', 'Ahmed Dakhla');

        $this->assertSame('partial_paid', $reservation->refresh()->paymentStatus->slug);
    }

    public function test_full_paid_amount_sets_reservation_paid_status(): void
    {
        $this->seed();
        $token = $this->adminToken();
        $reservation = $this->reservation(totalPrice: 1000);

        $this->createPayment($token, $reservation, 400);
        $this->createPayment($token, $reservation, 600);

        $this->assertSame('paid', $reservation->refresh()->paymentStatus->slug);
    }

    public function test_cancelled_payment_recalculates_reservation_status(): void
    {
        $this->seed();
        $token = $this->adminToken();
        $reservation = $this->reservation(totalPrice: 1000);

        $paymentId = $this->createPayment($token, $reservation, 500);

        $this->assertSame('partial_paid', $reservation->refresh()->paymentStatus->slug);

        $this->withToken($token)
            ->postJson("/api/admin/payments/{$paymentId}/cancel")
            ->assertOk()
            ->assertJsonPath('data.payment_status.slug', 'cancelled');

        $this->assertSame('unpaid', $reservation->refresh()->paymentStatus->slug);
        $this->assertDatabaseHas('payments', [
            'id' => $paymentId,
            'payment_status_id' => PaymentStatus::where('slug', 'cancelled')->value('id'),
        ]);
    }

    public function test_failed_and_refunded_payments_do_not_count_toward_paid_amount(): void
    {
        $this->seed();
        $token = $this->adminToken();
        $reservation = $this->reservation(totalPrice: 1000);

        $this->createPayment($token, $reservation, 600, 'failed');
        $this->createPayment($token, $reservation, 600, 'refunded');

        $this->assertSame('unpaid', $reservation->refresh()->paymentStatus->slug);

        $this->withToken($token)
            ->getJson("/api/admin/reservations/{$reservation->id}/payment-summary")
            ->assertOk()
            ->assertJsonPath('paid_amount', 0)
            ->assertJsonPath('remaining_amount', 1000)
            ->assertJsonPath('payment_status.slug', 'unpaid');
    }

    public function test_payment_update_and_summary_endpoint_recalculate_totals(): void
    {
        $this->seed();
        $token = $this->adminToken();
        $reservation = $this->reservation(totalPrice: 1000);
        $paymentId = $this->createPayment($token, $reservation, 250);

        $this->withToken($token)
            ->patchJson("/api/admin/payments/{$paymentId}", [
                'amount' => 1000,
                'reference' => 'FULL-001',
            ])
            ->assertOk()
            ->assertJsonPath('data.reference', 'FULL-001');

        $this->withToken($token)
            ->getJson("/api/admin/reservations/{$reservation->id}/payment-summary")
            ->assertOk()
            ->assertJsonPath('total_price', 1000)
            ->assertJsonPath('paid_amount', 1000)
            ->assertJsonPath('remaining_amount', 0)
            ->assertJsonPath('payment_status.slug', 'paid');
    }

    private function createPayment(string $token, Reservation $reservation, float $amount, string $statusSlug = 'paid'): int
    {
        $response = $this->withToken($token)->postJson('/api/admin/payments', [
            'reservation_id' => $reservation->id,
            'payment_method_slug' => 'cash',
            'payment_type_slug' => 'rental_payment',
            'payment_status_slug' => $statusSlug,
            'amount' => $amount,
            'payment_date' => now()->toDateString(),
        ]);

        $response->assertCreated();

        return (int) $response->json('data.id');
    }

    private function reservation(float $totalPrice): Reservation
    {
        [$pickupLocation, $dropoffLocation] = $this->locations();

        return Reservation::create([
            'reservation_number' => 'RSV-PAY-'.fake()->unique()->numerify('####'),
            'customer_id' => Customer::factory()->create()->id,
            'vehicle_id' => $this->vehicle()->id,
            'source_id' => ReservationSource::where('slug', 'website')->value('id'),
            'status_id' => ReservationStatus::where('slug', 'confirmed')->value('id'),
            'payment_status_id' => PaymentStatus::where('slug', 'unpaid')->value('id'),
            'pickup_location_id' => $pickupLocation->id,
            'dropoff_location_id' => $dropoffLocation->id,
            'start_datetime' => now()->addDays(5)->setTime(10, 0),
            'end_datetime' => now()->addDays(8)->setTime(10, 0),
            'total_days' => 3,
            'price_per_day' => 300,
            'delivery_fee' => 100,
            'deposit_amount' => 0,
            'total_price' => $totalPrice,
        ]);
    }

    private function vehicle(): Vehicle
    {
        return Vehicle::create([
            'brand_id' => VehicleBrand::where('slug', 'dacia')->value('id'),
            'category_id' => VehicleCategory::where('slug', 'economy')->value('id'),
            'status_id' => VehicleStatus::where('slug', 'available')->value('id'),
            'transmission_type_id' => TransmissionType::where('slug', 'manual')->value('id'),
            'fuel_type_id' => FuelType::where('slug', 'diesel')->value('id'),
            'name' => 'Payment Test Car '.fake()->unique()->numerify('###'),
            'slug' => 'payment-test-car-'.fake()->unique()->numerify('###'),
            'model' => 'Sandero',
            'year' => 2024,
            'plate_number' => fake()->unique()->bothify('PAY###'),
            'mileage' => 10000,
            'current_mileage_updated_at' => now(),
            'seats' => 5,
            'doors' => 5,
            'daily_price' => 300,
            'weekly_price' => 1800,
            'monthly_price' => 7200,
            'deposit_amount' => 1000,
            'is_featured' => false,
            'is_active' => true,
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
                ['slug' => 'payment-agency'],
                [
                    'location_type_id' => $agencyTypeId,
                    'name' => 'Payment Agency',
                    'address' => 'Dakhla Center',
                    'delivery_fee' => 0,
                    'is_active' => true,
                ]
            ),
            Location::firstOrCreate(
                ['slug' => 'payment-airport'],
                [
                    'location_type_id' => $airportTypeId,
                    'name' => 'Payment Airport',
                    'address' => 'Dakhla Airport',
                    'delivery_fee' => 100,
                    'is_active' => true,
                ]
            ),
        ];
    }

}
