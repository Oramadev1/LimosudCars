<?php

namespace Tests\Feature;

use App\Models\FuelType;
use App\Models\TransmissionType;
use App\Models\Vehicle;
use App\Models\VehicleBrand;
use App\Models\VehicleCategory;
use App\Models\VehiclePhoto;
use App\Models\VehicleStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FrontendIntegrationGapEndpointsTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_vehicle_endpoints_return_only_active_vehicles_with_relationships_and_availability(): void
    {
        $this->seed();
        $activeVehicle = $this->vehicle('public-active-car', true);
        $inactiveVehicle = $this->vehicle('public-inactive-car', false);

        VehiclePhoto::create([
            'vehicle_id' => $activeVehicle->id,
            'path' => 'vehicles/public-active/front.jpg',
            'alt_text' => 'Front view',
            'sort_order' => 1,
            'is_primary' => true,
        ]);

        $this->getJson('/api/public/vehicles')
            ->assertOk()
            ->assertJsonPath('data.0.slug', 'public-active-car')
            ->assertJsonPath('data.0.brand.name', 'Dacia')
            ->assertJsonPath('data.0.category.name', 'Economy')
            ->assertJsonPath('data.0.status.slug', 'available')
            ->assertJsonPath('data.0.transmission_type.name', 'Manual')
            ->assertJsonPath('data.0.fuel_type.name', 'Diesel')
            ->assertJsonPath('data.0.photos.0.is_primary', true)
            ->assertJsonMissingPath('data.0.year')
            ->assertJsonMissingPath('data.0.mileage')
            ->assertJsonMissingPath('data.0.plate_number')
            ->assertJsonMissingPath('data.0.brand.slug')
            ->assertJsonMissingPath('data.0.category.slug')
            ->assertJsonMissing(['slug' => $inactiveVehicle->slug]);

        $this->getJson('/api/public/vehicles/public-active-car')
            ->assertOk()
            ->assertJsonPath('data.slug', 'public-active-car')
            ->assertJsonPath('data.photos.0.path', 'vehicles/public-active/front.jpg');

        $this->getJson('/api/public/vehicles/public-inactive-car')
            ->assertNotFound();

        $this->getJson("/api/public/vehicles/{$activeVehicle->id}/availability?start_datetime=".now()->addDay()->toDateTimeString().'&end_datetime='.now()->addDays(3)->toDateTimeString())
            ->assertOk()
            ->assertJsonPath('vehicle_id', $activeVehicle->id)
            ->assertJsonPath('available', true);

        $this->getJson("/api/public/vehicles/{$activeVehicle->id}/schedule")
            ->assertOk()
            ->assertJsonPath('vehicle_id', $activeVehicle->id)
            ->assertJsonStructure([
                'vehicle_id',
                'vehicle_rentable',
                'vehicle_status',
                'blocked_periods',
            ]);
    }

    public function test_public_and_admin_lookup_endpoints_return_expected_data(): void
    {
        $this->seed();
        $token = $this->adminToken();

        VehicleBrand::create([
            'name' => 'Inactive Brand',
            'slug' => 'inactive-brand',
            'is_active' => false,
        ]);

        $this->getJson('/api/public/lookups')
            ->assertOk()
            ->assertJsonStructure([
                'vehicle_brands',
                'vehicle_categories',
                'transmission_types',
                'fuel_types',
                'locations',
            ])
            ->assertJsonMissing(['slug' => 'inactive-brand']);

        $this->getJson('/api/admin/lookups')
            ->assertUnauthorized();

        $this->withToken($token)
            ->getJson('/api/admin/lookups')
            ->assertOk()
            ->assertJsonStructure([
                'vehicle_statuses',
                'transmission_types',
                'fuel_types',
                'reservation_statuses',
                'payment_statuses',
                'payment_methods',
                'payment_types',
                'reservation_sources',
                'location_types',
                'maintenance_types',
                'expense_categories',
                'alert_types',
                'alert_statuses',
                'document_types',
                'contract_statuses',
                'vehicle_brands',
                'vehicle_categories',
                'locations',
            ])
            ->assertJsonFragment(['slug' => 'inactive-brand']);
    }

    public function test_admin_can_crud_vehicle_brands_and_categories(): void
    {
        $this->seed();
        $token = $this->adminToken();

        $brandId = (int) $this->withToken($token)
            ->postJson('/api/admin/vehicle-brands', [
                'name' => 'Mercedes',
                'slug' => 'mercedes',
                'is_active' => true,
            ])
            ->assertCreated()
            ->assertJsonPath('data.slug', 'mercedes')
            ->json('data.id');

        $this->withToken($token)
            ->getJson('/api/admin/vehicle-brands')
            ->assertOk()
            ->assertJsonFragment(['slug' => 'mercedes']);

        $this->withToken($token)
            ->patchJson("/api/admin/vehicle-brands/{$brandId}", [
                'name' => 'Mercedes-Benz',
            ])
            ->assertOk()
            ->assertJsonPath('data.name', 'Mercedes-Benz');

        $this->withToken($token)
            ->getJson("/api/admin/vehicle-brands/{$brandId}")
            ->assertOk()
            ->assertJsonPath('data.slug', 'mercedes');

        $categoryId = (int) $this->withToken($token)
            ->postJson('/api/admin/vehicle-categories', [
                'name' => 'Executive',
                'slug' => 'executive',
                'description' => 'Premium vehicles.',
                'is_active' => true,
            ])
            ->assertCreated()
            ->assertJsonPath('data.slug', 'executive')
            ->json('data.id');

        $this->withToken($token)
            ->putJson("/api/admin/vehicle-categories/{$categoryId}", [
                'description' => 'Premium chauffeur vehicles.',
            ])
            ->assertOk()
            ->assertJsonPath('data.description', 'Premium chauffeur vehicles.');

        $this->withToken($token)
            ->deleteJson("/api/admin/vehicle-brands/{$brandId}")
            ->assertNoContent();

        $this->withToken($token)
            ->deleteJson("/api/admin/vehicle-categories/{$categoryId}")
            ->assertNoContent();

        $this->assertSoftDeleted('vehicle_brands', ['id' => $brandId]);
        $this->assertSoftDeleted('vehicle_categories', ['id' => $categoryId]);
    }

    private function vehicle(string $slug, bool $isActive): Vehicle
    {
        return Vehicle::create([
            'brand_id' => VehicleBrand::where('slug', 'dacia')->value('id'),
            'category_id' => VehicleCategory::where('slug', 'economy')->value('id'),
            'status_id' => VehicleStatus::where('slug', 'available')->value('id'),
            'transmission_type_id' => TransmissionType::where('slug', 'manual')->value('id'),
            'fuel_type_id' => FuelType::where('slug', 'diesel')->value('id'),
            'name' => str($slug)->title()->replace('-', ' ')->toString(),
            'slug' => $slug,
            'model' => 'Sandero',
            'year' => 2024,
            'plate_number' => fake()->unique()->bothify('PUB###'),
            'mileage' => 12000,
            'current_mileage_updated_at' => now(),
            'seats' => 5,
            'doors' => 5,
            'daily_price' => 350,
            'weekly_price' => 2200,
            'monthly_price' => 8500,
            'deposit_amount' => 3000,
            'is_featured' => false,
            'is_active' => $isActive,
        ]);
    }

}
