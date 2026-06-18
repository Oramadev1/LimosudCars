<?php

namespace Database\Seeders;

use App\Models\AlertStatus;
use Database\Seeders\Concerns\SeedsLookupTables;
use Illuminate\Database\Seeder;

class AlertStatusSeeder extends Seeder
{
    use SeedsLookupTables;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedLookupValues(AlertStatus::class, [
            'pending' => 'Pending',
            'seen' => 'Seen',
            'done' => 'Done',
            'ignored' => 'Ignored',
        ]);
    }
}
