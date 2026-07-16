<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreReservationRequest;
use App\Http\Requests\Admin\UpdateReservationRequest;
use App\Http\Requests\ReservationAvailabilityRequest;
use App\Http\Resources\ReservationResource;
use App\Models\Location;
use App\Models\PaymentStatus;
use App\Models\Reservation;
use App\Models\ReservationSource;
use App\Models\ReservationStatus;
use App\Models\Vehicle;
use App\Services\AlertService;
use App\Services\PaymentService;
use App\Services\ReservationPricingService;
use App\Services\VehicleAvailabilityService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * @group Reservations
 *
 * Admin reservation endpoints. Requires the matching `reservations.*` permission listed on each endpoint.
 */
class ReservationController extends Controller
{
    /**
     * List reservations for the admin dashboard.
     *
     * Requires permission: `reservations.view`.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $perPage = min(max((int) $request->integer('per_page', 15), 1), 100);

        $reservations = Reservation::query()
            ->with($this->relationships())
            ->latest()
            ->paginate($perPage);

        return ReservationResource::collection($reservations);
    }

    /**
     * Store a manually created admin reservation.
     *
     * Requires permission: `reservations.create`.
     *
     * @bodyParam customer_id integer required Existing customer ID. Example: 1
     * @bodyParam vehicle_id integer required Existing vehicle ID. Example: 1
     * @bodyParam pickup_location_id integer required Pickup location ID. Example: 1
     * @bodyParam dropoff_location_id integer required Dropoff location ID. Example: 2
     * @bodyParam start_datetime datetime required Reservation start datetime. Example: 2026-07-01 10:00:00
     * @bodyParam end_datetime datetime required Reservation end datetime. Example: 2026-07-05 10:00:00
     * @bodyParam customer_notes string optional Customer-facing notes. Example: Airport pickup.
     * @bodyParam admin_notes string optional Internal admin notes. Example: Confirm license before pickup.
     */
    public function store(
        StoreReservationRequest $request,
        VehicleAvailabilityService $availabilityService,
        ReservationPricingService $pricingService,
        AlertService $alertService,
    ): JsonResponse {
        $reservation = DB::transaction(function () use ($request, $availabilityService, $pricingService): Reservation {
            $data = $request->validated();
            $vehicle = Vehicle::findOrFail($data['vehicle_id']);
            $pickupLocation = Location::findOrFail($data['pickup_location_id']);
            $dropoffLocation = Location::findOrFail($data['dropoff_location_id']);

            $availabilityService->preventOverlappingActiveReservations($vehicle, $data['start_datetime'], $data['end_datetime']);
            $pricing = $pricingService->calculate($vehicle, $pickupLocation, $dropoffLocation, $data['start_datetime'], $data['end_datetime']);

            return Reservation::create([
                'reservation_number' => $this->generateReservationNumber(),
                'customer_id' => $data['customer_id'],
                'vehicle_id' => $vehicle->id,
                'source_id' => $this->reservationSourceId('admin_manual'),
                'status_id' => $this->reservationStatusId('pending'),
                'payment_status_id' => $this->paymentStatusId('unpaid'),
                'pickup_location_id' => $pickupLocation->id,
                'dropoff_location_id' => $dropoffLocation->id,
                'start_datetime' => $data['start_datetime'],
                'end_datetime' => $data['end_datetime'],
                'customer_notes' => $data['customer_notes'] ?? null,
                'admin_notes' => $data['admin_notes'] ?? null,
                'created_by' => $request->user()?->id,
                ...$pricing,
            ]);
        });

        $alertService->createReservationFollowUpAlert($reservation);

        return (new ReservationResource($reservation->load($this->relationships())))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display a reservation.
     *
     * Requires permission: `reservations.view`.
     */
    public function show(Reservation $reservation): ReservationResource
    {
        return new ReservationResource($reservation->load($this->relationships()));
    }

    /**
     * Update a reservation and recalculate pricing when booking inputs change.
     *
     * Requires permission: `reservations.update`.
     *
     * @bodyParam vehicle_id integer optional Existing vehicle ID. Example: 1
     * @bodyParam pickup_location_id integer optional Pickup location ID. Example: 1
     * @bodyParam dropoff_location_id integer optional Dropoff location ID. Example: 2
     * @bodyParam start_datetime datetime optional Reservation start datetime. Example: 2026-07-02 10:00:00
     * @bodyParam end_datetime datetime optional Reservation end datetime. Example: 2026-07-06 10:00:00
     * @bodyParam admin_notes string optional Internal admin notes. Example: Updated pickup time.
     */
    public function update(
        UpdateReservationRequest $request,
        Reservation $reservation,
        VehicleAvailabilityService $availabilityService,
        ReservationPricingService $pricingService,
        PaymentService $paymentService
    ): ReservationResource {
        DB::transaction(function () use ($request, $reservation, $availabilityService, $pricingService, $paymentService): void {
            $reservation = Reservation::whereKey($reservation->id)->lockForUpdate()->firstOrFail();
            $this->ensureNotTerminalStatus($reservation);

            $data = $request->validated();
            $vehicle = Vehicle::findOrFail($data['vehicle_id'] ?? $reservation->vehicle_id);
            $pickupLocation = Location::findOrFail($data['pickup_location_id'] ?? $reservation->pickup_location_id);
            $dropoffLocation = Location::findOrFail($data['dropoff_location_id'] ?? $reservation->dropoff_location_id);
            $startDatetime = $data['start_datetime'] ?? $reservation->start_datetime;
            $endDatetime = $data['end_datetime'] ?? $reservation->end_datetime;

            $availabilityService->preventOverlappingActiveReservations($vehicle, $startDatetime, $endDatetime, $reservation->id);
            $pricing = $pricingService->calculate($vehicle, $pickupLocation, $dropoffLocation, $startDatetime, $endDatetime);

            $reservation->update([
                ...$data,
                'vehicle_id' => $vehicle->id,
                'pickup_location_id' => $pickupLocation->id,
                'dropoff_location_id' => $dropoffLocation->id,
                'start_datetime' => $startDatetime,
                'end_datetime' => $endDatetime,
                ...$pricing,
            ]);

            $paymentService->recalculateReservationPaymentStatus($reservation->fresh());
        });

        return new ReservationResource($reservation->load($this->relationships()));
    }

    /**
     * Soft delete a reservation.
     *
     * Requires permission: `reservations.delete`.
     */
    public function destroy(Reservation $reservation): Response
    {
        $reservation->delete();

        return response()->noContent();
    }

    /**
     * Confirm a pending reservation and reserve the vehicle.
     *
     * Requires permission: `reservations.confirm`.
     */
    public function confirm(Reservation $reservation, VehicleAvailabilityService $availabilityService, AlertService $alertService): ReservationResource
    {
        $reservation = DB::transaction(function () use ($reservation, $availabilityService): Reservation {
            $reservation = Reservation::whereKey($reservation->id)->lockForUpdate()->firstOrFail();
            $vehicle = Vehicle::whereKey($reservation->vehicle_id)->lockForUpdate()->firstOrFail();

            $this->ensureStatus($reservation, ['pending']);
            $availabilityService->preventOverlappingActiveReservations($vehicle, $reservation->start_datetime, $reservation->end_datetime, $reservation->id);

            $reservation->update([
                'status_id' => $this->reservationStatusId('confirmed'),
                'confirmed_at' => now(),
            ]);

            return $reservation;
        });

        $alertService->resolveReservationFollowUpAlert($reservation);

        return new ReservationResource($reservation->load($this->relationships()));
    }

    /**
     * Start a confirmed reservation.
     *
     * Requires permission: `reservations.start`.
     */
    public function start(Reservation $reservation): ReservationResource
    {
        $reservation = DB::transaction(function () use ($reservation): Reservation {
            $reservation = Reservation::whereKey($reservation->id)->lockForUpdate()->firstOrFail();

            $this->ensureStatus($reservation, ['confirmed']);

            $reservation->update([
                'status_id' => $this->reservationStatusId('in_progress'),
                'started_at' => now(),
            ]);

            return $reservation;
        });

        return new ReservationResource($reservation->load($this->relationships()));
    }

    /**
     * Complete an in-progress reservation.
     *
     * Requires permission: `reservations.complete`.
     */
    public function complete(Reservation $reservation): ReservationResource
    {
        $reservation = DB::transaction(function () use ($reservation): Reservation {
            $reservation = Reservation::whereKey($reservation->id)->lockForUpdate()->firstOrFail();

            $this->ensureStatus($reservation, ['in_progress']);

            $reservation->update([
                'status_id' => $this->reservationStatusId('completed'),
                'completed_at' => now(),
            ]);

            return $reservation;
        });

        return new ReservationResource($reservation->load($this->relationships()));
    }

    /**
     * Cancel a reservation and free reserved/rented vehicles.
     *
     * Requires permission: `reservations.cancel`.
     */
    public function cancel(Reservation $reservation, AlertService $alertService): ReservationResource
    {
        $wasPending = $reservation->loadMissing('status')->status?->slug === 'pending';

        $reservation = DB::transaction(function () use ($reservation): Reservation {
            $reservation = Reservation::whereKey($reservation->id)->lockForUpdate()->firstOrFail();

            $this->ensureNotStatus($reservation, ['completed', 'cancelled', 'rejected']);

            $reservation->update([
                'status_id' => $this->reservationStatusId('cancelled'),
                'cancelled_at' => now(),
            ]);

            return $reservation;
        });

        if ($wasPending) {
            $alertService->resolveReservationFollowUpAlert($reservation);
        }

        return new ReservationResource($reservation->load($this->relationships()));
    }

    /**
     * Reject a pending reservation request.
     *
     * Requires permission: `reservations.reject`.
     */
    public function reject(Reservation $reservation, AlertService $alertService): ReservationResource
    {
        $reservation = DB::transaction(function () use ($reservation): Reservation {
            $reservation = Reservation::whereKey($reservation->id)->lockForUpdate()->firstOrFail();

            $this->ensureStatus($reservation, ['pending']);

            $reservation->update([
                'status_id' => $this->reservationStatusId('rejected'),
            ]);

            return $reservation;
        });

        $alertService->resolveReservationFollowUpAlert($reservation);

        return new ReservationResource($reservation->load($this->relationships()));
    }

    /**
     * Reopen a cancelled or rejected reservation as pending.
     *
     * Requires permission: `reservations.update`.
     */
    public function reopen(Reservation $reservation, VehicleAvailabilityService $availabilityService, AlertService $alertService): ReservationResource
    {
        $reservation = DB::transaction(function () use ($reservation, $availabilityService): Reservation {
            $reservation = Reservation::whereKey($reservation->id)->lockForUpdate()->firstOrFail();
            $vehicle = Vehicle::whereKey($reservation->vehicle_id)->lockForUpdate()->firstOrFail();

            $this->ensureStatus($reservation, ['cancelled', 'rejected']);
            $availabilityService->preventOverlappingActiveReservations($vehicle, $reservation->start_datetime, $reservation->end_datetime, $reservation->id);

            $reservation->update([
                'status_id' => $this->reservationStatusId('pending'),
                'confirmed_at' => null,
                'started_at' => null,
                'completed_at' => null,
                'cancelled_at' => null,
            ]);

            return $reservation;
        });

        $alertService->createReservationFollowUpAlert($reservation);

        return new ReservationResource($reservation->load($this->relationships()));
    }

    /**
     * Check vehicle availability for admin workflows.
     *
     * Requires permission: `reservations.view`.
     *
     * @bodyParam vehicle_id integer required Vehicle ID to check. Example: 1
     * @bodyParam start_datetime datetime required Start datetime. Example: 2026-07-01 10:00:00
     * @bodyParam end_datetime datetime required End datetime. Example: 2026-07-05 10:00:00
     * @bodyParam ignore_reservation_id integer optional Reservation ID to ignore when updating. Example: 10
     */
    public function checkAvailability(ReservationAvailabilityRequest $request, VehicleAvailabilityService $availabilityService): JsonResponse
    {
        $data = $request->validated();
        $vehicleId = (int) $data['vehicle_id'];
        $startAt = Carbon::parse($data['start_datetime']);
        $endAt = Carbon::parse($data['end_datetime']);
        $ignoreReservationId = $data['ignore_reservation_id'] ?? null;
        $ignoreHoldId = $data['ignore_hold_id'] ?? null;
        $durationSeconds = max($startAt->diffInSeconds($endAt), 3600);

        $available = $availabilityService->checkAvailability(
            $vehicleId,
            $data['start_datetime'],
            $data['end_datetime'],
            $ignoreReservationId,
            $ignoreHoldId
        );

        $schedule = $availabilityService->vehicleSchedule(
            $vehicleId,
            now(),
            now()->addDays(90),
            $ignoreReservationId
        );

        return response()->json([
            'available' => $available,
            'vehicle_rentable' => $schedule['vehicle_rentable'],
            'vehicle_status' => $schedule['vehicle_status'],
            'blocked_periods' => $schedule['blocked_periods'],
            'conflicting_periods' => $available
                ? []
                : $availabilityService->getConflictingPeriods(
                    $vehicleId,
                    $data['start_datetime'],
                    $data['end_datetime'],
                    $ignoreReservationId,
                    $ignoreHoldId
                ),
            'suggested_periods' => $available
                ? []
                : $availabilityService->suggestAvailablePeriods(
                    $vehicleId,
                    $durationSeconds,
                    now(),
                    now()->addDays(90),
                    5,
                    $ignoreReservationId,
                    $ignoreHoldId
                ),
        ]);
    }

    /**
     * Return blocked reservation periods for a vehicle.
     *
     * Requires permission: `reservations.view`.
     *
     * @queryParam vehicle_id integer required Vehicle ID. Example: 1
     * @queryParam from date optional Range start. Example: 2026-07-01
     * @queryParam to date optional Range end. Example: 2026-09-30
     * @queryParam ignore_reservation_id integer optional Reservation ID to ignore when editing. Example: 10
     */
    public function vehicleAvailability(Request $request, VehicleAvailabilityService $availabilityService): JsonResponse
    {
        $data = $request->validate([
            'vehicle_id' => ['required', 'integer', 'exists:vehicles,id'],
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after:from'],
            'ignore_reservation_id' => ['nullable', 'integer', 'exists:reservations,id'],
        ]);

        $from = isset($data['from']) ? Carbon::parse($data['from']) : now()->subMonth();
        $to = isset($data['to']) ? Carbon::parse($data['to']) : now()->addYear();

        return response()->json(
            $availabilityService->vehicleSchedule(
                (int) $data['vehicle_id'],
                $from,
                $to,
                $data['ignore_reservation_id'] ?? null
            )
        );
    }

    /**
     * Return reservations in a lightweight calendar collection.
     *
     * Requires permission: `reservations.view`.
     *
     * @queryParam start date optional Include reservations ending on or after this date. Example: 2026-07-01
     * @queryParam end date optional Include reservations starting on or before this date. Example: 2026-07-31
     */
    public function calendar(Request $request): AnonymousResourceCollection
    {
        $reservations = Reservation::query()
            ->with($this->relationships())
            ->when($request->query('start'), fn ($query, $start) => $query->where('end_datetime', '>=', $start))
            ->when($request->query('end'), fn ($query, $end) => $query->where('start_datetime', '<=', $end))
            ->orderBy('start_datetime')
            ->get();

        return ReservationResource::collection($reservations);
    }

    /**
     * @return array<int, string>
     */
    private function relationships(): array
    {
        return [
            'customer',
            'vehicle.brand',
            'vehicle.category',
            'vehicle.status',
            'vehicle.transmissionType',
            'vehicle.fuelType',
            'source',
            'status',
            'paymentStatus',
            'pickupLocation.locationType',
            'dropoffLocation.locationType',
            'createdBy',
        ];
    }

    private function reservationSourceId(string $slug): int
    {
        return ReservationSource::where('slug', $slug)->firstOrFail()->id;
    }

    private function reservationStatusId(string $slug): int
    {
        return ReservationStatus::where('slug', $slug)->firstOrFail()->id;
    }

    private function paymentStatusId(string $slug): int
    {
        return PaymentStatus::where('slug', $slug)->firstOrFail()->id;
    }

    /**
     * @param  array<int, string>  $allowedStatuses
     *
     * @throws ValidationException
     */
    private function ensureStatus(Reservation $reservation, array $allowedStatuses): void
    {
        if (! in_array($reservation->status?->slug, $allowedStatuses, true)) {
            throw ValidationException::withMessages([
                'status' => 'The reservation status does not allow this action.',
            ]);
        }
    }

    /**
     * @param  array<int, string>  $blockedStatuses
     *
     * @throws ValidationException
     */
    private function ensureNotStatus(Reservation $reservation, array $blockedStatuses): void
    {
        if (in_array($reservation->status?->slug, $blockedStatuses, true)) {
            throw ValidationException::withMessages([
                'status' => 'The reservation status does not allow this action.',
            ]);
        }
    }

    /**
     * @throws ValidationException
     */
    private function ensureNotTerminalStatus(Reservation $reservation): void
    {
        if (in_array($reservation->status?->slug, ['completed', 'cancelled', 'rejected'], true)) {
            throw ValidationException::withMessages([
                'status' => 'Completed, cancelled, or rejected reservations cannot be edited.',
            ]);
        }
    }

    private function generateReservationNumber(): string
    {
        do {
            $number = 'RSV-'.now()->format('Ymd').'-'.random_int(1000, 9999);
        } while (Reservation::where('reservation_number', $number)->exists());

        return $number;
    }
}
