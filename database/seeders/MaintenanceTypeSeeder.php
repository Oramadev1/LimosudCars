<?php

namespace Database\Seeders;

use App\Models\MaintenanceType;
use Database\Seeders\Concerns\SeedsLookupTables;
use Illuminate\Database\Seeder;

class MaintenanceTypeSeeder extends Seeder
{
    use SeedsLookupTables;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedLookupValues(MaintenanceType::class, [
            'oil_change' => 'Oil Change',
            'tires' => 'Tires',
            'brakes' => 'Brakes',
            'inspection' => 'Inspection',
            'repair' => 'Repair',
            'cleaning' => 'Cleaning',
            'other' => 'Other',
        ]);
    }
}
