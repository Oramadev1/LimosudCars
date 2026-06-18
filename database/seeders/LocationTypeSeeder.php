<?php

namespace Database\Seeders;

use App\Models\LocationType;
use Database\Seeders\Concerns\SeedsLookupTables;
use Illuminate\Database\Seeder;

class LocationTypeSeeder extends Seeder
{
    use SeedsLookupTables;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedLookupValues(LocationType::class, [
            'agency' => 'Agency',
            'airport' => 'Airport',
            'hotel' => 'Hotel',
            'city_center' => 'City Center',
            'custom' => 'Custom',
        ]);
    }
}
