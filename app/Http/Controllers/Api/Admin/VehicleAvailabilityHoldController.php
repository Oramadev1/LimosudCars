<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreVehicleAvailabilityHoldRequest;
use App\Http\Requests\Admin\UpdateVehicleAvailabilityHoldRequest;
use App\Http\Resources\VehicleAvailabilityHoldResource;
use App\Models\Vehicle;
use App\Models\VehicleAvailabilityHold;
use App\Services\VehicleAvailabilityService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class VehicleAvailabilityHoldController extends Controller
{
    public function schedule(
        Request $request,
        Vehicle $vehicle,
        VehicleAvailabilityService $availabilityService
    ): JsonResponse {
        $data = $request->validate([
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after:from'],
        ]);

        $from = isset($data['from']) ? Carbon::parse($data['from']) : now()->subMonth();
        $to = isset($data['to']) ? Carbon::parse($data['to']) : now()->addYear();

        return response()->json(
            $availabilityService->vehicleSchedule($vehicle, $from, $to)
        );
    }

    public function index(Vehicle $vehicle): AnonymousResourceCollection
    {
        $holds = VehicleAvailabilityHold::query()
            ->where('vehicle_id', $vehicle->id)
            ->orderBy('starts_at')
            ->get();

        return VehicleAvailabilityHoldResource::collection($holds);
    }

    public function store(
        StoreVehicleAvailabilityHoldRequest $request,
        Vehicle $vehicle,
        VehicleAvailabilityService $availabilityService
    ): JsonResponse {
        $data = $request->validated();

        if (! $availabilityService->checkAvailability($vehicle, $data['starts_at'], $data['ends_at'])) {
            throw ValidationException::withMessages([
                'starts_at' => 'This vehicle is not available for the selected period.',
            ]);
        }

        $hold = DB::transaction(function () use ($data, $vehicle, $request): VehicleAvailabilityHold {
            return VehicleAvailabilityHold::create([
                ...$data,
                'vehicle_id' => $vehicle->id,
                'created_by' => $request->user()?->id,
            ]);
        });

        return (new VehicleAvailabilityHoldResource($hold))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function update(
        UpdateVehicleAvailabilityHoldRequest $request,
        Vehicle $vehicle,
        VehicleAvailabilityHold $hold,
        VehicleAvailabilityService $availabilityService
    ): VehicleAvailabilityHoldResource {
        if ($hold->vehicle_id !== $vehicle->id) {
            abort(Response::HTTP_NOT_FOUND);
        }

        $data = $request->validated();
        $startsAt = $data['starts_at'] ?? $hold->starts_at;
        $endsAt = $data['ends_at'] ?? $hold->ends_at;

        if (! $availabilityService->checkAvailability($vehicle, $startsAt, $endsAt, null, $hold->id)) {
            throw ValidationException::withMessages([
                'starts_at' => 'This vehicle is not available for the selected period.',
            ]);
        }

        $hold = DB::transaction(function () use ($hold, $data, $startsAt, $endsAt): VehicleAvailabilityHold {
            $hold->update([
                ...$data,
                'starts_at' => $startsAt,
                'ends_at' => $endsAt,
            ]);

            return $hold->fresh();
        });

        return new VehicleAvailabilityHoldResource($hold);
    }

    public function destroy(Vehicle $vehicle, VehicleAvailabilityHold $hold): Response
    {
        if ($hold->vehicle_id !== $vehicle->id) {
            abort(Response::HTTP_NOT_FOUND);
        }

        $hold->delete();

        return response()->noContent();
    }
}
