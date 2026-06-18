<?php

namespace Database\Seeders;

use App\Models\PaymentStatus;
use Database\Seeders\Concerns\SeedsLookupTables;
use Illuminate\Database\Seeder;

class PaymentStatusSeeder extends Seeder
{
    use SeedsLookupTables;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedLookupValues(PaymentStatus::class, [
            'unpaid' => 'Unpaid',
            'partial_paid' => 'Partial Paid',
            'paid' => 'Paid',
            'cancelled' => 'Cancelled',
            'failed' => 'Failed',
            'refunded' => 'Refunded',
        ]);
    }
}
