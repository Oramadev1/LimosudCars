<?php

namespace Database\Seeders;

use App\Models\ReservationStatus;
use Database\Seeders\Concerns\SeedsLookupTables;
use Illuminate\Database\Seeder;

class ReservationStatusSeeder extends Seeder
{
    use SeedsLookupTables;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedLookupValues(ReservationStatus::class, [
            'pending' => 'Pending',
            'confirmed' => 'Confirmed',
            'in_progress' => 'In Progress',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
            'rejected' => 'Rejected',
        ]);
    }
}
