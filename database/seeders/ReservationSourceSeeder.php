<?php

namespace Database\Seeders;

use App\Models\ReservationSource;
use Database\Seeders\Concerns\SeedsLookupTables;
use Illuminate\Database\Seeder;

class ReservationSourceSeeder extends Seeder
{
    use SeedsLookupTables;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedLookupValues(ReservationSource::class, [
            'website' => 'Website',
            'phone' => 'Phone',
            'whatsapp' => 'WhatsApp',
            'walk_in' => 'Walk In',
            'admin' => 'Admin',
            'admin_manual' => 'Admin Manual',
            'partner' => 'Partner',
        ]);
    }
}
