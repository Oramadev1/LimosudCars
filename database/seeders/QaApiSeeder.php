<?php

namespace Database\Seeders;

use Database\Seeders\Concerns\PreventsQaSeedingInProduction;
use App\Models\Alert;
use App\Models\Customer;
use App\Models\DocumentType;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\FuelType;
use App\Models\Location;
use App\Models\LocationType;
use App\Models\MaintenanceType;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\PaymentStatus;
use App\Models\PaymentType;
use App\Models\Reservation;
use App\Models\ReservationSource;
use App\Models\ReservationStatus;
use App\Models\TransmissionType;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleBrand;
use App\Models\VehicleCategory;
use App\Models\VehicleDocument;
use App\Models\VehicleMaintenance;
use App\Models\VehiclePhoto;
use App\Models\VehicleStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

/**
 * Local QA-only sample data for Postman/Newman. Never registered in DatabaseSeeder.
 */
class QaApiSeeder extends Seeder
{
    use PreventsQaSeedingInProduction;

    /**
     * Seed realistic QA data for Postman/Newman API testing.
     */
    public function run(): void
    {
        $this->preventQaSeedingInProduction();

        $brand = VehicleBrand::updateOrCreate(
            ['slug' => 'postman-brand'],
            ['name' => 'Postman Brand', 'is_active' => true]
        );

        $category = VehicleCategory::updateOrCreate(
            ['slug' => 'postman-category'],
            ['name' => 'Postman Category', 'description' => 'QA category for API testing.', 'is_active' => true]
        );

        $daciaBrand = VehicleBrand::where('slug', 'dacia')->firstOrFail();
        $economyCategory = VehicleCategory::where('slug', 'economy')->firstOrFail();
        $availableStatusId = VehicleStatus::where('slug', 'available')->value('id');
        $reservedStatusId = VehicleStatus::where('slug', 'reserved')->value('id');
        $manualTransmissionId = TransmissionType::where('slug', 'manual')->value('id');
        $dieselFuelId = FuelType::where('slug', 'diesel')->value('id');
        $agencyTypeId = LocationType::where('slug', 'agency')->value('id');
        $airportTypeId = LocationType::where('slug', 'airport')->value('id');

        $publicVehicle = Vehicle::updateOrCreate(
            ['slug' => 'dacia-sandero-2024'],
            [
                'brand_id' => $daciaBrand->id,
                'category_id' => $economyCategory->id,
                'status_id' => $availableStatusId,
                'transmission_type_id' => $manualTransmissionId,
                'fuel_type_id' => $dieselFuelId,
                'name' => 'Dacia Sandero 2024',
                'model' => 'Sandero',
                'year' => 2024,
                'plate_number' => '12345-A-10',
                'mileage' => 12500,
                'current_mileage_updated_at' => now()->subDay(),
                'seats' => 5,
                'doors' => 5,
                'daily_price' => 350,
                'weekly_price' => 2200,
                'monthly_price' => 8500,
                'deposit_amount' => 3000,
                'description' => 'Reliable economy vehicle for Dakhla rentals.',
                'is_featured' => true,
                'is_active' => true,
            ]
        );

        VehiclePhoto::updateOrCreate(
            ['vehicle_id' => $publicVehicle->id, 'path' => 'vehicles/photos/sandero.jpg'],
            [
                'alt_text' => 'Dacia Sandero front view',
                'sort_order' => 1,
                'is_primary' => true,
            ]
        );

        $adminVehicle = Vehicle::updateOrCreate(
            ['slug' => 'postman-test-vehicle'],
            [
                'brand_id' => $brand->id,
                'category_id' => $category->id,
                'status_id' => $availableStatusId,
                'transmission_type_id' => $manualTransmissionId,
                'fuel_type_id' => $dieselFuelId,
                'name' => 'Postman Test Vehicle',
                'model' => 'Sandero',
                'year' => 2024,
                'plate_number' => 'PM-100-TEST',
                'mileage' => 13000,
                'current_mileage_updated_at' => now()->subDay(),
                'seats' => 5,
                'doors' => 5,
                'daily_price' => 375,
                'weekly_price' => 2200,
                'monthly_price' => 8500,
                'deposit_amount' => 3000,
                'description' => 'Postman test vehicle.',
                'is_featured' => false,
                'is_active' => true,
            ]
        );

        VehiclePhoto::updateOrCreate(
            ['vehicle_id' => $adminVehicle->id, 'path' => 'vehicles/photos/postman.jpg'],
            [
                'alt_text' => 'Front view',
                'sort_order' => 1,
                'is_primary' => true,
            ]
        );

        VehicleDocument::updateOrCreate(
            [
                'vehicle_id' => $adminVehicle->id,
                'title' => 'Registration Card',
            ],
            [
                'document_type_id' => DocumentType::where('slug', 'vehicle_registration')->value('id'),
                'file_path' => 'vehicle-documents/'.$adminVehicle->id.'/registration.pdf',
                'expires_at' => '2027-12-31',
            ]
        );

        $lifecycleVehicle = $this->seedVehicle(
            'qa-lifecycle-vehicle',
            'QA Lifecycle Vehicle',
            'QA-LIFE-01',
            $brand->id,
            $category->id,
            $availableStatusId,
            $manualTransmissionId,
            $dieselFuelId
        );

        $cancelVehicle = $this->seedVehicle(
            'qa-cancel-vehicle',
            'QA Cancel Vehicle',
            'QA-CANCEL-01',
            $brand->id,
            $category->id,
            $reservedStatusId,
            $manualTransmissionId,
            $dieselFuelId
        );

        $rejectVehicle = $this->seedVehicle(
            'qa-reject-vehicle',
            'QA Reject Vehicle',
            'QA-REJECT-01',
            $brand->id,
            $category->id,
            $availableStatusId,
            $manualTransmissionId,
            $dieselFuelId
        );

        $contractVehicle = $this->seedVehicle(
            'qa-contract-vehicle',
            'QA Contract Vehicle',
            'QA-CONTRACT-01',
            $brand->id,
            $category->id,
            $availableStatusId,
            $manualTransmissionId,
            $dieselFuelId
        );

        $paymentVehicle = $this->seedVehicle(
            'qa-payment-vehicle',
            'QA Payment Vehicle',
            'QA-PAYMENT-01',
            $brand->id,
            $category->id,
            $availableStatusId,
            $manualTransmissionId,
            $dieselFuelId
        );

        $dakhlaAgency = Location::updateOrCreate(
            ['slug' => 'dakhla-agency'],
            [
                'location_type_id' => $agencyTypeId,
                'name' => 'Dakhla Agency',
                'address' => 'Dakhla Center, Morocco',
                'delivery_fee' => 0,
                'is_active' => true,
            ]
        );

        $dakhlaAirport = Location::updateOrCreate(
            ['slug' => 'dakhla-airport'],
            [
                'location_type_id' => $airportTypeId,
                'name' => 'Dakhla Airport',
                'address' => 'Dakhla Airport, Morocco',
                'delivery_fee' => 150,
                'is_active' => true,
            ]
        );

        $postmanLocation = Location::updateOrCreate(
            ['slug' => 'postman-location'],
            [
                'location_type_id' => $agencyTypeId,
                'name' => 'Postman Location',
                'address' => 'Postman Street, Dakhla',
                'delivery_fee' => 100,
                'is_active' => true,
            ]
        );

        $customer = Customer::updateOrCreate(
            ['email' => 'postman.customer@example.com'],
            [
                'full_name' => 'Postman Customer',
                'nationality' => 'Moroccan',
                'phone' => '+212611111111',
                'passport_or_cin' => 'PC123456',
                'driving_license_number' => 'PC-DL-001',
            ]
        );

        $adminUserId = User::where('email', env('ADMIN_EMAIL', 'admin@limosudcars.local'))->value('id');

        $websiteSourceId = ReservationSource::where('slug', 'website')->value('id');
        $adminManualSourceId = ReservationSource::where('slug', 'admin_manual')->value('id');
        $pendingStatusId = ReservationStatus::where('slug', 'pending')->value('id');
        $confirmedStatusId = ReservationStatus::where('slug', 'confirmed')->value('id');
        $unpaidStatusId = PaymentStatus::where('slug', 'unpaid')->value('id');
        $partialPaidStatusId = PaymentStatus::where('slug', 'partial_paid')->value('id');

        $adminReservation = Reservation::updateOrCreate(
            ['reservation_number' => 'RSV-QA-ADMIN-001'],
            [
                'customer_id' => $customer->id,
                'vehicle_id' => $adminVehicle->id,
                'source_id' => $adminManualSourceId,
                'status_id' => $pendingStatusId,
                'payment_status_id' => $unpaidStatusId,
                'pickup_location_id' => $postmanLocation->id,
                'dropoff_location_id' => $postmanLocation->id,
                'start_datetime' => now()->addMonths(2)->setTime(10, 0),
                'end_datetime' => now()->addMonths(2)->addDays(4)->setTime(10, 0),
                'total_days' => 4,
                'price_per_day' => 375,
                'delivery_fee' => 200,
                'deposit_amount' => 3000,
                'total_price' => 4700,
                'customer_notes' => 'Postman admin reservation.',
                'admin_notes' => 'QA test.',
                'created_by' => $adminUserId,
            ]
        );

        $lifecycleReservation = Reservation::updateOrCreate(
            ['reservation_number' => 'RSV-QA-LIFECYCLE-001'],
            [
                'customer_id' => $customer->id,
                'vehicle_id' => $lifecycleVehicle->id,
                'source_id' => $adminManualSourceId,
                'status_id' => $pendingStatusId,
                'payment_status_id' => $unpaidStatusId,
                'pickup_location_id' => $postmanLocation->id,
                'dropoff_location_id' => $postmanLocation->id,
                'start_datetime' => now()->addMonths(3)->setTime(10, 0),
                'end_datetime' => now()->addMonths(3)->addDays(3)->setTime(10, 0),
                'total_days' => 3,
                'price_per_day' => 375,
                'delivery_fee' => 200,
                'deposit_amount' => 3000,
                'total_price' => 4325,
                'admin_notes' => 'Lifecycle QA reservation.',
                'created_by' => $adminUserId,
            ]
        );

        $cancelReservation = Reservation::updateOrCreate(
            ['reservation_number' => 'RSV-QA-CANCEL-001'],
            [
                'customer_id' => $customer->id,
                'vehicle_id' => $cancelVehicle->id,
                'source_id' => $adminManualSourceId,
                'status_id' => $confirmedStatusId,
                'payment_status_id' => $unpaidStatusId,
                'pickup_location_id' => $postmanLocation->id,
                'dropoff_location_id' => $postmanLocation->id,
                'start_datetime' => now()->addMonths(4)->setTime(10, 0),
                'end_datetime' => now()->addMonths(4)->addDays(3)->setTime(10, 0),
                'total_days' => 3,
                'price_per_day' => 375,
                'delivery_fee' => 200,
                'deposit_amount' => 3000,
                'total_price' => 4325,
                'confirmed_at' => now()->subHour(),
                'created_by' => $adminUserId,
            ]
        );

        $rejectReservation = Reservation::updateOrCreate(
            ['reservation_number' => 'RSV-QA-REJECT-001'],
            [
                'customer_id' => $customer->id,
                'vehicle_id' => $rejectVehicle->id,
                'source_id' => $websiteSourceId,
                'status_id' => $pendingStatusId,
                'payment_status_id' => $unpaidStatusId,
                'pickup_location_id' => $dakhlaAgency->id,
                'dropoff_location_id' => $dakhlaAirport->id,
                'start_datetime' => now()->addMonths(5)->setTime(10, 0),
                'end_datetime' => now()->addMonths(5)->addDays(2)->setTime(10, 0),
                'total_days' => 2,
                'price_per_day' => 375,
                'delivery_fee' => 150,
                'deposit_amount' => 3000,
                'total_price' => 3450,
                'customer_notes' => 'Reject flow QA.',
                'created_by' => null,
            ]
        );

        $contractReservation = Reservation::updateOrCreate(
            ['reservation_number' => 'RSV-QA-CONTRACT-001'],
            [
                'customer_id' => $customer->id,
                'vehicle_id' => $contractVehicle->id,
                'source_id' => $adminManualSourceId,
                'status_id' => $confirmedStatusId,
                'payment_status_id' => $unpaidStatusId,
                'pickup_location_id' => $postmanLocation->id,
                'dropoff_location_id' => $postmanLocation->id,
                'start_datetime' => now()->addMonths(6)->setTime(10, 0),
                'end_datetime' => now()->addMonths(6)->addDays(4)->setTime(10, 0),
                'total_days' => 4,
                'price_per_day' => 375,
                'delivery_fee' => 200,
                'deposit_amount' => 3000,
                'total_price' => 4700,
                'confirmed_at' => now()->subHours(2),
                'created_by' => $adminUserId,
            ]
        );

        $paymentReservation = Reservation::updateOrCreate(
            ['reservation_number' => 'RSV-QA-PAYMENT-001'],
            [
                'customer_id' => $customer->id,
                'vehicle_id' => $paymentVehicle->id,
                'source_id' => $adminManualSourceId,
                'status_id' => $confirmedStatusId,
                'payment_status_id' => $partialPaidStatusId,
                'pickup_location_id' => $postmanLocation->id,
                'dropoff_location_id' => $postmanLocation->id,
                'start_datetime' => now()->addMonths(7)->setTime(10, 0),
                'end_datetime' => now()->addMonths(7)->addDays(4)->setTime(10, 0),
                'total_days' => 4,
                'price_per_day' => 375,
                'delivery_fee' => 200,
                'deposit_amount' => 3000,
                'total_price' => 4700,
                'confirmed_at' => now()->subHours(3),
                'created_by' => $adminUserId,
            ]
        );

        $seededPayment = Payment::updateOrCreate(
            ['reference' => 'PM-PAY-SEEDED-001'],
            [
                'reservation_id' => $paymentReservation->id,
                'payment_method_id' => PaymentMethod::where('slug', 'cash')->value('id'),
                'payment_type_id' => PaymentType::where('slug', 'rental_payment')->value('id'),
                'payment_status_id' => PaymentStatus::where('slug', 'paid')->value('id'),
                'amount' => 1000,
                'payment_date' => now()->subDay()->toDateString(),
                'paid_by_customer_name' => 'Postman Customer',
                'notes' => 'Seeded QA payment.',
                'created_by' => $adminUserId,
            ]
        );

        $maintenance = VehicleMaintenance::updateOrCreate(
            [
                'vehicle_id' => $adminVehicle->id,
                'maintenance_date' => now()->subDay()->toDateString(),
                'garage_name' => 'Postman Garage',
            ],
            [
                'maintenance_type_id' => MaintenanceType::where('slug', 'oil_change')->value('id'),
                'next_maintenance_date' => now()->addMonth()->toDateString(),
                'mileage' => 21000,
                'cost' => 450,
                'notes' => 'Postman maintenance.',
            ]
        );

        $expense = Expense::updateOrCreate(
            [
                'vehicle_id' => $adminVehicle->id,
                'description' => 'Postman expense seeded.',
            ],
            [
                'expense_category_id' => ExpenseCategory::where('slug', 'fuel')->value('id'),
                'amount' => 250,
                'expense_date' => now()->subDay()->toDateString(),
                'created_by' => $adminUserId,
            ]
        );

        $alert = Alert::updateOrCreate(
            [
                'vehicle_id' => $adminVehicle->id,
                'title' => 'Postman Maintenance Alert',
            ],
            [
                'alert_type_id' => \App\Models\AlertType::where('slug', 'maintenance_due')->value('id'),
                'alert_status_id' => \App\Models\AlertStatus::where('slug', 'pending')->value('id'),
                'message' => 'Postman alert message.',
                'due_date' => now()->addMonth()->toDateString(),
            ]
        );

        $alertDone = Alert::updateOrCreate(
            [
                'vehicle_id' => $adminVehicle->id,
                'title' => 'QA Alert Done Target',
            ],
            [
                'alert_type_id' => \App\Models\AlertType::where('slug', 'maintenance_due')->value('id'),
                'alert_status_id' => \App\Models\AlertStatus::where('slug', 'pending')->value('id'),
                'message' => 'Alert for done transition.',
                'due_date' => now()->addMonths(2)->toDateString(),
            ]
        );

        $alertIgnore = Alert::updateOrCreate(
            [
                'vehicle_id' => $adminVehicle->id,
                'title' => 'QA Alert Ignore Target',
            ],
            [
                'alert_type_id' => \App\Models\AlertType::where('slug', 'maintenance_due')->value('id'),
                'alert_status_id' => \App\Models\AlertStatus::where('slug', 'pending')->value('id'),
                'message' => 'Alert for ignore transition.',
                'due_date' => now()->addMonths(3)->toDateString(),
            ]
        );

        $manifest = [
            'public_vehicle_id' => $publicVehicle->id,
            'public_vehicle_slug' => $publicVehicle->slug,
            'pickup_location_id' => $dakhlaAgency->id,
            'dropoff_location_id' => $dakhlaAirport->id,
            'vehicle_brand_id' => $brand->id,
            'vehicle_category_id' => $category->id,
            'vehicle_id' => $adminVehicle->id,
            'customer_id' => $customer->id,
            'location_id' => $postmanLocation->id,
            'reservation_admin_id' => $adminReservation->id,
            'reservation_lifecycle_id' => $lifecycleReservation->id,
            'reservation_cancel_id' => $cancelReservation->id,
            'reservation_reject_id' => $rejectReservation->id,
            'reservation_contract_id' => $contractReservation->id,
            'reservation_payment_id' => $paymentReservation->id,
            'payment_id' => $seededPayment->id,
            'maintenance_id' => $maintenance->id,
            'expense_id' => $expense->id,
            'alert_id' => $alert->id,
            'alert_done_id' => $alertDone->id,
            'alert_ignore_id' => $alertIgnore->id,
        ];

        $manifestPath = storage_path('qa/qa-manifest.json');
        File::ensureDirectoryExists(dirname($manifestPath));
        File::put($manifestPath, json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

    private function seedVehicle(
        string $slug,
        string $name,
        string $plateNumber,
        int $brandId,
        int $categoryId,
        int $statusId,
        int $transmissionId,
        int $fuelId
    ): Vehicle {
        return Vehicle::updateOrCreate(
            ['slug' => $slug],
            [
                'brand_id' => $brandId,
                'category_id' => $categoryId,
                'status_id' => $statusId,
                'transmission_type_id' => $transmissionId,
                'fuel_type_id' => $fuelId,
                'name' => $name,
                'model' => 'Sandero',
                'year' => 2024,
                'plate_number' => $plateNumber,
                'mileage' => 15000,
                'current_mileage_updated_at' => now()->subDay(),
                'seats' => 5,
                'doors' => 5,
                'daily_price' => 375,
                'weekly_price' => 2200,
                'monthly_price' => 8500,
                'deposit_amount' => 3000,
                'description' => 'QA support vehicle.',
                'is_featured' => false,
                'is_active' => true,
            ]
        );
    }
}
