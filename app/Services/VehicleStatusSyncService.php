<?php

namespace App\Services;

use App\Models\Reservation;
use App\Models\ReservationStatus;
use App\Models\Vehicle;
use App\Models\VehicleAvailabilityHold;
use App\Models\VehicleStatus;
use Carbon\Carbon;

class VehicleStatusSyncService
{
    /**
     * Sync vehicle status from current calendar periods.
     * Skips vehicles in maintenance/repair (manual admin states).
     *
     * @return array{updated: int, skipped: int}
     */
    public function sync(?Carbon $at = null): array
    {
        $at ??= now();
        $updated = 0;
        $skipped = 0;

        $availableId = VehicleStatus::where('slug', 'available')->value('id');
        $reservedId = VehicleStatus::where('slug', 'reserved')->value('id');
        $rentedId = VehicleStatus::where('slug', 'rented')->value('id');

        if ($availableId === null || $reservedId === null || $rentedId === null) {
            return ['updated' => 0, 'skipped' => 0];
        }

        $inProgressIds = ReservationStatus::where('slug', 'in_progress')->pluck('id')->all();
        $confirmedIds = ReservationStatus::where('slug', 'confirmed')->pluck('id')->all();

        $vehicles = Vehicle::query()
            ->with('status')
            ->where('is_active', true)
            ->get();

        foreach ($vehicles as $vehicle) {
            $slug = $vehicle->status?->slug;

            if (in_array($slug, ['maintenance', 'repair'], true)) {
                $skipped++;

                continue;
            }

            $desiredId = $availableId;

            $inProgress = Reservation::query()
                ->where('vehicle_id', $vehicle->id)
                ->whereIn('status_id', $inProgressIds)
                ->where('start_datetime', '<=', $at)
                ->where('end_datetime', '>', $at)
                ->exists();

            if ($inProgress) {
                $desiredId = $rentedId;
            } else {
                $confirmedNow = Reservation::query()
                    ->where('vehicle_id', $vehicle->id)
                    ->whereIn('status_id', $confirmedIds)
                    ->where('start_datetime', '<=', $at)
                    ->where('end_datetime', '>', $at)
                    ->exists();

                $holdNow = VehicleAvailabilityHold::query()
                    ->where('vehicle_id', $vehicle->id)
                    ->where('starts_at', '<=', $at)
                    ->where('ends_at', '>', $at)
                    ->exists();

                if ($confirmedNow || $holdNow) {
                    $desiredId = $reservedId;
                }
            }

            if ((int) $vehicle->status_id !== (int) $desiredId) {
                // Only auto-manage available/reserved/rented; leave other statuses alone.
                if (! in_array($slug, ['available', 'reserved', 'rented'], true) && $slug !== null) {
                    $skipped++;

                    continue;
                }

                $vehicle->update(['status_id' => $desiredId]);
                $updated++;
            }
        }

        return ['updated' => $updated, 'skipped' => $skipped];
    }
}
