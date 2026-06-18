<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * System reference data required by the app (not business/demo data).
     * Runs with migrate — DatabaseSeeder stays login-only for production.
     *
     * @var array<string, array<string, string>>
     */
    private array $lookups = [
        'vehicle_statuses' => [
            'available' => 'Available',
            'reserved' => 'Reserved',
            'rented' => 'Rented',
            'maintenance' => 'Maintenance',
            'repair' => 'Repair',
            'out_of_service' => 'Out of Service',
            'sold' => 'Sold',
        ],
        'transmission_types' => [
            'manual' => 'Manual',
            'automatic' => 'Automatic',
        ],
        'fuel_types' => [
            'gasoline' => 'Gasoline',
            'diesel' => 'Diesel',
            'hybrid' => 'Hybrid',
            'electric' => 'Electric',
        ],
        'reservation_statuses' => [
            'pending' => 'Pending',
            'confirmed' => 'Confirmed',
            'in_progress' => 'In Progress',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
            'rejected' => 'Rejected',
        ],
        'payment_statuses' => [
            'unpaid' => 'Unpaid',
            'partial_paid' => 'Partial Paid',
            'paid' => 'Paid',
            'cancelled' => 'Cancelled',
            'failed' => 'Failed',
            'refunded' => 'Refunded',
        ],
        'payment_methods' => [
            'cash' => 'Cash',
            'bank_transfer' => 'Bank Transfer',
            'credit_card' => 'Credit Card',
            'debit_card' => 'Debit Card',
            'check' => 'Check',
            'online' => 'Online',
        ],
        'payment_types' => [
            'reservation_deposit' => 'Reservation Deposit',
            'rental_payment' => 'Rental Payment',
            'remaining_balance' => 'Remaining Balance',
            'security_deposit' => 'Security Deposit',
            'refund' => 'Refund',
        ],
        'reservation_sources' => [
            'website' => 'Website',
            'phone' => 'Phone',
            'whatsapp' => 'WhatsApp',
            'walk_in' => 'Walk In',
            'admin' => 'Admin',
            'admin_manual' => 'Admin Manual',
            'partner' => 'Partner',
        ],
        'location_types' => [
            'agency' => 'Agency',
            'airport' => 'Airport',
            'hotel' => 'Hotel',
            'city_center' => 'City Center',
            'custom' => 'Custom',
        ],
        'maintenance_types' => [
            'oil_change' => 'Oil Change',
            'tires' => 'Tires',
            'brakes' => 'Brakes',
            'inspection' => 'Inspection',
            'repair' => 'Repair',
            'cleaning' => 'Cleaning',
            'other' => 'Other',
        ],
        'expense_categories' => [
            'maintenance' => 'Maintenance',
            'fuel' => 'Fuel',
            'insurance' => 'Insurance',
            'cleaning' => 'Cleaning',
            'parking' => 'Parking',
            'tolls' => 'Tolls',
            'taxes' => 'Taxes',
            'other' => 'Other',
        ],
        'alert_types' => [
            'maintenance_due' => 'Maintenance Due',
            'document_expiry' => 'Document Expiry',
            'insurance_expiry' => 'Insurance Expiry',
            'payment_due' => 'Payment Due',
            'reservation_follow_up' => 'Reservation Follow Up',
            'vehicle_status' => 'Vehicle Status',
            'other' => 'Other',
        ],
        'alert_statuses' => [
            'pending' => 'Pending',
            'seen' => 'Seen',
            'done' => 'Done',
            'ignored' => 'Ignored',
        ],
        'document_types' => [
            'passport' => 'Passport',
            'cin' => 'CIN',
            'driving_license' => 'Driving License',
            'vehicle_registration' => 'Vehicle Registration',
            'insurance' => 'Insurance',
            'technical_inspection' => 'Technical Inspection',
            'contract' => 'Contract',
            'invoice' => 'Invoice',
            'other' => 'Other',
        ],
        'contract_statuses' => [
            'draft' => 'Draft',
            'generated' => 'Generated',
            'signed' => 'Signed',
            'cancelled' => 'Cancelled',
        ],
    ];

    public function up(): void
    {
        $now = now();

        foreach ($this->lookups as $table => $rows) {
            foreach ($rows as $slug => $name) {
                DB::table($table)->updateOrInsert(
                    ['slug' => $slug],
                    ['name' => $name, 'created_at' => $now, 'updated_at' => $now]
                );
            }
        }
    }

    public function down(): void
    {
        foreach (array_keys($this->lookups) as $table) {
            DB::table($table)->truncate();
        }
    }
};
