<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Database\Seeders\Concerns\SeedsLookupTables;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    use SeedsLookupTables;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedLookupValues(PaymentMethod::class, [
            'cash' => 'Cash',
            'bank_transfer' => 'Bank Transfer',
            'credit_card' => 'Credit Card',
            'debit_card' => 'Debit Card',
            'check' => 'Check',
            'online' => 'Online',
        ]);
    }
}
