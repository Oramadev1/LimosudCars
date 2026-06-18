<?php

namespace Database\Seeders;

use App\Models\VehicleStatus;
use Database\Seeders\Concerns\SeedsLookupTables;
use Illuminate\Database\Seeder;

class VehicleStatusSeeder extends Seeder
{
    use SeedsLookupTables;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedLookupValues(VehicleStatus::class, [
            'available' => 'Available',
            'reserved' => 'Reserved',
            'rented' => 'Rented',
            'maintenance' => 'Maintenance',
            'repair' => 'Repair',
            'out_of_service' => 'Out of Service',
            'sold' => 'Sold',
        ]);
    }
}
