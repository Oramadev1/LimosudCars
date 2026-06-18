<?php

namespace Database\Seeders;

use App\Models\PaymentType;
use Database\Seeders\Concerns\SeedsLookupTables;
use Illuminate\Database\Seeder;

class PaymentTypeSeeder extends Seeder
{
    use SeedsLookupTables;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedLookupValues(PaymentType::class, [
            'reservation_deposit' => 'Reservation Deposit',
            'rental_payment' => 'Rental Payment',
            'remaining_balance' => 'Remaining Balance',
            'security_deposit' => 'Security Deposit',
            'refund' => 'Refund',
        ]);
    }
}
