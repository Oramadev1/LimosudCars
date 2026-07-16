<?php

namespace App\Support;

use App\Models\ContractSetting;
use App\Models\Reservation;
use Carbon\Carbon;

class ContractViewData
{
    /**
     * @param  array<string, mixed>  $details
     * @return array<string, mixed>
     */
    public static function fromReservation(
        Reservation $reservation,
        string $contractNumber,
        float $paidAmount,
        float $remainingAmount,
        ?string $logoData,
        array $details = [],
        string $contractSeries = 'A',
    ): array {
        $settings = ContractSetting::current();
        $company = $settings->toCompanyArray();
        $details = ContractDetails::merge(ContractDetails::blank($reservation), $details);

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

        $start = Carbon::parse($reservation->start_datetime);
        $rentalDetails = $details['rental'] ?? [];
        $end = ! empty($rentalDetails['dropoff_datetime'])
            ? Carbon::parse($rentalDetails['dropoff_datetime'])
            : Carbon::parse($reservation->end_datetime);
        $rentalDurationDays = (int) ($rentalDetails['total_days'] ?? $reservation->total_days);
        $customer = $reservation->customer;
        $vehicle = $reservation->vehicle;

        $latestPayment = $reservation->payments
            ->sortByDesc(fn ($payment) => $payment->payment_date?->format('Y-m-d H:i:s') ?? '')
            ->first();

        $paymentMethodSlug = ContractPaymentMethods::normalize(
            $details['payment']['payment_method_slug'] ?? $latestPayment?->paymentMethod?->slug,
        );
        $brandName = trim(($vehicle->brand?->name ?? '').' '.($vehicle->model ?? ''));
        if ($brandName === '') {
            $brandName = $vehicle->name;
        }

        $contractDigits = preg_replace('/\D+/', '', $contractNumber) ?: $contractNumber;
        $deductible = (int) ($details['insurance']['deductible'] ?? $company['insurance_deductible']);
        $deductibleFormatted = number_format($deductible, 0, '.', ' ');

        $paymentExtras = $details['payment'];
        $baseTotal = (float) $reservation->total_price;
        $afterExtension = self::parseFormattedAmount($rentalDetails['extension_total'] ?? '') ?: $baseTotal;
        $grandTotal = $afterExtension
            + (float) ($paymentExtras['additional_fees'] ?? 0)
            + (float) ($paymentExtras['late_return_fees'] ?? 0)
            + (float) ($paymentExtras['fuel_charges'] ?? 0)
            + (float) ($paymentExtras['cleaning_charges'] ?? 0)
            + (float) ($paymentExtras['damage_charges'] ?? 0)
            + (float) ($paymentExtras['tax'] ?? 0)
            - (float) ($paymentExtras['discount'] ?? 0);

        $remainingBalance = max(0, $grandTotal - $paidAmount);

        $legalAr = str_replace(':deductible', $deductibleFormatted, $settings->terms_ar ?? config('limosud.contract_legal_ar'));
        $legalFr = str_replace(':deductible', $deductibleFormatted, $settings->terms_fr ?? config('limosud.contract_legal_fr'));

        $customerDetails = $details['customer'];
        $vehicleDetails = $details['vehicle'];
        $additionalDriver = $details['additional_driver'];
        $mileage = $vehicleDetails['mileage'] ?? $vehicle->mileage;

        return [
            'contractNumber' => $contractNumber,
            'contractDisplayNumber' => str_pad(substr($contractDigits, -6), 6, '0', STR_PAD_LEFT),
            'contractSeries' => $contractSeries,
            'reservation' => $reservation,
            'company' => $company,
            'labelsAr' => config('limosud.contract_labels_ar'),
            'legalAr' => $legalAr,
            'legalFr' => $legalFr,
            'logoData' => $logoData,
            'paidAmount' => $paidAmount,
            'remainingAmount' => $remainingBalance,
            'paymentMethodSlug' => $paymentMethodSlug,
            'vehicleBrand' => mb_strtoupper($brandName),
            'vehicleModel' => mb_strtoupper((string) $vehicle->model),
            'vehicleCategory' => $vehicle->category?->name ?? '—',
            'vehiclePlate' => mb_strtoupper((string) $vehicle->plate_number),
            'vehicleVin' => $vehicleDetails['vin'] ?? $vehicle->vin,
            'vehicleColor' => $vehicleDetails['color'] ?? $vehicle->color,
            'vehicleYear' => $vehicle->year,
            'vehicleMileage' => $mileage,
            'vehicleFuelLevel' => $vehicleDetails['fuel_level'] ?? $vehicle->fuel_level,
            'vehicleTransmission' => $vehicle->transmissionType?->name,
            'vehicleFuelType' => $vehicle->fuelType?->name,
            'pickupLocation' => $reservation->pickupLocation?->name ?? '—',
            'dropoffLocation' => $reservation->dropoffLocation?->name ?? '—',
            'dropoffRepriseText' => trim(($reservation->dropoffLocation?->name ?? '—').' '.$end->format('d/m/Y H:i')),
            'rentalDurationDays' => $rentalDurationDays,
            'start' => self::dateParts($start),
            'end' => self::dateParts($end),
            'actualReturnDate' => $details['rental']['actual_return_date'] ?? '',
            'actualReturnTime' => $details['rental']['actual_return_time'] ?? '',
            'customerName' => $customer->full_name,
            'customerAddress' => $customerDetails['address'] ?? $customer->address,
            'customerNationality' => $customer->nationality,
            'customerPhone' => $customer->phone,
            'customerEmail' => $customer->email,
            'customerPassportOrCin' => $customerDetails['passport_or_cin'] ?? $customer->passport_or_cin,
            'customerPassportOrCinIssuedAt' => self::displayDate($customerDetails['passport_or_cin_issued_at'] ?? $customer->passport_or_cin_issued_at),
            'customerLicense' => $customerDetails['driving_license_number'] ?? $customer->driving_license_number,
            'customerLicenseIssuedAt' => self::displayDate($customerDetails['license_issued_at'] ?? $customer->driving_license_issued_at),
            'customerLicenseExpiresAt' => self::displayDate($customerDetails['license_expires_at'] ?? $customer->driving_license_expires_at),
            'customerLicenseCountry' => $customerDetails['license_country'] ?? $customer->driving_license_country,
            'customerForeignAddress' => $customerDetails['foreign_address'] ?? $customer->foreign_address,
            'additionalDriver' => $additionalDriver,
            'documents' => $details['documents'],
            'extension' => $details['rental']['extension'] ?? '',
            'insuranceType' => ContractPaymentMethods::normalizeInsuranceType($details['insurance']['type'] ?? 'basic'),
            'leaveUrbanArea' => (bool) ($details['special_authorization']['leave_urban_area'] ?? false),
            'pricePerDay' => number_format((float) $reservation->price_per_day, 0, '.', ' '),
            'depositAmount' => number_format((float) $reservation->deposit_amount, 0, '.', ' '),
            'deliveryFee' => number_format((float) $reservation->delivery_fee, 0, '.', ' '),
            'discount' => number_format((float) ($paymentExtras['discount'] ?? 0), 0, '.', ' '),
            'additionalFees' => number_format((float) ($paymentExtras['additional_fees'] ?? 0), 0, '.', ' '),
            'lateReturnFees' => number_format((float) ($paymentExtras['late_return_fees'] ?? 0), 0, '.', ' '),
            'fuelCharges' => number_format((float) ($paymentExtras['fuel_charges'] ?? 0), 0, '.', ' '),
            'cleaningCharges' => number_format((float) ($paymentExtras['cleaning_charges'] ?? 0), 0, '.', ' '),
            'damageCharges' => number_format((float) ($paymentExtras['damage_charges'] ?? 0), 0, '.', ' '),
            'taxAmount' => number_format((float) ($paymentExtras['tax'] ?? 0), 0, '.', ' '),
            'totalPrice' => number_format($baseTotal, 0, '.', ' '),
            'totalBeforeExtension' => number_format($baseTotal, 0, '.', ' '),
            'extensionTotal' => $details['rental']['extension_total'] ?? number_format($baseTotal, 0, '.', ' '),
            'grandTotal' => number_format($grandTotal, 0, '.', ' '),
            'paidAmountFormatted' => number_format($paidAmount, 0, '.', ' '),
            'remainingAmountFormatted' => number_format($remainingBalance, 0, '.', ' '),
            'paymentStatus' => $reservation->paymentStatus?->name,
            'scheduledPaymentDate' => self::displayDate($paymentExtras['scheduled_payment_date'] ?? null),
            'deductibleFormatted' => $deductibleFormatted,
            'contractDate' => now()->format('d/m/Y'),
            'documentItems' => [
                ['key' => 'ww', 'label' => 'WW'],
                ['key' => 'registration_card', 'label' => 'C.GRISE'],
                ['key' => 'technical_inspection', 'label' => 'V.TECH'],
                ['key' => 'insurance', 'label' => 'ASSUR'],
                ['key' => 'green_card', 'label' => 'CARTE VERTE'],
                ['key' => 'authorization', 'label' => 'DECISION'],
                ['key' => 'vignette', 'label' => 'VIGNETT'],
                ['key' => 'rental_contract_copy', 'label' => 'CONTRAT'],
            ],
        ];
    }

    /**
     * @return array{mime: string, data: string}|null
     */
    public static function contractLogo(): ?array
    {
        $configured = (string) config('limosud.contract_logo', 'images/logo.png');
        $candidates = array_unique([
            public_path($configured),
            public_path('images/logo.png'),
            public_path('images/logo.jpg'),
            public_path('images/logo.jpeg'),
        ]);

        foreach ($candidates as $path) {
            if (! is_readable($path)) {
                continue;
            }

            return [
                'mime' => mime_content_type($path) ?: 'image/png',
                'data' => base64_encode((string) file_get_contents($path)),
            ];
        }

        return null;
    }

    /**
     * @return array{mime: string, data: string}|null
     */
    public static function vehicleConditionImage(): ?array
    {
        $path = public_path((string) config('limosud.vehicle_condition_image'));

        if (! is_readable($path)) {
            return null;
        }

        return [
            'mime' => mime_content_type($path) ?: 'image/jpeg',
            'data' => base64_encode((string) file_get_contents($path)),
        ];
    }

    /**
     * @return array{day: string, month: string, year: string, hour: string}
     */
    private static function dateParts(Carbon $date): array
    {
        return [
            'day' => $date->format('d'),
            'month' => $date->format('m'),
            'year' => $date->format('y'),
            'hour' => $date->format('H:i'),
        ];
    }

    private static function displayDate(mixed $value): string
    {
        if ($value === null || $value === '') {
            return '';
        }

        return Carbon::parse($value)->format('d/m/Y');
    }

    private static function parseFormattedAmount(mixed $value): float
    {
        if ($value === null || $value === '') {
            return 0;
        }

        $digits = preg_replace('/[^\d]/', '', (string) $value);

        return $digits !== '' ? (float) $digits : 0;
    }
}
