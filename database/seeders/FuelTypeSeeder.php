<?php

namespace Database\Seeders;

use App\Models\FuelType;
use Database\Seeders\Concerns\SeedsLookupTables;
use Illuminate\Database\Seeder;

class FuelTypeSeeder extends Seeder
{
    use SeedsLookupTables;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedLookupValues(FuelType::class, [
            'gasoline' => 'Gasoline',
            'diesel' => 'Diesel',
            'hybrid' => 'Hybrid',
            'electric' => 'Electric',
        ]);
    }
}
