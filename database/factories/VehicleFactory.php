<?php

namespace Database\Factories;

use App\Models\FuelType;
use App\Models\TransmissionType;
use App\Models\Vehicle;
use App\Models\VehicleBrand;
use App\Models\VehicleCategory;
use App\Models\VehicleStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Vehicle>
 */
class VehicleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->bothify('Vehicle ###');

        return [
            'brand_id' => VehicleBrand::factory(),
            'category_id' => VehicleCategory::factory(),
            'status_id' => VehicleStatus::where('slug', 'available')->value('id'),
            'transmission_type_id' => TransmissionType::where('slug', 'automatic')->value('id'),
            'fuel_type_id' => FuelType::where('slug', 'diesel')->value('id'),
            'name' => $name,
            'slug' => Str::slug($name),
            'model' => fake()->bothify('Model ##'),
            'year' => fake()->numberBetween(2018, (int) date('Y')),
            'plate_number' => fake()->unique()->bothify('#####-?-##'),
            'mileage' => fake()->numberBetween(1000, 120000),
            'current_mileage_updated_at' => now(),
            'seats' => fake()->randomElement([4, 5, 7]),
            'doors' => fake()->randomElement([3, 4, 5]),
            'daily_price' => fake()->randomFloat(2, 250, 1200),
            'weekly_price' => fake()->randomFloat(2, 1500, 7000),
            'monthly_price' => fake()->randomFloat(2, 6000, 25000),
            'deposit_amount' => fake()->randomFloat(2, 1000, 10000),
            'description' => fake()->paragraph(),
            'is_featured' => false,
            'is_active' => true,
        ];
    }
}
