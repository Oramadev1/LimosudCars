<?php

namespace Database\Seeders;

use App\Models\VehicleCategory;
use Illuminate\Database\Seeder;

class VehicleCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Economy', 'slug' => 'economy', 'description' => 'Budget-friendly city cars for daily rentals.'],
            ['name' => 'Compact', 'slug' => 'compact', 'description' => 'Compact vehicles suitable for couples and small families.'],
            ['name' => 'SUV', 'slug' => 'suv', 'description' => 'SUV vehicles suitable for Dakhla roads and longer trips.'],
            ['name' => 'Luxury', 'slug' => 'luxury', 'description' => 'Premium vehicles for business and comfort.'],
            ['name' => 'Van', 'slug' => 'van', 'description' => 'Spacious vans for groups and airport transfers.'],
        ];

        foreach ($categories as $category) {
            VehicleCategory::updateOrCreate(
                ['slug' => $category['slug']],
                [
                    'name' => $category['name'],
                    'description' => $category['description'],
                    'is_active' => true,
                ]
            );
        }
    }
}
