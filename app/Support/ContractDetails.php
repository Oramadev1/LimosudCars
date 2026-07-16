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
            return self::finalize(self::merge(self::blank($reservation), $contract->details));
        }

        return self::finalize(self::blank($reservation));
    }

    /**
     * @param  array<string, mixed>  $details
     * @return array<string, mixed>
     */
    private static function finalize(array $details): array
    {
        $details['payment']['payment_method_slug'] = ContractPaymentMethods::normalize(
            $details['payment']['payment_method_slug'] ?? null,
        );
        $details['insurance']['type'] = ContractPaymentMethods::normalizeInsuranceType(
            $details['insurance']['type'] ?? null,
        );

        return $details;
    }

    /**
     * @return array<string, mixed>
     */
    public static function blank(Reservation $reservation): array
    {
        $customer = $reservation->customer;
        $vehicle = $reservation->vehicle;
        $settings = \App\Models\ContractSetting::current();
        $latestPayment = $reservation->payments
            ->sortByDesc(fn ($payment) => $payment->payment_date?->format('Y-m-d H:i:s') ?? '')
            ->first();

        return [
            'customer' => [
                'passport_or_cin' => $customer->passport_or_cin,
                'driving_license_number' => $customer->driving_license_number,
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
            'documents' => self::documentDefaults(),
            'condition' => [
                'before' => self::conditionDefaults(),
                'after' => self::conditionDefaults(),
            ],
            'rental' => [
                'dropoff_datetime' => $reservation->end_datetime?->toIso8601String(),
                'total_days' => $reservation->total_days,
                'actual_return_date' => '',
                'actual_return_time' => '',
                'extension' => '',
                'extension_total' => number_format((float) $reservation->total_price, 0, '.', ' '),
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
                'payment_method_slug' => ContractPaymentMethods::normalize(
                    $latestPayment?->paymentMethod?->slug,
                ),
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
        $customerDetails = $details['customer'] ?? [];

        if (blank($customerDetails['passport_or_cin'] ?? null) && blank($customer->passport_or_cin)) {
            $missing[] = 'customer.passport_or_cin';
        }

        if (blank($customerDetails['address'] ?? null) && blank($customer->address)) {
            $missing[] = 'customer.address';
        }

        if (blank($customerDetails['driving_license_number'] ?? null) && blank($customer->driving_license_number)) {
            $missing[] = 'customer.driving_license_number';
        }

        if (blank($customerDetails['license_issued_at'] ?? null) && blank($customer->driving_license_issued_at)) {
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
                'passport_or_cin' => $customerData['passport_or_cin'] ?? null,
                'driving_license_number' => $customerData['driving_license_number'] ?? null,
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

        self::syncReservationRental($reservation, $details);
    }

    /**
     * @param  array<string, mixed>  $details
     */
    public static function syncReservationRental(Reservation $reservation, array $details): void
    {
        $rentalDetails = $details['rental'] ?? [];
        $baseDays = (int) ($rentalDetails['total_days'] ?? $reservation->total_days);
        $extensionDays = self::parseExtensionDays($rentalDetails['extension'] ?? '');
        $effectiveDays = max($baseDays, 1) + ($extensionDays >= 1 ? $extensionDays : 0);

        $dropoffIso = $rentalDetails['dropoff_datetime'] ?? null;
        $endAt = $dropoffIso
            ? Carbon::parse($dropoffIso)
            : ($reservation->end_datetime?->copy());

        if ($endAt && $extensionDays >= 1) {
            $endAt = $endAt->copy()->addDays($extensionDays);
        }

        $updates = [];

        if ($effectiveDays !== (int) $reservation->total_days) {
            $updates['total_days'] = $effectiveDays;
        }

        if ($endAt && ! $reservation->end_datetime?->equalTo($endAt)) {
            $updates['end_datetime'] = $endAt;
        }

        $extensionTotal = self::parseFormattedAmount($rentalDetails['extension_total'] ?? '');
        if ($extensionTotal > 0 && abs($extensionTotal - (float) $reservation->total_price) > 0.009) {
            $updates['total_price'] = round($extensionTotal, 2);
        }

        if ($updates !== []) {
            $reservation->update($updates);
        }
    }

    private static function parseExtensionDays(mixed $value): int
    {
        if ($value === null || trim((string) $value) === '') {
            return 0;
        }

        $digits = preg_replace('/[^\d]/', '', (string) $value);

        return $digits !== '' ? max(0, (int) $digits) : 0;
    }

    private static function parseFormattedAmount(mixed $value): float
    {
        if ($value === null || $value === '') {
            return 0.0;
        }

        $digits = preg_replace('/[^\d]/', '', (string) $value);

        return $digits !== '' ? (float) $digits : 0.0;
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
