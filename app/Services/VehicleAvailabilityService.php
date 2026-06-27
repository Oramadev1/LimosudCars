<?php

namespace App\Services;

use App\Models\Reservation;
use App\Models\ReservationStatus;
use App\Models\Vehicle;
use App\Models\VehicleStatus;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class VehicleAvailabilityService
{
    /**
     * Check whether a vehicle is available for a datetime range.
     */
    public function checkAvailability(Vehicle|int $vehicle, mixed $startDatetime, mixed $endDatetime, ?int $ignoreReservationId = null): bool
    {
        $vehicleModel = $this->resolveVehicle($vehicle);

        if ($vehicleModel === null || ! $this->vehicleIsRentable($vehicleModel)) {
            return false;
        }

        $startAt = Carbon::parse($startDatetime);
        $endAt = Carbon::parse($endDatetime);

        if ($endAt->lessThanOrEqualTo($startAt)) {
            return false;
        }

        return ! $this->hasOverlappingReservations($vehicleModel->id, $startAt, $endAt, $ignoreReservationId);
    }

    /**
     * Stop writes that would overlap active reservations or use an unavailable vehicle.
     *
     * @throws ValidationException
     */
    public function preventOverlappingActiveReservations(
        Vehicle|int $vehicle,
        mixed $startDatetime,
        mixed $endDatetime,
        ?int $ignoreReservationId = null
    ): void {
        $vehicleModel = $this->resolveVehicle($vehicle);

        if ($vehicleModel === null) {
            throw ValidationException::withMessages([
                'vehicle_id' => 'The selected vehicle could not be found.',
            ]);
        }

        if (! $vehicleModel->is_active) {
            throw ValidationException::withMessages([
                'vehicle_id' => 'The selected vehicle is inactive.',
            ]);
        }

        if (! $this->vehicleIsRentable($vehicleModel)) {
            throw ValidationException::withMessages([
                'vehicle_id' => 'The selected vehicle is not available for rental right now.',
            ]);
        }

        $startAt = Carbon::parse($startDatetime);
        $endAt = Carbon::parse($endDatetime);

        if ($endAt->lessThanOrEqualTo($startAt)) {
            throw ValidationException::withMessages([
                'end_datetime' => 'The end date must be after the start date.',
            ]);
        }

        if ($this->hasOverlappingReservations($vehicleModel->id, $startAt, $endAt, $ignoreReservationId)) {
            throw ValidationException::withMessages([
                'vehicle_id' => 'The selected vehicle is not available for the requested period.',
            ]);
        }
    }

    public function isVehicleRentable(Vehicle|int $vehicle): bool
    {
        $vehicleModel = $this->resolveVehicle($vehicle);

        return $vehicleModel !== null && $this->vehicleIsRentable($vehicleModel);
    }

    /**
     * @return array{
     *     vehicle_id: int,
     *     vehicle_rentable: bool,
     *     vehicle_status: string|null,
     *     blocked_periods: array<int, array<string, mixed>>
     * }
     */
    public function vehicleSchedule(
        Vehicle|int $vehicle,
        ?Carbon $from = null,
        ?Carbon $to = null,
        ?int $ignoreReservationId = null
    ): array {
        $vehicleModel = $this->resolveVehicle($vehicle);

        $from ??= now();
        $to ??= now()->addDays(90);

        return [
            'vehicle_id' => $vehicleModel?->id ?? (is_int($vehicle) ? $vehicle : 0),
            'vehicle_rentable' => $vehicleModel !== null && $this->vehicleIsRentable($vehicleModel),
            'vehicle_status' => $vehicleModel?->status?->slug,
            'blocked_periods' => $vehicleModel === null
                ? []
                : $this->getBlockedPeriods($vehicleModel, $from, $to, $ignoreReservationId),
        ];
    }

    /**
     * @return array<int, array{start_datetime: string, end_datetime: string, status: string|null, reservation_number: string}>
     */
    public function getBlockedPeriods(
        Vehicle|int $vehicle,
        Carbon $from,
        Carbon $to,
        ?int $ignoreReservationId = null
    ): array {
        $vehicleModel = $this->resolveVehicle($vehicle);

        if ($vehicleModel === null) {
            return [];
        }

        return Reservation::query()
            ->where('vehicle_id', $vehicleModel->id)
            ->whereIn('status_id', $this->blockingReservationStatusIds())
            ->when($ignoreReservationId, fn ($query) => $query->whereKeyNot($ignoreReservationId))
            ->where('start_datetime', '<', $to)
            ->where('end_datetime', '>', $from)
            ->with('status')
            ->orderBy('start_datetime')
            ->get()
            ->map(fn (Reservation $reservation): array => [
                'start_datetime' => $reservation->start_datetime->toDateTimeString(),
                'end_datetime' => $reservation->end_datetime->toDateTimeString(),
                'status' => $reservation->status?->slug,
                'reservation_number' => $reservation->reservation_number,
            ])
            ->all();
    }

    /**
     * @return array<int, array{start_datetime: string, end_datetime: string, status: string|null, reservation_number: string}>
     */
    public function getConflictingPeriods(
        Vehicle|int $vehicle,
        mixed $startDatetime,
        mixed $endDatetime,
        ?int $ignoreReservationId = null
    ): array {
        $startAt = Carbon::parse($startDatetime);
        $endAt = Carbon::parse($endDatetime);

        return array_values(array_filter(
            $this->getBlockedPeriods($vehicle, $startAt, $endAt, $ignoreReservationId),
            fn (array $period): bool => Carbon::parse($period['start_datetime'])->lt($endAt)
                && Carbon::parse($period['end_datetime'])->gt($startAt)
        ));
    }

    /**
     * @return array<int, array{start_datetime: string, end_datetime: string}>
     */
    public function suggestAvailablePeriods(
        Vehicle|int $vehicle,
        int $durationSeconds,
        ?Carbon $searchFrom = null,
        ?Carbon $searchTo = null,
        int $limit = 5,
        ?int $ignoreReservationId = null
    ): array {
        $vehicleModel = $this->resolveVehicle($vehicle);

        if ($vehicleModel === null || ! $this->vehicleIsRentable($vehicleModel)) {
            return [];
        }

        $searchFrom ??= now();
        $searchTo ??= now()->addDays(90);
        $durationSeconds = max($durationSeconds, 3600);

        $blocked = Reservation::query()
            ->where('vehicle_id', $vehicleModel->id)
            ->whereIn('status_id', $this->blockingReservationStatusIds())
            ->when($ignoreReservationId, fn ($query) => $query->whereKeyNot($ignoreReservationId))
            ->where('end_datetime', '>', $searchFrom)
            ->where('start_datetime', '<', $searchTo)
            ->orderBy('start_datetime')
            ->get(['start_datetime', 'end_datetime']);

        $windows = [];
        $cursor = $searchFrom->copy();

        foreach ($blocked as $reservation) {
            $blockStart = Carbon::parse($reservation->start_datetime);
            $blockEnd = Carbon::parse($reservation->end_datetime);

            if ($cursor->copy()->addSeconds($durationSeconds)->lte($blockStart)) {
                $windows[] = [
                    'start_datetime' => $cursor->toDateTimeString(),
                    'end_datetime' => $cursor->copy()->addSeconds($durationSeconds)->toDateTimeString(),
                ];

                if (count($windows) >= $limit) {
                    return $windows;
                }
            }

            if ($blockEnd->gt($cursor)) {
                $cursor = $blockEnd->copy();
            }
        }

        if ($cursor->copy()->addSeconds($durationSeconds)->lte($searchTo)) {
            $windows[] = [
                'start_datetime' => $cursor->toDateTimeString(),
                'end_datetime' => $cursor->copy()->addSeconds($durationSeconds)->toDateTimeString(),
            ];
        }

        return array_slice($windows, 0, $limit);
    }

    private function resolveVehicle(Vehicle|int $vehicle): ?Vehicle
    {
        if ($vehicle instanceof Vehicle) {
            return $vehicle->relationLoaded('status')
                ? $vehicle
                : $vehicle->load('status');
        }

        return Vehicle::query()->with('status')->find($vehicle);
    }

    private function vehicleIsRentable(Vehicle $vehicle): bool
    {
        if (! $vehicle->is_active) {
            return false;
        }

        $slug = $vehicle->status?->slug;

        if ($slug === null) {
            return true;
        }

        return in_array($slug, ['available', 'reserved', 'rented'], true);
    }

    private function hasOverlappingReservations(
        int $vehicleId,
        Carbon $startAt,
        Carbon $endAt,
        ?int $ignoreReservationId = null
    ): bool {
        return Reservation::query()
            ->where('vehicle_id', $vehicleId)
            ->whereIn('status_id', $this->blockingReservationStatusIds())
            ->when($ignoreReservationId, fn ($query) => $query->whereKeyNot($ignoreReservationId))
            ->where('start_datetime', '<', $endAt)
            ->where('end_datetime', '>', $startAt)
            ->exists();
    }

    /**
     * @return array<int, int>
     */
    private function blockingReservationStatusIds(): array
    {
        return ReservationStatus::whereIn('slug', ['pending', 'confirmed', 'in_progress'])
            ->pluck('id')
            ->all();
    }
}
