<?php

namespace App\Services;

use App\Models\Location;
use App\Models\Vehicle;
use Carbon\Carbon;
use InvalidArgumentException;

class ReservationPricingService
{
    /**
     * Calculate reservation pricing from vehicle, locations, and datetimes.
     *
     * @return array{total_days: int, price_per_day: float, delivery_fee: float, deposit_amount: float, total_price: float}
     */
    public function calculate(Vehicle $vehicle, Location $pickupLocation, Location $dropoffLocation, mixed $startDatetime, mixed $endDatetime): array
    {
        $totalDays = $this->calculateTotalDays($startDatetime, $endDatetime);
        $pricePerDay = $this->pricePerDay($vehicle);
        $deliveryFee = (float) $pickupLocation->delivery_fee + (float) $dropoffLocation->delivery_fee;
        $depositAmount = (float) $vehicle->deposit_amount;

        return [
            'total_days' => $totalDays,
            'price_per_day' => round($pricePerDay, 2),
            'delivery_fee' => round($deliveryFee, 2),
            'deposit_amount' => round($depositAmount, 2),
            'total_price' => round(($totalDays * $pricePerDay) + $deliveryFee + $depositAmount, 2),
        ];
    }

    public function calculateTotalDays(mixed $startDatetime, mixed $endDatetime): int
    {
        $startAt = Carbon::parse($startDatetime);
        $endAt = Carbon::parse($endDatetime);

        if ($endAt->lessThanOrEqualTo($startAt)) {
            throw new InvalidArgumentException('The end datetime must be after the start datetime.');
        }

        return max(1, (int) ceil($startAt->diffInSeconds($endAt) / 86400));
    }

    private function pricePerDay(Vehicle $vehicle): float
    {
        return (float) $vehicle->daily_price;
    }
}
