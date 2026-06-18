<?php

namespace Database\Seeders;

use App\Models\VehicleBrand;
use Illuminate\Database\Seeder;

class VehicleBrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            ['name' => 'Dacia', 'slug' => 'dacia'],
            ['name' => 'Renault', 'slug' => 'renault'],
            ['name' => 'Hyundai', 'slug' => 'hyundai'],
            ['name' => 'Toyota', 'slug' => 'toyota'],
            ['name' => 'Volkswagen', 'slug' => 'volkswagen'],
            ['name' => 'Peugeot', 'slug' => 'peugeot'],
        ];

        foreach ($brands as $brand) {
            VehicleBrand::updateOrCreate(
                ['slug' => $brand['slug']],
                [
                    'name' => $brand['name'],
                    'is_active' => true,
                ]
            );
        }
    }
}
