<?php

namespace Database\Seeders;

use App\Models\AlertType;
use Database\Seeders\Concerns\SeedsLookupTables;
use Illuminate\Database\Seeder;

class AlertTypeSeeder extends Seeder
{
    use SeedsLookupTables;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedLookupValues(AlertType::class, [
            'maintenance_due' => 'Maintenance Due',
            'document_expiry' => 'Document Expiry',
            'insurance_expiry' => 'Insurance Expiry',
            'payment_due' => 'Payment Due',
            'reservation_follow_up' => 'Reservation Follow Up',
            'vehicle_status' => 'Vehicle Status',
            'other' => 'Other',
        ]);
    }
}
