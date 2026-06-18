<?php

namespace Database\Seeders;

use App\Models\ExpenseCategory;
use Database\Seeders\Concerns\SeedsLookupTables;
use Illuminate\Database\Seeder;

class ExpenseCategorySeeder extends Seeder
{
    use SeedsLookupTables;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedLookupValues(ExpenseCategory::class, [
            'maintenance' => 'Maintenance',
            'fuel' => 'Fuel',
            'insurance' => 'Insurance',
            'cleaning' => 'Cleaning',
            'parking' => 'Parking',
            'tolls' => 'Tolls',
            'taxes' => 'Taxes',
            'other' => 'Other',
        ]);
    }
}
