<?php

namespace Database\Seeders;

use App\Models\TransmissionType;
use Database\Seeders\Concerns\SeedsLookupTables;
use Illuminate\Database\Seeder;

class TransmissionTypeSeeder extends Seeder
{
    use SeedsLookupTables;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedLookupValues(TransmissionType::class, [
            'manual' => 'Manual',
            'automatic' => 'Automatic',
        ]);
    }
}
