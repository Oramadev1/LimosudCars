<?php

namespace Database\Factories;

use App\Models\Location;
use App\Models\LocationType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Location>
 */
class LocationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->company().' Location';

        return [
            'location_type_id' => LocationType::where('slug', 'agency')->value('id'),
            'name' => $name,
            'slug' => Str::slug($name),
            'address' => fake()->address(),
            'delivery_fee' => fake()->randomFloat(2, 0, 250),
            'is_active' => true,
        ];
    }
}
