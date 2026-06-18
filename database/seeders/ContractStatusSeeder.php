<?php

namespace Database\Seeders;

use App\Models\ContractStatus;
use Database\Seeders\Concerns\SeedsLookupTables;
use Illuminate\Database\Seeder;

class ContractStatusSeeder extends Seeder
{
    use SeedsLookupTables;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedLookupValues(ContractStatus::class, [
            'draft' => 'Draft',
            'generated' => 'Generated',
            'signed' => 'Signed',
            'cancelled' => 'Cancelled',
        ]);
    }
}
