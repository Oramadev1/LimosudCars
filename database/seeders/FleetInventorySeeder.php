<?php

namespace Database\Seeders;

use App\Models\FuelType;
use App\Models\TransmissionType;
use App\Models\Vehicle;
use App\Models\VehicleBrand;
use App\Models\VehicleCategory;
use App\Models\VehicleStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Limosud fleet inventory from operational vehicle list.
 *
 * Manual seeder only — NOT registered in DatabaseSeeder (production stays login-only).
 *
 * Run after migrate (lookups are seeded by migration):
 *   php artisan db:seed --class=FleetInventorySeeder
 */
class FleetInventorySeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            VehicleBrandSeeder::class,
            VehicleCategorySeeder::class,
        ]);

        $this->seedFleetVehicles();
    }

    private function seedFleetVehicles(): void
    {
        $availableStatusId = VehicleStatus::where('slug', 'available')->value('id');
        $manualTransmissionId = TransmissionType::where('slug', 'manual')->value('id');
        $automaticTransmissionId = TransmissionType::where('slug', 'automatic')->value('id');
        $dieselFuelId = FuelType::where('slug', 'diesel')->value('id');
        $gasolineFuelId = FuelType::where('slug', 'gasoline')->value('id');
        $hybridFuelId = FuelType::where('slug', 'hybrid')->value('id');

        $brands = VehicleBrand::query()
            ->whereIn('slug', [
                'dacia', 'skoda', 'opel', 'hyundai', 'suzuki', 'nissan',
                'renault', 'toyota', 'kia', 'fiat',
            ])
            ->get()
            ->keyBy('slug');

        $economy = VehicleCategory::where('slug', 'economy')->firstOrFail();
        $suv = VehicleCategory::where('slug', 'suv')->firstOrFail();
        $van = VehicleCategory::where('slug', 'van')->firstOrFail();

        $transmissionIds = [
            'manual' => $manualTransmissionId,
            'automatic' => $automaticTransmissionId,
        ];

        $fuelIds = [
            'diesel' => $dieselFuelId,
            'gasoline' => $gasolineFuelId,
            'hybrid' => $hybridFuelId,
        ];

        $pricing = [
            'economy' => ['daily' => 350, 'weekly' => 2200, 'monthly' => 8500, 'deposit' => 3000],
            'suv' => ['daily' => 550, 'weekly' => 3400, 'monthly' => 12500, 'deposit' => 4000],
            'van' => ['daily' => 700, 'weekly' => 4300, 'monthly' => 16000, 'deposit' => 5000],
        ];

        $this->cleanupLegacyFleetSlugs();

        foreach ($this->fleetRows() as $index => $row) {
            $fleetNumber = $index + 1;
            $brand = $brands[$row['brand_slug']];
            $categorySlug = $row['category'];
            $category = match ($categorySlug) {
                'suv' => $suv,
                'van' => $van,
                default => $economy,
            };
            $price = $pricing[$categorySlug === 'economy' ? 'economy' : $categorySlug];

            $color = $this->normalizeColor($row['color']);
            $model = $row['model'];
            $name = $model !== null
                ? "{$brand->name} {$model} {$color}"
                : "{$brand->name} {$color}";
            $slug = sprintf('fleet-%03d', $fleetNumber);
            $plateNumber = sprintf('LS-F%03d-A-48', $fleetNumber);

            $description = "Couleur: {$color}. PF: {$row['fiscal_power']}. Cylindres: {$row['cylinders']}.";
            if ($row['note'] !== null) {
                $description .= " Note: {$row['note']}.";
            }

            Vehicle::updateOrCreate(
                ['slug' => $slug],
                [
                    'brand_id' => $brand->id,
                    'category_id' => $category->id,
                    'status_id' => $availableStatusId,
                    'transmission_type_id' => $transmissionIds[$row['transmission']],
                    'fuel_type_id' => $fuelIds[$row['fuel']],
                    'name' => $name,
                    'model' => $model ?? 'Unknown',
                    'year' => 2024,
                    'plate_number' => $plateNumber,
                    'mileage' => 0,
                    'current_mileage_updated_at' => now()->subDay(),
                    'seats' => $row['seats'],
                    'doors' => 5,
                    'daily_price' => $price['daily'],
                    'weekly_price' => $price['weekly'],
                    'monthly_price' => $price['monthly'],
                    'deposit_amount' => $price['deposit'],
                    'description' => $description,
                    'is_featured' => false,
                    'is_active' => true,
                ]
            );
        }
    }

    private function cleanupLegacyFleetSlugs(): void
    {
        Vehicle::withTrashed()
            ->where('slug', 'like', 'fleet-%-%')
            ->forceDelete();
    }

    /**
     * Normalized fleet rows: brand_slug, model, color, fiscal_power, cylinders, fuel, transmission, category, seats, note.
     *
     * @return list<array{
     *     brand_slug: string,
     *     model: string|null,
     *     color: string,
     *     fiscal_power: int,
     *     cylinders: int,
     *     fuel: string,
     *     transmission: string,
     *     category: string,
     *     seats: int,
     *     note: string|null
     * }>
     */
    private function fleetRows(): array
    {
        return [
            ['brand_slug' => 'dacia', 'model' => 'Logan', 'color' => 'Gris', 'fiscal_power' => 6, 'cylinders' => 3, 'fuel' => 'gasoline', 'transmission' => 'automatic', 'category' => 'economy', 'seats' => 5, 'note' => null],
            ['brand_slug' => 'dacia', 'model' => 'Logan', 'color' => 'Noir', 'fiscal_power' => 6, 'cylinders' => 3, 'fuel' => 'gasoline', 'transmission' => 'automatic', 'category' => 'economy', 'seats' => 5, 'note' => null],
            ['brand_slug' => 'dacia', 'model' => 'Logan', 'color' => 'Gris', 'fiscal_power' => 6, 'cylinders' => 3, 'fuel' => 'gasoline', 'transmission' => 'automatic', 'category' => 'economy', 'seats' => 5, 'note' => null],
            ['brand_slug' => 'dacia', 'model' => 'Logan', 'color' => 'Gris', 'fiscal_power' => 6, 'cylinders' => 3, 'fuel' => 'gasoline', 'transmission' => 'automatic', 'category' => 'economy', 'seats' => 5, 'note' => null],
            ['brand_slug' => 'dacia', 'model' => 'Logan', 'color' => 'Noir', 'fiscal_power' => 6, 'cylinders' => 3, 'fuel' => 'gasoline', 'transmission' => 'automatic', 'category' => 'economy', 'seats' => 5, 'note' => null],
            ['brand_slug' => 'dacia', 'model' => 'Logan', 'color' => 'Gris', 'fiscal_power' => 6, 'cylinders' => 3, 'fuel' => 'gasoline', 'transmission' => 'automatic', 'category' => 'economy', 'seats' => 5, 'note' => null],
            ['brand_slug' => 'dacia', 'model' => 'Logan', 'color' => 'Gris', 'fiscal_power' => 6, 'cylinders' => 3, 'fuel' => 'gasoline', 'transmission' => 'automatic', 'category' => 'economy', 'seats' => 5, 'note' => null],
            ['brand_slug' => 'skoda', 'model' => 'Scala', 'color' => 'Gris Foncé', 'fiscal_power' => 6, 'cylinders' => 3, 'fuel' => 'gasoline', 'transmission' => 'automatic', 'category' => 'economy', 'seats' => 5, 'note' => null],
            ['brand_slug' => 'skoda', 'model' => 'Kamiq', 'color' => 'Noir', 'fiscal_power' => 6, 'cylinders' => 3, 'fuel' => 'gasoline', 'transmission' => 'automatic', 'category' => 'economy', 'seats' => 5, 'note' => null],
            ['brand_slug' => 'dacia', 'model' => 'Sandero', 'color' => 'Gris', 'fiscal_power' => 6, 'cylinders' => 4, 'fuel' => 'diesel', 'transmission' => 'manual', 'category' => 'economy', 'seats' => 5, 'note' => null],
            ['brand_slug' => 'dacia', 'model' => 'Stepway', 'color' => 'Noir', 'fiscal_power' => 6, 'cylinders' => 4, 'fuel' => 'diesel', 'transmission' => 'manual', 'category' => 'economy', 'seats' => 5, 'note' => null],
            ['brand_slug' => 'dacia', 'model' => 'Duster', 'color' => 'Blanc', 'fiscal_power' => 6, 'cylinders' => 4, 'fuel' => 'diesel', 'transmission' => 'manual', 'category' => 'suv', 'seats' => 5, 'note' => null],
            ['brand_slug' => 'dacia', 'model' => 'Duster', 'color' => 'Blanc', 'fiscal_power' => 6, 'cylinders' => 4, 'fuel' => 'diesel', 'transmission' => 'manual', 'category' => 'suv', 'seats' => 5, 'note' => null],
            ['brand_slug' => 'dacia', 'model' => 'Sandero', 'color' => 'Gris', 'fiscal_power' => 6, 'cylinders' => 4, 'fuel' => 'diesel', 'transmission' => 'manual', 'category' => 'economy', 'seats' => 5, 'note' => null],
            ['brand_slug' => 'dacia', 'model' => 'Logan', 'color' => 'Gris Urbain', 'fiscal_power' => 6, 'cylinders' => 3, 'fuel' => 'diesel', 'transmission' => 'manual', 'category' => 'economy', 'seats' => 5, 'note' => null],
            ['brand_slug' => 'dacia', 'model' => 'Jogger', 'color' => 'Blanc', 'fiscal_power' => 6, 'cylinders' => 4, 'fuel' => 'diesel', 'transmission' => 'manual', 'category' => 'van', 'seats' => 7, 'note' => null],
            ['brand_slug' => 'opel', 'model' => 'Corsa', 'color' => 'Gris Urbain', 'fiscal_power' => 6, 'cylinders' => 4, 'fuel' => 'diesel', 'transmission' => 'manual', 'category' => 'economy', 'seats' => 5, 'note' => null],
            ['brand_slug' => 'dacia', 'model' => 'Logan', 'color' => 'Noir', 'fiscal_power' => 6, 'cylinders' => 3, 'fuel' => 'gasoline', 'transmission' => 'automatic', 'category' => 'economy', 'seats' => 5, 'note' => null],
            ['brand_slug' => 'dacia', 'model' => 'Duster', 'color' => 'Blanc', 'fiscal_power' => 6, 'cylinders' => 4, 'fuel' => 'diesel', 'transmission' => 'manual', 'category' => 'suv', 'seats' => 5, 'note' => null],
            ['brand_slug' => 'dacia', 'model' => 'Logan', 'color' => 'Blanc', 'fiscal_power' => 6, 'cylinders' => 3, 'fuel' => 'gasoline', 'transmission' => 'automatic', 'category' => 'economy', 'seats' => 5, 'note' => null],
            ['brand_slug' => 'hyundai', 'model' => 'Accent', 'color' => 'Noir', 'fiscal_power' => 9, 'cylinders' => 4, 'fuel' => 'gasoline', 'transmission' => 'automatic', 'category' => 'economy', 'seats' => 5, 'note' => null],
            ['brand_slug' => 'suzuki', 'model' => 'Jimny', 'color' => 'Chiffon Sablé', 'fiscal_power' => 8, 'cylinders' => 4, 'fuel' => 'gasoline', 'transmission' => 'automatic', 'category' => 'suv', 'seats' => 4, 'note' => null],
            ['brand_slug' => 'dacia', 'model' => 'Duster', 'color' => 'Blanc', 'fiscal_power' => 6, 'cylinders' => 4, 'fuel' => 'diesel', 'transmission' => 'manual', 'category' => 'suv', 'seats' => 5, 'note' => null],
            ['brand_slug' => 'dacia', 'model' => 'Stepway', 'color' => 'Gris Urbain', 'fiscal_power' => 6, 'cylinders' => 4, 'fuel' => 'diesel', 'transmission' => 'manual', 'category' => 'economy', 'seats' => 5, 'note' => null],
            ['brand_slug' => 'dacia', 'model' => 'Jogger', 'color' => 'Blanc', 'fiscal_power' => 6, 'cylinders' => 4, 'fuel' => 'diesel', 'transmission' => 'manual', 'category' => 'van', 'seats' => 7, 'note' => null],
            ['brand_slug' => 'dacia', 'model' => 'Logan', 'color' => 'Gris Urbain', 'fiscal_power' => 6, 'cylinders' => 4, 'fuel' => 'diesel', 'transmission' => 'manual', 'category' => 'economy', 'seats' => 5, 'note' => null],
            ['brand_slug' => 'dacia', 'model' => 'Logan', 'color' => 'Gris Urbain', 'fiscal_power' => 6, 'cylinders' => 4, 'fuel' => 'diesel', 'transmission' => 'manual', 'category' => 'economy', 'seats' => 5, 'note' => null],
            ['brand_slug' => 'dacia', 'model' => 'Logan', 'color' => 'Gris Urbain', 'fiscal_power' => 6, 'cylinders' => 4, 'fuel' => 'diesel', 'transmission' => 'manual', 'category' => 'economy', 'seats' => 5, 'note' => null],
            ['brand_slug' => 'dacia', 'model' => 'Logan', 'color' => 'Gris Urbain', 'fiscal_power' => 6, 'cylinders' => 4, 'fuel' => 'diesel', 'transmission' => 'manual', 'category' => 'economy', 'seats' => 5, 'note' => 'houssine'],
            ['brand_slug' => 'dacia', 'model' => 'Logan', 'color' => 'Gris Urbain', 'fiscal_power' => 6, 'cylinders' => 4, 'fuel' => 'diesel', 'transmission' => 'manual', 'category' => 'economy', 'seats' => 5, 'note' => null],
            ['brand_slug' => 'dacia', 'model' => 'Logan', 'color' => 'Gris Urbain', 'fiscal_power' => 6, 'cylinders' => 4, 'fuel' => 'diesel', 'transmission' => 'manual', 'category' => 'economy', 'seats' => 5, 'note' => null],
            ['brand_slug' => 'dacia', 'model' => 'Logan', 'color' => 'Gris Urbain', 'fiscal_power' => 6, 'cylinders' => 4, 'fuel' => 'diesel', 'transmission' => 'manual', 'category' => 'economy', 'seats' => 5, 'note' => null],
            ['brand_slug' => 'dacia', 'model' => 'Logan', 'color' => 'Gris Urbain', 'fiscal_power' => 6, 'cylinders' => 4, 'fuel' => 'gasoline', 'transmission' => 'automatic', 'category' => 'economy', 'seats' => 5, 'note' => null],
            ['brand_slug' => 'dacia', 'model' => 'Logan', 'color' => 'Gris Urbain', 'fiscal_power' => 6, 'cylinders' => 4, 'fuel' => 'gasoline', 'transmission' => 'automatic', 'category' => 'economy', 'seats' => 5, 'note' => null],
            ['brand_slug' => 'dacia', 'model' => 'Logan', 'color' => 'Blanc', 'fiscal_power' => 6, 'cylinders' => 4, 'fuel' => 'diesel', 'transmission' => 'manual', 'category' => 'economy', 'seats' => 5, 'note' => null],
            ['brand_slug' => 'dacia', 'model' => 'Logan', 'color' => 'Blanc', 'fiscal_power' => 6, 'cylinders' => 4, 'fuel' => 'diesel', 'transmission' => 'manual', 'category' => 'economy', 'seats' => 5, 'note' => null],
            ['brand_slug' => 'dacia', 'model' => 'Logan', 'color' => 'Gris Urbain', 'fiscal_power' => 6, 'cylinders' => 4, 'fuel' => 'diesel', 'transmission' => 'manual', 'category' => 'economy', 'seats' => 5, 'note' => null],
            ['brand_slug' => 'dacia', 'model' => 'Logan', 'color' => 'Noir', 'fiscal_power' => 6, 'cylinders' => 4, 'fuel' => 'diesel', 'transmission' => 'manual', 'category' => 'economy', 'seats' => 5, 'note' => null],
            ['brand_slug' => 'dacia', 'model' => 'Logan', 'color' => 'Gris Urbain', 'fiscal_power' => 6, 'cylinders' => 4, 'fuel' => 'diesel', 'transmission' => 'manual', 'category' => 'economy', 'seats' => 5, 'note' => null],
            ['brand_slug' => 'dacia', 'model' => 'Duster', 'color' => 'Gris', 'fiscal_power' => 6, 'cylinders' => 4, 'fuel' => 'diesel', 'transmission' => 'manual', 'category' => 'suv', 'seats' => 5, 'note' => null],
            ['brand_slug' => 'dacia', 'model' => 'Logan', 'color' => 'Gris Urbain', 'fiscal_power' => 6, 'cylinders' => 4, 'fuel' => 'diesel', 'transmission' => 'manual', 'category' => 'economy', 'seats' => 5, 'note' => null],
            ['brand_slug' => 'dacia', 'model' => 'Logan', 'color' => 'Gris Urbain', 'fiscal_power' => 6, 'cylinders' => 4, 'fuel' => 'diesel', 'transmission' => 'manual', 'category' => 'economy', 'seats' => 5, 'note' => null],
            ['brand_slug' => 'dacia', 'model' => 'Logan', 'color' => 'Gris Urbain', 'fiscal_power' => 6, 'cylinders' => 4, 'fuel' => 'diesel', 'transmission' => 'manual', 'category' => 'economy', 'seats' => 5, 'note' => null],
            ['brand_slug' => 'dacia', 'model' => 'Duster', 'color' => 'Gris', 'fiscal_power' => 6, 'cylinders' => 4, 'fuel' => 'diesel', 'transmission' => 'manual', 'category' => 'suv', 'seats' => 5, 'note' => null],
            ['brand_slug' => 'nissan', 'model' => 'Magnite', 'color' => 'Bleu', 'fiscal_power' => 7, 'cylinders' => 3, 'fuel' => 'gasoline', 'transmission' => 'manual', 'category' => 'suv', 'seats' => 5, 'note' => null],
            ['brand_slug' => 'dacia', 'model' => 'Logan', 'color' => 'Gris', 'fiscal_power' => 6, 'cylinders' => 4, 'fuel' => 'diesel', 'transmission' => 'manual', 'category' => 'economy', 'seats' => 5, 'note' => 'salek'],
            ['brand_slug' => 'renault', 'model' => 'Clio', 'color' => 'Blanc', 'fiscal_power' => 6, 'cylinders' => 4, 'fuel' => 'gasoline', 'transmission' => 'automatic', 'category' => 'economy', 'seats' => 5, 'note' => null],
            ['brand_slug' => 'dacia', 'model' => 'Jogger', 'color' => 'Blanc', 'fiscal_power' => 6, 'cylinders' => 4, 'fuel' => 'diesel', 'transmission' => 'manual', 'category' => 'van', 'seats' => 7, 'note' => null],
            ['brand_slug' => 'dacia', 'model' => 'Sandero', 'color' => 'Blanc', 'fiscal_power' => 6, 'cylinders' => 4, 'fuel' => 'diesel', 'transmission' => 'manual', 'category' => 'economy', 'seats' => 5, 'note' => null],
            ['brand_slug' => 'renault', 'model' => 'Kardian', 'color' => 'Noir', 'fiscal_power' => 6, 'cylinders' => 4, 'fuel' => 'diesel', 'transmission' => 'manual', 'category' => 'suv', 'seats' => 5, 'note' => null],
            ['brand_slug' => 'toyota', 'model' => 'Limam', 'color' => 'Gris', 'fiscal_power' => 10, 'cylinders' => 4, 'fuel' => 'hybrid', 'transmission' => 'automatic', 'category' => 'van', 'seats' => 10, 'note' => null],
            ['brand_slug' => 'dacia', 'model' => 'Sandero', 'color' => 'Gris Urbain', 'fiscal_power' => 6, 'cylinders' => 4, 'fuel' => 'diesel', 'transmission' => 'manual', 'category' => 'economy', 'seats' => 5, 'note' => null],
            ['brand_slug' => 'dacia', 'model' => 'Sandero', 'color' => 'Gris Urbain', 'fiscal_power' => 6, 'cylinders' => 4, 'fuel' => 'diesel', 'transmission' => 'manual', 'category' => 'economy', 'seats' => 5, 'note' => null],
            ['brand_slug' => 'dacia', 'model' => 'Jogger', 'color' => 'Noir', 'fiscal_power' => 6, 'cylinders' => 4, 'fuel' => 'diesel', 'transmission' => 'manual', 'category' => 'van', 'seats' => 7, 'note' => null],
            ['brand_slug' => 'kia', 'model' => 'Seltos', 'color' => 'Bleu', 'fiscal_power' => 9, 'cylinders' => 4, 'fuel' => 'gasoline', 'transmission' => 'automatic', 'category' => 'suv', 'seats' => 5, 'note' => null],
            ['brand_slug' => 'fiat', 'model' => null, 'color' => 'Blanc', 'fiscal_power' => 8, 'cylinders' => 4, 'fuel' => 'diesel', 'transmission' => 'manual', 'category' => 'economy', 'seats' => 5, 'note' => 'Modèle non précisé dans la liste source.'],
            ['brand_slug' => 'renault', 'model' => 'Clio', 'color' => 'Noir', 'fiscal_power' => 6, 'cylinders' => 4, 'fuel' => 'diesel', 'transmission' => 'manual', 'category' => 'economy', 'seats' => 5, 'note' => null],
            ['brand_slug' => 'renault', 'model' => 'Clio', 'color' => 'Gris', 'fiscal_power' => 6, 'cylinders' => 4, 'fuel' => 'diesel', 'transmission' => 'manual', 'category' => 'economy', 'seats' => 5, 'note' => null],
        ];
    }

    private function normalizeColor(string $color): string
    {
        return Str::title(Str::lower($color));
    }
}
