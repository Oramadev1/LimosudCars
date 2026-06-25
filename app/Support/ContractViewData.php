<?php

namespace App\Support;

use App\Models\Reservation;
use Carbon\Carbon;

class ContractViewData
{
    /**
     * @return array<string, mixed>
     */
    public static function fromReservation(
        Reservation $reservation,
        string $contractNumber,
        float $paidAmount,
        float $remainingAmount,
        ?string $logoData,
    ): array {
        $company = config('limosud.company');
        $start = Carbon::parse($reservation->start_datetime);
        $end = Carbon::parse($reservation->end_datetime);
        $customer = $reservation->customer;
        $vehicle = $reservation->vehicle;

        $latestPayment = $reservation->payments
            ->sortByDesc(fn ($payment) => $payment->payment_date?->format('Y-m-d H:i:s') ?? '')
            ->first();

        $paymentMethodSlug = $latestPayment?->paymentMethod?->slug;

        $brandName = trim(($vehicle->brand?->name ?? '').' '.($vehicle->model ?? ''));
        if ($brandName === '') {
            $brandName = $vehicle->name;
        }

        $contractDigits = preg_replace('/\D+/', '', $contractNumber) ?: $contractNumber;
        $pickupLocation = $reservation->pickupLocation?->name ?? '—';
        $dropoffLocation = $reservation->dropoffLocation?->name ?? '—';
        $deductible = number_format((int) $company['insurance_deductible'], 0, '.', ' ');

        return [
            'contractNumber' => $contractNumber,
            'contractDisplayNumber' => str_pad(substr($contractDigits, -6), 6, '0', STR_PAD_LEFT),
            'reservation' => $reservation,
            'company' => $company,
            'labelsAr' => config('limosud.contract_labels_ar'),
            'legalAr' => str_replace(':deductible', $deductible, config('limosud.contract_legal_ar')),
            'legalFr' => str_replace(':deductible', $deductible, config('limosud.contract_legal_fr')),
            'logoData' => $logoData,
            'paidAmount' => $paidAmount,
            'remainingAmount' => $remainingAmount,
            'paymentMethodSlug' => $paymentMethodSlug,
            'vehicleBrand' => mb_strtoupper($brandName),
            'vehiclePlate' => mb_strtoupper((string) $vehicle->plate_number),
            'pickupLocation' => $pickupLocation,
            'dropoffLocation' => $dropoffLocation,
            'dropoffRepriseText' => trim($dropoffLocation.' '.$end->format('d/m/Y H:i')),
            'start' => [
                'day' => $start->format('d'),
                'month' => $start->format('m'),
                'year' => $start->format('y'),
                'hour' => $start->format('H:i'),
            ],
            'end' => [
                'day' => $end->format('d'),
                'month' => $end->format('m'),
                'year' => $end->format('y'),
                'hour' => $end->format('H:i'),
            ],
            'customerName' => $customer->full_name,
            'customerNationality' => $customer->nationality,
            'customerPhone' => $customer->phone,
            'customerEmail' => $customer->email,
            'customerPassportOrCin' => $customer->passport_or_cin,
            'customerLicense' => $customer->driving_license_number,
            'pricePerDay' => number_format((float) $reservation->price_per_day, 0, '.', ' '),
            'totalPrice' => number_format((float) $reservation->total_price, 0, '.', ' '),
            'paidAmountFormatted' => number_format($paidAmount, 0, '.', ' '),
            'remainingAmountFormatted' => number_format($remainingAmount, 0, '.', ' '),
            'deductibleFormatted' => number_format((int) $company['insurance_deductible'], 0, '.', ' '),
            'contractDate' => now()->format('d/m/Y'),
        ];
    }
}
