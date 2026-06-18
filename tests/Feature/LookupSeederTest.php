<?php

namespace Tests\Feature;

use Database\Seeders\AlertStatusSeeder;
use Database\Seeders\AlertTypeSeeder;
use Database\Seeders\ContractStatusSeeder;
use Database\Seeders\DocumentTypeSeeder;
use Database\Seeders\ExpenseCategorySeeder;
use Database\Seeders\FuelTypeSeeder;
use Database\Seeders\LocationTypeSeeder;
use Database\Seeders\MaintenanceTypeSeeder;
use Database\Seeders\PaymentMethodSeeder;
use Database\Seeders\PaymentStatusSeeder;
use Database\Seeders\PaymentTypeSeeder;
use Database\Seeders\ReservationSourceSeeder;
use Database\Seeders\ReservationStatusSeeder;
use Database\Seeders\TransmissionTypeSeeder;
use Database\Seeders\VehicleStatusSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LookupSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_lookup_seeders_create_expected_values(): void
    {
        $this->seed([
            VehicleStatusSeeder::class,
            TransmissionTypeSeeder::class,
            FuelTypeSeeder::class,
            ReservationStatusSeeder::class,
            PaymentStatusSeeder::class,
            PaymentMethodSeeder::class,
            PaymentTypeSeeder::class,
            ReservationSourceSeeder::class,
            LocationTypeSeeder::class,
            MaintenanceTypeSeeder::class,
            ExpenseCategorySeeder::class,
            AlertTypeSeeder::class,
            AlertStatusSeeder::class,
            DocumentTypeSeeder::class,
            ContractStatusSeeder::class,
        ]);

        foreach ($this->expectedLookups() as $table => $slugs) {
            foreach ($slugs as $slug) {
                $this->assertDatabaseHas($table, [
                    'slug' => $slug,
                ]);
            }
        }
    }

    /**
     * @return array<string, array<int, string>>
     */
    private function expectedLookups(): array
    {
        return [
            'vehicle_statuses' => ['available', 'reserved', 'rented', 'maintenance', 'repair', 'out_of_service', 'sold'],
            'transmission_types' => ['manual', 'automatic'],
            'fuel_types' => ['gasoline', 'diesel', 'hybrid', 'electric'],
            'reservation_statuses' => ['pending', 'confirmed', 'in_progress', 'completed', 'cancelled', 'rejected'],
            'payment_statuses' => ['unpaid', 'partial_paid', 'paid', 'cancelled', 'failed', 'refunded'],
            'payment_methods' => ['cash', 'bank_transfer', 'credit_card', 'debit_card', 'check', 'online'],
            'payment_types' => ['reservation_deposit', 'rental_payment', 'remaining_balance', 'security_deposit', 'refund'],
            'reservation_sources' => ['website', 'phone', 'whatsapp', 'walk_in', 'admin', 'partner'],
            'location_types' => ['agency', 'airport', 'hotel', 'city_center', 'custom'],
            'maintenance_types' => ['oil_change', 'tires', 'brakes', 'inspection', 'repair', 'cleaning', 'other'],
            'expense_categories' => ['maintenance', 'fuel', 'insurance', 'cleaning', 'parking', 'tolls', 'taxes', 'other'],
            'alert_types' => ['maintenance_due', 'document_expiry', 'insurance_expiry', 'payment_due', 'reservation_follow_up', 'vehicle_status', 'other'],
            'alert_statuses' => ['pending', 'seen', 'done', 'ignored'],
            'document_types' => ['passport', 'cin', 'driving_license', 'vehicle_registration', 'insurance', 'technical_inspection', 'contract', 'invoice', 'other'],
            'contract_statuses' => ['draft', 'generated', 'signed', 'cancelled'],
        ];
    }
}
