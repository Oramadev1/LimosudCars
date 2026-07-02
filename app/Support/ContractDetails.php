<?php

namespace App\Support;

use App\Models\Contract;
use App\Models\Reservation;
use Carbon\Carbon;

class ContractDetails
{
    /**
     * @return array<string, mixed>
     */
    public static function equipmentDefaults(): array
    {
        return [
            'jack' => false,
            'wheel_wrench' => false,
            'spare_key' => false,
            'warning_triangle' => false,
            'fire_extinguisher' => false,
            'spare_wheel' => false,
            'first_aid_kit' => false,
            'gps' => false,
            'phone_charger' => false,
            'child_seat' => false,
            'other_accessories' => false,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function documentDefaults(): array
    {
        return [
            'ww' => false,
            'registration_card' => false,
            'technical_inspection' => false,
            'insurance' => false,
            'green_card' => false,
            'authorization' => false,
            'vignette' => false,
            'rental_contract_copy' => false,
            'other_documents' => false,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function conditionDefaults(): array
    {
        return [
            'front' => '',
            'rear' => '',
            'left' => '',
            'right' => '',
            'roof' => '',
            'windshield' => '',
            'notes' => '',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function fromReservation(Reservation $reservation, ?Contract $contract = null): array
    {
        $reservation->loadMissing([
            'customer',
            'vehicle.brand',
            'vehicle.category',
            'vehicle.transmissionType',
            'vehicle.fuelType',
            'pickupLocation',
            'dropoffLocation',
            'payments.paymentMethod',
            'payments.paymentStatus',
            'paymentStatus',
        ]);

        if ($contract?->details) {
            return self::merge(self::blank($reservation), $contract->details);
        }

        return self::blank($reservation);
    }

    /**
     * @return array<string, mixed>
     */
    public static function blank(Reservation $reservation): array
    {
        $customer = $reservation->customer;
        $vehicle = $reservation->vehicle;
        $settings = \App\Models\ContractSetting::current();

        return [
            'customer' => [
                'address' => $customer->address,
                'foreign_address' => $customer->foreign_address,
                'license_issued_at' => self::formatDate($customer->driving_license_issued_at),
                'license_expires_at' => self::formatDate($customer->driving_license_expires_at),
                'license_country' => $customer->driving_license_country,
                'passport_or_cin_issued_at' => self::formatDate($customer->passport_or_cin_issued_at),
            ],
            'vehicle' => [
                'vin' => $vehicle->vin,
                'color' => $vehicle->color,
                'fuel_level' => $vehicle->fuel_level,
                'mileage' => $vehicle->mileage,
            ],
            'additional_driver' => [
                'enabled' => false,
                'full_name' => '',
                'address' => '',
                'passport_or_cin' => '',
                'driving_license_number' => '',
                'license_issued_at' => '',
                'license_expires_at' => '',
                'nationality' => '',
                'phone' => '',
            ],
            'equipment' => self::equipmentDefaults(),
            'documents' => self::documentDefaults(),
            'condition' => [
                'before' => self::conditionDefaults(),
                'after' => self::conditionDefaults(),
            ],
            'rental' => [
                'actual_return_date' => '',
                'actual_return_time' => '',
                'extension' => '',
                'extension_total' => '',
            ],
            'payment' => [
                'discount' => 0,
                'additional_fees' => (float) $reservation->delivery_fee,
                'late_return_fees' => 0,
                'fuel_charges' => 0,
                'cleaning_charges' => 0,
                'damage_charges' => 0,
                'tax' => 0,
                'scheduled_payment_date' => '',
            ],
            'insurance' => [
                'type' => 'basic',
                'deductible' => $settings->insurance_deductible,
            ],
            'special_authorization' => [
                'leave_urban_area' => false,
            ],
            'persist_customer' => true,
            'persist_vehicle' => true,
        ];
    }

    /**
     * @param  array<string, mixed>  $base
     * @param  array<string, mixed>  $input
     * @return array<string, mixed>
     */
    public static function merge(array $base, array $input): array
    {
        return array_replace_recursive($base, $input);
    }

    /**
     * @param  array<string, mixed>  $details
     * @return list<string>
     */
    public static function missingFields(Reservation $reservation, array $details): array
    {
        $missing = [];
        $customer = $reservation->customer;

        if (! $customer->passport_or_cin) {
            $missing[] = 'customer.passport_or_cin';
        }

        if (blank($details['customer']['address'] ?? null) && blank($customer->address)) {
            $missing[] = 'customer.address';
        }

        if (blank($customer->driving_license_number)) {
            $missing[] = 'customer.driving_license_number';
        }

        if (blank($details['customer']['license_issued_at'] ?? null) && blank($customer->driving_license_issued_at)) {
            $missing[] = 'customer.license_issued_at';
        }

        return $missing;
    }

  /**
     * @param  array<string, mixed>  $details
     */
    public static function persistRelatedRecords(Reservation $reservation, array $details): void
    {
        $customerData = $details['customer'] ?? [];
        $vehicleData = $details['vehicle'] ?? [];

        if ($details['persist_customer'] ?? true) {
            $reservation->customer->fill(array_filter([
                'address' => $customerData['address'] ?? null,
                'foreign_address' => $customerData['foreign_address'] ?? null,
                'driving_license_issued_at' => self::parseDate($customerData['license_issued_at'] ?? null),
                'driving_license_expires_at' => self::parseDate($customerData['license_expires_at'] ?? null),
                'driving_license_country' => $customerData['license_country'] ?? null,
                'passport_or_cin_issued_at' => self::parseDate($customerData['passport_or_cin_issued_at'] ?? null),
            ], fn ($value) => $value !== null && $value !== ''))->save();
        }

        if ($details['persist_vehicle'] ?? true) {
            $reservation->vehicle->fill(array_filter([
                'vin' => $vehicleData['vin'] ?? null,
                'color' => $vehicleData['color'] ?? null,
                'fuel_level' => $vehicleData['fuel_level'] ?? null,
                'mileage' => isset($vehicleData['mileage']) ? (int) $vehicleData['mileage'] : null,
            ], fn ($value) => $value !== null && $value !== ''))->save();
        }
    }

    private static function formatDate(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        return Carbon::parse($value)->format('Y-m-d');
    }

    private static function parseDate(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        return Carbon::parse($value)->toDateString();
    }
}
