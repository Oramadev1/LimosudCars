<?php

namespace Tests\Feature;

use App\Models\Alert;
use App\Models\ExpenseCategory;
use App\Models\FuelType;
use App\Models\MaintenanceType;
use App\Models\TransmissionType;
use App\Models\Vehicle;
use App\Models\VehicleBrand;
use App\Models\VehicleCategory;
use App\Models\VehicleMaintenance;
use App\Models\VehicleStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MaintenanceExpenseAlertModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_maintenance_with_lookup_slug_and_optional_side_effects(): void
    {
        $this->seed();
        $token = $this->adminToken();
        $vehicle = $this->vehicle();

        $response = $this->withToken($token)->postJson('/api/admin/maintenances', [
            'vehicle_id' => $vehicle->id,
            'maintenance_type_slug' => 'oil_change',
            'maintenance_date' => now()->toDateString(),
            'next_maintenance_date' => now()->addMonth()->toDateString(),
            'mileage' => 21000,
            'cost' => 450,
            'garage_name' => 'Dakhla Garage',
            'notes' => 'Routine service.',
            'vehicle_status_slug' => 'maintenance',
            'create_expense' => true,
            'expense_category_slug' => 'maintenance',
        ]);

        $response
            ->assertCreated()
            ->assertJsonPath('data.vehicle.id', $vehicle->id)
            ->assertJsonPath('data.maintenance_type.slug', 'oil_change')
            ->assertJsonPath('data.garage_name', 'Dakhla Garage');

        $maintenance = VehicleMaintenance::firstOrFail();

        $this->assertTrue($maintenance->vehicle->is($vehicle));
        $this->assertSame('maintenance', $vehicle->refresh()->status->slug);
        $this->assertDatabaseHas('expenses', [
            'vehicle_id' => $vehicle->id,
            'expense_category_id' => ExpenseCategory::where('slug', 'maintenance')->value('id'),
            'amount' => 450,
        ]);
    }

    public function test_upcoming_maintenance_endpoint_lists_future_next_maintenance_dates(): void
    {
        $this->seed();
        $token = $this->adminToken();
        $vehicle = $this->vehicle();

        VehicleMaintenance::create([
            'vehicle_id' => $vehicle->id,
            'maintenance_type_id' => MaintenanceType::where('slug', 'inspection')->value('id'),
            'maintenance_date' => now()->subMonth()->toDateString(),
            'next_maintenance_date' => now()->addDays(10)->toDateString(),
            'cost' => 0,
        ]);

        $this->withToken($token)
            ->getJson('/api/admin/maintenances/upcoming')
            ->assertOk()
            ->assertJsonPath('data.0.vehicle.id', $vehicle->id)
            ->assertJsonPath('data.0.maintenance_type.slug', 'inspection');
    }

    public function test_admin_can_create_expenses_with_and_without_vehicle_and_get_monthly_summary(): void
    {
        $this->seed();
        $token = $this->adminToken();
        $vehicle = $this->vehicle();
        $date = now()->setDay(10);

        $this->withToken($token)->postJson('/api/admin/expenses', [
            'vehicle_id' => $vehicle->id,
            'expense_category_slug' => 'fuel',
            'amount' => 200,
            'expense_date' => $date->toDateString(),
            'description' => 'Diesel refill.',
        ])->assertCreated()
            ->assertJsonPath('data.vehicle.id', $vehicle->id)
            ->assertJsonPath('data.expense_category.slug', 'fuel')
            ->assertJsonPath('data.has_invoice', false);

        $this->withToken($token)->postJson('/api/admin/expenses', [
            'expense_category_slug' => 'taxes',
            'amount' => 100,
            'expense_date' => $date->toDateString(),
            'description' => 'Administrative fee.',
        ])->assertCreated()
            ->assertJsonPath('data.vehicle', null)
            ->assertJsonPath('data.expense_category.slug', 'taxes');

        $this->withToken($token)
            ->getJson('/api/admin/expenses/monthly-summary?year='.$date->year.'&month='.$date->month)
            ->assertOk()
            ->assertJsonPath('total_amount', 300)
            ->assertJsonPath('expense_count', 2);
    }

    public function test_admin_can_create_alert_and_transition_allowed_statuses(): void
    {
        $this->seed();
        $token = $this->adminToken();
        $vehicle = $this->vehicle();

        $response = $this->withToken($token)->postJson('/api/admin/alerts', [
            'vehicle_id' => $vehicle->id,
            'alert_type_slug' => 'maintenance_due',
            'title' => 'Oil change due',
            'message' => 'Schedule oil change.',
            'due_date' => now()->addDays(5)->toDateString(),
        ]);

        $alertId = (int) $response
            ->assertCreated()
            ->assertJsonPath('data.alert_type.slug', 'maintenance_due')
            ->assertJsonPath('data.alert_status.slug', 'pending')
            ->json('data.id');

        $this->withToken($token)
            ->patchJson("/api/admin/alerts/{$alertId}/done")
            ->assertOk()
            ->assertJsonPath('data.alert_status.slug', 'done');
    }

    public function test_duplicate_pending_alerts_are_prevented_for_same_vehicle_type_and_due_date(): void
    {
        $this->seed();
        $token = $this->adminToken();
        $vehicle = $this->vehicle();
        $dueDate = now()->addWeek()->toDateString();
        $payload = [
            'vehicle_id' => $vehicle->id,
            'alert_type_slug' => 'maintenance_due',
            'title' => 'Maintenance due',
            'due_date' => $dueDate,
        ];

        $firstId = $this->withToken($token)
            ->postJson('/api/admin/alerts', $payload)
            ->assertCreated()
            ->json('data.id');

        $secondId = $this->withToken($token)
            ->postJson('/api/admin/alerts', $payload)
            ->assertCreated()
            ->json('data.id');

        $this->assertSame($firstId, $secondId);
        $this->assertSame(1, Alert::count());
    }

    public function test_generate_endpoint_creates_maintenance_alerts(): void
    {
        $this->seed();
        $token = $this->adminToken();
        $vehicle = $this->vehicle();

        VehicleMaintenance::create([
            'vehicle_id' => $vehicle->id,
            'maintenance_type_id' => MaintenanceType::where('slug', 'inspection')->value('id'),
            'maintenance_date' => now()->subMonth()->toDateString(),
            'next_maintenance_date' => now()->addDays(7)->toDateString(),
            'cost' => 0,
        ]);

        $this->withToken($token)
            ->postJson('/api/admin/alerts/generate')
            ->assertOk()
            ->assertJsonPath('maintenance_alerts_created', 1)
            ->assertJsonPath('total_created', 1);

        $alert = Alert::firstOrFail();

        $this->assertSame('maintenance_due', $alert->alertType->slug);
        $this->assertSame('pending', $alert->alertStatus->slug);
        $this->assertSame($vehicle->id, $alert->vehicle_id);
    }

    public function test_generate_endpoint_does_not_recreate_handled_alerts(): void
    {
        $this->seed();
        $token = $this->adminToken();
        $vehicle = $this->vehicle();

        VehicleMaintenance::create([
            'vehicle_id' => $vehicle->id,
            'maintenance_type_id' => MaintenanceType::where('slug', 'inspection')->value('id'),
            'maintenance_date' => now()->subMonth()->toDateString(),
            'next_maintenance_date' => now()->addDays(7)->toDateString(),
            'cost' => 0,
        ]);

        $this->withToken($token)
            ->postJson('/api/admin/alerts/generate')
            ->assertOk()
            ->assertJsonPath('total_created', 1);

        $alertId = Alert::firstOrFail()->id;

        $this->withToken($token)
            ->patchJson("/api/admin/alerts/{$alertId}/done")
            ->assertOk();

        $this->withToken($token)
            ->postJson('/api/admin/alerts/generate')
            ->assertOk()
            ->assertJsonPath('total_created', 0);

        $this->assertSame(1, Alert::count());
    }

    private function vehicle(): Vehicle
    {
        return Vehicle::factory()->create([
            'name' => 'Maintenance Test Car '.fake()->unique()->numerify('###'),
            'slug' => 'maintenance-test-car-'.fake()->unique()->numerify('###'),
            'plate_number' => fake()->unique()->bothify('MNT###'),
        ]);
    }

    private function adminToken(): string
    {
        $response = $this->postJson('/api/admin/auth/login', [
            'email' => env('ADMIN_EMAIL', 'admin@limosudcars.local'),
            'password' => env('ADMIN_PASSWORD', 'password'),
        ]);

        $response->assertOk();

        return $response->json('access_token');
    }
}
