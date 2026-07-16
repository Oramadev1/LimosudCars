<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\PublicVehicleResource;
use App\Models\Vehicle;
use App\Services\VehicleAvailabilityService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * @group Public
 *
 * Public active vehicle browsing endpoints.
 */
class PublicVehicleController extends Controller
{
    /**
     * List active vehicles for the public website.
     *
     * Optional `start_datetime` + `end_datetime` return only cars free for that period.
     *
     * @unauthenticated
     *
     * @queryParam start_datetime string optional Rental start. Example: 2026-07-01 10:00:00
     * @queryParam end_datetime string optional Rental end. Example: 2026-07-05 10:00:00
     */
    public function index(Request $request, VehicleAvailabilityService $availabilityService): AnonymousResourceCollection
    {
        $data = $request->validate([
            'start_datetime' => ['nullable', 'date'],
            'end_datetime' => ['nullable', 'date', 'after:start_datetime', 'required_with:start_datetime'],
        ]);

        $query = Vehicle::query()
            ->with($this->relationships())
            ->where('is_active', true);

        if (! empty($data['start_datetime']) && ! empty($data['end_datetime'])) {
            $availableIds = $availabilityService->availableVehicleIds(
                $data['start_datetime'],
                $data['end_datetime']
            );

            $query->whereIn('id', $availableIds ?: [0]);
        }

        $vehicles = $query
            ->orderByRaw('homepage_rank IS NULL')
            ->orderBy('homepage_rank')
            ->latest()
            ->paginate(15);

        return PublicVehicleResource::collection($vehicles);
    }

    /**
     * Show an active vehicle by slug.
     *
     * @unauthenticated
     */
    public function show(string $slug): PublicVehicleResource
    {
        $vehicle = Vehicle::query()
            ->with($this->relationships())
            ->where('is_active', true)
            ->where('slug', $slug)
            ->firstOrFail();

        return new PublicVehicleResource($vehicle);
    }

    /**
     * Check availability for an active vehicle.
     *
     * @unauthenticated
     *
     * @queryParam start_datetime string required Start datetime. Example: 2026-07-01 10:00:00
     * @queryParam end_datetime string required End datetime. Example: 2026-07-05 10:00:00
     */
    public function availability(Request $request, Vehicle $vehicle, VehicleAvailabilityService $availabilityService): JsonResponse
    {
        abort_unless($vehicle->is_active, 404);

        $data = $request->validate([
            'start_datetime' => ['required', 'date'],
            'end_datetime' => ['required', 'date', 'after:start_datetime'],
        ]);

        return response()->json([
            'vehicle_id' => $vehicle->id,
            'available' => $availabilityService->checkAvailability(
                $vehicle->id,
                $data['start_datetime'],
                $data['end_datetime']
            ),
        ]);
    }

    /**
     * Return blocked rental periods for calendar date picking.
     *
     * @unauthenticated
     *
     * @queryParam from date optional Range start. Example: 2026-07-01
     * @queryParam to date optional Range end. Example: 2026-09-30
     */
    public function schedule(Request $request, Vehicle $vehicle, VehicleAvailabilityService $availabilityService): JsonResponse
    {
        abort_unless($vehicle->is_active, 404);

        $data = $request->validate([
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after:from'],
        ]);

        $from = isset($data['from']) ? Carbon::parse($data['from']) : now();
        $to = isset($data['to']) ? Carbon::parse($data['to']) : now()->addDays(120);

        return response()->json(
            $availabilityService->vehicleSchedule($vehicle->id, $from, $to)
        );
    }

    /**
     * @return array<int, string>
     */
    private function relationships(): array
    {
        return [
            'brand',
            'category',
            'status',
            'transmissionType',
            'fuelType',
            'photos',
        ];
    }
}
