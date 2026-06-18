<?php

namespace Database\Seeders;

use App\Models\FuelType;
use App\Models\Location;
use App\Models\LocationType;
use App\Models\TransmissionType;
use App\Models\Vehicle;
use App\Models\VehicleBrand;
use App\Models\VehicleCategory;
use App\Models\VehiclePhoto;
use App\Models\VehicleStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

/**
 * Local demo fleet with sample locations, vehicles, brand logos, and vehicle photos.
 */
class DemoFleetSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment('local', 'testing')) {
            return;
        }

        $this->seedBrandLogos();
        $this->seedLocations();
        $this->seedVehicles();
    }

    private function seedBrandLogos(): void
    {
        $brands = VehicleBrand::query()->whereIn('slug', ['dacia', 'toyota', 'hyundai'])->get();

        foreach ($brands as $brand) {
            $path = "vehicle-brands/{$brand->id}/logo.jpg";
            $this->copyFixtureToPublic($path);
            $brand->update(['image_path' => $path]);
        }
    }

    private function seedLocations(): void
    {
        $agencyTypeId = LocationType::where('slug', 'agency')->value('id');
        $airportTypeId = LocationType::where('slug', 'airport')->value('id');

        Location::updateOrCreate(
            ['slug' => 'dakhla-agency'],
            [
                'location_type_id' => $agencyTypeId,
                'name' => 'Dakhla Agency',
                'address' => 'Dakhla Center, Morocco',
                'delivery_fee' => 0,
                'is_active' => true,
            ]
        );

        Location::updateOrCreate(
            ['slug' => 'dakhla-airport'],
            [
                'location_type_id' => $airportTypeId,
                'name' => 'Dakhla Airport',
                'address' => 'Dakhla Airport, Morocco',
                'delivery_fee' => 150,
                'is_active' => true,
            ]
        );
    }

    private function seedVehicles(): void
    {
        $availableStatusId = VehicleStatus::where('slug', 'available')->value('id');
        $manualTransmissionId = TransmissionType::where('slug', 'manual')->value('id');
        $automaticTransmissionId = TransmissionType::where('slug', 'automatic')->value('id');
        $dieselFuelId = FuelType::where('slug', 'diesel')->value('id');
        $petrolFuelId = FuelType::where('slug', 'petrol')->value('id');

        $dacia = VehicleBrand::where('slug', 'dacia')->firstOrFail();
        $toyota = VehicleBrand::where('slug', 'toyota')->firstOrFail();
        $hyundai = VehicleBrand::where('slug', 'hyundai')->firstOrFail();

        $economy = VehicleCategory::where('slug', 'economy')->firstOrFail();
        $suv = VehicleCategory::where('slug', 'suv')->firstOrFail();
        $compact = VehicleCategory::where('slug', 'compact')->firstOrFail();

        $vehicles = [
            [
                'slug' => 'dacia-sandero-2024',
                'brand_id' => $dacia->id,
                'category_id' => $economy->id,
                'transmission_type_id' => $manualTransmissionId,
                'fuel_type_id' => $dieselFuelId,
                'name' => 'Dacia Sandero 2024',
                'model' => 'Sandero',
                'year' => 2024,
                'plate_number' => '12345-A-10',
                'mileage' => 12500,
                'seats' => 5,
                'doors' => 5,
                'daily_price' => 350,
                'weekly_price' => 2200,
                'monthly_price' => 8500,
                'deposit_amount' => 3000,
                'description' => 'Reliable economy vehicle for city and short trips.',
                'is_featured' => true,
                'photos' => ['front.jpg', 'side.jpg', 'interior.jpg'],
            ],
            [
                'slug' => 'toyota-rav4-2023',
                'brand_id' => $toyota->id,
                'category_id' => $suv->id,
                'transmission_type_id' => $automaticTransmissionId,
                'fuel_type_id' => $petrolFuelId,
                'name' => 'Toyota RAV4 2023',
                'model' => 'RAV4',
                'year' => 2023,
                'plate_number' => '54321-B-20',
                'mileage' => 18000,
                'seats' => 5,
                'doors' => 5,
                'daily_price' => 650,
                'weekly_price' => 4000,
                'monthly_price' => 15000,
                'deposit_amount' => 5000,
                'description' => 'Comfortable SUV for family trips around Dakhla.',
                'is_featured' => true,
                'photos' => ['exterior.jpg', 'rear.jpg'],
            ],
            [
                'slug' => 'hyundai-i20-2022',
                'brand_id' => $hyundai->id,
                'category_id' => $compact->id,
                'transmission_type_id' => $manualTransmissionId,
                'fuel_type_id' => $petrolFuelId,
                'name' => 'Hyundai i20 2022',
                'model' => 'i20',
                'year' => 2022,
                'plate_number' => '98765-C-30',
                'mileage' => 22000,
                'seats' => 5,
                'doors' => 5,
                'daily_price' => 300,
                'weekly_price' => 1900,
                'monthly_price' => 7200,
                'deposit_amount' => 2500,
                'description' => 'Compact and fuel-efficient for daily rentals.',
                'is_featured' => false,
                'photos' => ['main.jpg'],
            ],
        ];

        foreach ($vehicles as $payload) {
            $photoFiles = $payload['photos'];
            unset($payload['photos']);

            $vehicle = Vehicle::updateOrCreate(
                ['slug' => $payload['slug']],
                [
                    ...$payload,
                    'status_id' => $availableStatusId,
                    'current_mileage_updated_at' => now()->subDay(),
                    'is_active' => true,
                ]
            );

            foreach ($photoFiles as $index => $filename) {
                $path = "vehicles/{$vehicle->id}/photos/{$filename}";
                $this->copyFixtureToPublic($path);

                VehiclePhoto::updateOrCreate(
                    ['vehicle_id' => $vehicle->id, 'path' => $path],
                    [
                        'alt_text' => "{$vehicle->name} photo ".($index + 1),
                        'sort_order' => $index + 1,
                        'is_primary' => $index === 0,
                    ]
                );
            }
        }
    }

    private function copyFixtureToPublic(string $relativePath): void
    {
        $source = base_path('scripts/qa/fixtures/sample.jpg');

        if (! File::exists($source)) {
            return;
        }

        Storage::disk('public')->makeDirectory(dirname($relativePath));
        Storage::disk('public')->put($relativePath, File::get($source));
    }
}
