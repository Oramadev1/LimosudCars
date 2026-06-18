<?php

namespace App\Services;

use App\Models\Reservation;
use App\Models\ReservationStatus;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class VehicleAvailabilityService
{
    /**
     * Check whether a vehicle is available for a datetime range.
     */
    public function checkAvailability(Vehicle|int $vehicle, mixed $startDatetime, mixed $endDatetime, ?int $ignoreReservationId = null): bool
    {
        $vehicleId = $vehicle instanceof Vehicle ? $vehicle->id : $vehicle;
        $startAt = Carbon::parse($startDatetime);
        $endAt = Carbon::parse($endDatetime);

        if ($endAt->lessThanOrEqualTo($startAt)) {
            return false;
        }

        return ! Reservation::query()
            ->where('vehicle_id', $vehicleId)
            ->whereIn('status_id', $this->activeReservationStatusIds())
            ->when($ignoreReservationId, fn ($query) => $query->whereKeyNot($ignoreReservationId))
            ->where('start_datetime', '<', $endAt)
            ->where('end_datetime', '>', $startAt)
            ->exists();
    }

    /**
     * Stop writes that would overlap active reservations.
     *
     * @throws ValidationException
     */
    public function preventOverlappingActiveReservations(
        Vehicle|int $vehicle,
        mixed $startDatetime,
        mixed $endDatetime,
        ?int $ignoreReservationId = null
    ): void {
        if (! $this->checkAvailability($vehicle, $startDatetime, $endDatetime, $ignoreReservationId)) {
            throw ValidationException::withMessages([
                'vehicle_id' => 'The selected vehicle is not available for the requested period.',
            ]);
        }
    }

    /**
     * @return array<int, int>
     */
    private function activeReservationStatusIds(): array
    {
        return ReservationStatus::whereIn('slug', ['confirmed', 'in_progress'])
            ->pluck('id')
            ->all();
    }
}
