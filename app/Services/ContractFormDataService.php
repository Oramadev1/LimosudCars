<?php

namespace App\Services;

use App\Models\Contract;
use App\Models\Reservation;
use App\Support\ContractDetails;

class ContractFormDataService
{
    /**
     * @return array<string, mixed>
     */
    public function build(Reservation $reservation): array
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
            'status',
            'contract.status',
        ]);

        $contract = $reservation->contract;
        $details = ContractDetails::fromReservation($reservation, $contract);
        $paidAmount = $this->paidAmount($reservation);
        $vehicle = $reservation->vehicle;

        return [
            'reservation_id' => $reservation->id,
            'reservation_number' => $reservation->reservation_number,
            'reservation_status' => $reservation->status?->slug,
            'can_generate' => in_array($reservation->status?->slug, ['confirmed', 'in_progress', 'completed'], true),
            'existing_contract' => $contract ? [
                'id' => $contract->id,
                'contract_number' => $contract->contract_number,
                'contract_series' => $contract->contract_series,
                'status' => $contract->status?->only(['slug', 'name']),
            ] : null,
            'auto' => [
                'contract_series' => $contract?->contract_series ?? 'A',
                'generation_date' => now()->toDateString(),
                'customer' => [
                    'full_name' => $reservation->customer->full_name,
                    'nationality' => $reservation->customer->nationality,
                    'phone' => $reservation->customer->phone,
                    'email' => $reservation->customer->email,
                    'passport_or_cin' => $reservation->customer->passport_or_cin,
                    'driving_license_number' => $reservation->customer->driving_license_number,
                ],
                'vehicle' => [
                    'brand' => $vehicle->brand?->name,
                    'model' => $vehicle->model,
                    'name' => $vehicle->name,
                    'category' => $vehicle->category?->name,
                    'plate_number' => $vehicle->plate_number,
                    'year' => $vehicle->year,
                    'transmission' => $vehicle->transmissionType?->name,
                    'fuel_type' => $vehicle->fuelType?->name,
                    'daily_price' => (float) $reservation->price_per_day,
                    'weekly_price' => (float) $vehicle->weekly_price,
                    'monthly_price' => (float) $vehicle->monthly_price,
                ],
                'rental' => [
                    'pickup_location' => $reservation->pickupLocation?->name,
                    'dropoff_location' => $reservation->dropoffLocation?->name,
                    'pickup_datetime' => $reservation->start_datetime?->toIso8601String(),
                    'dropoff_datetime' => $reservation->end_datetime?->toIso8601String(),
                    'total_days' => $reservation->total_days,
                ],
                'payment' => [
                    'deposit_amount' => (float) $reservation->deposit_amount,
                    'delivery_fee' => (float) $reservation->delivery_fee,
                    'total_price' => (float) $reservation->total_price,
                    'amount_paid' => $paidAmount,
                    'remaining_balance' => max(0, (float) $reservation->total_price - $paidAmount),
                    'payment_status' => $reservation->paymentStatus?->slug,
                    'payment_method' => $reservation->payments
                        ->sortByDesc(fn ($payment) => $payment->payment_date?->format('Y-m-d H:i:s') ?? '')
                        ->first()
                        ?->paymentMethod
                        ?->slug,
                ],
            ],
            'details' => $details,
            'missing_fields' => ContractDetails::missingFields($reservation, $details),
        ];
    }

    private function paidAmount(Reservation $reservation): float
    {
        $paidStatusId = \App\Models\PaymentStatus::where('slug', 'paid')->value('id');

        return (float) $reservation->payments
            ->where('payment_status_id', $paidStatusId)
            ->sum('amount');
    }
}
