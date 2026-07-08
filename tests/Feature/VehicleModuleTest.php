<?php

namespace Tests\Feature;

use App\Models\DocumentType;
use App\Models\FuelType;
use App\Models\TransmissionType;
use App\Models\Vehicle;
use App\Models\VehicleBrand;
use App\Models\VehicleCategory;
use App\Models\VehicleDocument;
use App\Models\VehiclePhoto;
use App\Models\VehicleStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VehicleModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_vehicle_brand_and_category_seeders_create_sample_values(): void
    {
        $this->seed();

        $this->assertDatabaseHas('vehicle_brands', ['slug' => 'dacia']);
        $this->assertDatabaseHas('vehicle_brands', ['slug' => 'toyota']);
        $this->assertDatabaseHas('vehicle_categories', ['slug' => 'economy']);
        $this->assertDatabaseHas('vehicle_categories', ['slug' => 'suv']);
    }

    public function test_admin_can_create_list_update_show_and_delete_vehicle(): void
    {
        $this->seed();

        $token = $this->adminToken();
        $brand = VehicleBrand::where('slug', 'dacia')->firstOrFail();
        $category = VehicleCategory::where('slug', 'economy')->firstOrFail();

        $createResponse = $this->withToken($token)->postJson('/api/admin/vehicles', [
            'brand_id' => $brand->id,
            'category_id' => $category->id,
            'status_slug' => 'available',
            'transmission_type_slug' => 'manual',
            'fuel_type_slug' => 'diesel',
            'name' => 'Dacia Sandero 2024',
            'model' => 'Sandero',
            'plate_number' => '12345-A-10',
            'seats' => 5,
            'doors' => 5,
            'daily_price' => 350,
            'weekly_price' => 2200,
            'monthly_price' => 8500,
            'deposit_amount' => 3000,
            'description' => 'Reliable economy vehicle for Dakhla rentals.',
            'is_featured' => true,
            'is_active' => true,
        ]);

        $createResponse
            ->assertCreated()
            ->assertJsonPath('data.slug', 'dacia-sandero-2024')
            ->assertJsonMissingPath('data.year')
            ->assertJsonMissingPath('data.mileage')
            ->assertJsonPath('data.status.slug', 'available')
            ->assertJsonPath('data.transmission_type.slug', 'manual')
            ->assertJsonPath('data.fuel_type.slug', 'diesel');

        $vehicle = Vehicle::where('slug', 'dacia-sandero-2024')->firstOrFail();

        $this->assertSame(VehicleStatus::where('slug', 'available')->value('id'), $vehicle->status_id);
        $this->assertSame(TransmissionType::where('slug', 'manual')->value('id'), $vehicle->transmission_type_id);
        $this->assertSame(FuelType::where('slug', 'diesel')->value('id'), $vehicle->fuel_type_id);

        VehiclePhoto::create([
            'vehicle_id' => $vehicle->id,
            'path' => 'vehicles/dacia-sandero/front.jpg',
            'alt_text' => 'Dacia Sandero front view',
            'sort_order' => 1,
            'is_primary' => true,
        ]);

        VehicleDocument::create([
            'vehicle_id' => $vehicle->id,
            'document_type_id' => DocumentType::where('slug', 'insurance')->value('id'),
            'title' => 'Insurance 2026',
            'file_path' => 'documents/dacia-sandero/insurance.pdf',
            'expires_at' => now()->addYear()->toDateString(),
        ]);

        $this->withToken($token)
            ->getJson('/api/admin/vehicles')
            ->assertOk()
            ->assertJsonPath('data.0.slug', 'dacia-sandero-2024');

        $this->withToken($token)
            ->getJson("/api/admin/vehicles/{$vehicle->id}")
            ->assertOk()
            ->assertJsonPath('data.photos.0.is_primary', true)
            ->assertJsonPath('data.documents.0.document_type.slug', 'insurance');

        $this->withToken($token)
            ->patchJson("/api/admin/vehicles/{$vehicle->id}", [
                'status_slug' => 'maintenance',
                'is_featured' => false,
            ])
            ->assertOk()
            ->assertJsonPath('data.status.slug', 'maintenance')
            ->assertJsonMissingPath('data.mileage');

        $this->assertSame(VehicleStatus::where('slug', 'maintenance')->value('id'), $vehicle->refresh()->status_id);

        $this->withToken($token)
            ->deleteJson("/api/admin/vehicles/{$vehicle->id}")
            ->assertNoContent();

        $this->assertSoftDeleted('vehicles', ['id' => $vehicle->id]);
    }

    public function test_admin_can_upload_multiple_vehicle_photos_and_manage_primary(): void
    {
        $this->seed();

        $token = $this->adminToken();
        $vehicle = Vehicle::factory()->create([
            'brand_id' => VehicleBrand::where('slug', 'dacia')->value('id'),
            'category_id' => VehicleCategory::where('slug', 'economy')->value('id'),
            'status_id' => VehicleStatus::where('slug', 'available')->value('id'),
            'transmission_type_id' => TransmissionType::where('slug', 'manual')->value('id'),
            'fuel_type_id' => FuelType::where('slug', 'diesel')->value('id'),
        ]);

        $uploadResponse = $this->withToken($token)->post("/api/admin/vehicles/{$vehicle->id}/photos", [
            'photos' => [
                \Illuminate\Http\UploadedFile::fake()->create('front.jpg', 100, 'image/jpeg'),
                \Illuminate\Http\UploadedFile::fake()->create('side.jpg', 100, 'image/jpeg'),
            ],
            'is_primary' => true,
        ]);

        $uploadResponse
            ->assertCreated()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('data.0.is_primary', true)
            ->assertJsonPath('data.1.is_primary', false);

        $secondPhotoId = $uploadResponse->json('data.1.id');

        $this->withToken($token)
            ->patchJson("/api/admin/vehicle-photos/{$secondPhotoId}", [
                'is_primary' => true,
            ])
            ->assertOk()
            ->assertJsonPath('data.is_primary', true);

        $this->withToken($token)
            ->getJson("/api/admin/vehicles/{$vehicle->id}")
            ->assertOk()
            ->assertJsonPath('data.photos.0.is_primary', false)
            ->assertJsonPath('data.photos.1.is_primary', true);

        $firstPhotoId = VehiclePhoto::query()->where('vehicle_id', $vehicle->id)->orderBy('sort_order')->value('id');

        $this->withToken($token)
            ->deleteJson("/api/admin/vehicle-photos/{$firstPhotoId}")
            ->assertNoContent();

        $this->assertSoftDeleted('vehicle_photos', ['id' => $firstPhotoId]);
    }

    public function test_admin_can_upload_and_remove_vehicle_brand_image(): void
    {
        $this->seed();

        $token = $this->adminToken();
        $brand = VehicleBrand::where('slug', 'dacia')->firstOrFail();

        $this->withToken($token)
            ->post("/api/admin/vehicle-brands/{$brand->id}/image", [
                'image' => \Illuminate\Http\UploadedFile::fake()->create('dacia-logo.png', 100, 'image/png'),
            ])
            ->assertOk()
            ->assertJsonPath('data.image_path', fn (string $path): bool => str_starts_with($path, "vehicle-brands/{$brand->id}/"));

        $this->assertNotNull($brand->refresh()->image_path);
        \Illuminate\Support\Facades\Storage::disk('public')->assertExists($brand->image_path);

        $this->withToken($token)
            ->deleteJson("/api/admin/vehicle-brands/{$brand->id}/image")
            ->assertOk()
            ->assertJsonPath('data.image_path', null);

        $this->assertNull($brand->refresh()->image_path);
    }

}
