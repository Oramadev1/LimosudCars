<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreVehicleRequest;
use App\Http\Requests\Admin\UpdateVehicleRequest;
use App\Http\Resources\VehicleResource;
use App\Models\FuelType;
use App\Models\TransmissionType;
use App\Models\Vehicle;
use App\Models\VehicleStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

/**
 * @group Vehicles
 *
 * Admin vehicle inventory endpoints. Requires the matching `vehicles.*` permission listed on each endpoint.
 */
class VehicleController extends Controller
{
    /**
     * List vehicles for the admin dashboard.
     *
     * Requires permission: `vehicles.view`.
     */
    public function index(): AnonymousResourceCollection
    {
        $vehicles = Vehicle::query()
            ->with(['brand', 'category', 'status', 'transmissionType', 'fuelType', 'photos'])
            ->latest()
            ->paginate(15);

        return VehicleResource::collection($vehicles);
    }

    /**
     * Store a new vehicle.
     *
     * Requires permission: `vehicles.create`.
     *
     * @bodyParam brand_id integer required Existing vehicle brand ID. Example: 1
     * @bodyParam category_id integer required Existing vehicle category ID. Example: 1
     * @bodyParam status_slug string required Vehicle status slug. Example: available
     * @bodyParam transmission_type_slug string required Transmission type slug. Example: automatic
     * @bodyParam fuel_type_slug string required Fuel type slug. Example: diesel
     * @bodyParam name string required Vehicle display name. Example: Dacia Sandero 2024
     * @bodyParam slug string required Unique vehicle slug. Example: dacia-sandero-2024
     * @bodyParam model string required Vehicle model. Example: Sandero
     * @bodyParam year integer required Production year. Example: 2024
     * @bodyParam plate_number string required Unique plate number. Example: 12345-A-10
     * @bodyParam mileage integer required Current mileage. Example: 12500
     * @bodyParam current_mileage_updated_at datetime optional Mileage update timestamp. Example: 2026-06-10 10:00:00
     * @bodyParam seats integer required Seat count. Example: 5
     * @bodyParam doors integer required Door count. Example: 5
     * @bodyParam daily_price number required Daily rental price. Example: 350
     * @bodyParam weekly_price number required Weekly rental price. Example: 2200
     * @bodyParam monthly_price number required Monthly rental price. Example: 8500
     * @bodyParam deposit_amount number required Required deposit. Example: 3000
     * @bodyParam description string optional Vehicle description. Example: Reliable economy vehicle.
     * @bodyParam is_featured boolean optional Whether the vehicle is featured. Example: true
     * @bodyParam is_active boolean optional Whether the vehicle is active. Example: true
     */
    public function store(StoreVehicleRequest $request): JsonResponse
    {
        $vehicle = DB::transaction(function () use ($request): Vehicle {
            $data = $this->prepareVehicleData($request->validated());

            return Vehicle::create($data);
        });

        return (new VehicleResource($vehicle->load(['brand', 'category', 'status', 'transmissionType', 'fuelType'])))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display a vehicle.
     *
     * Requires permission: `vehicles.view`.
     */
    public function show(Vehicle $vehicle): VehicleResource
    {
        return new VehicleResource($vehicle->load([
            'brand',
            'category',
            'status',
            'transmissionType',
            'fuelType',
            'photos',
            'documents.documentType',
        ]));
    }

    /**
     * Update a vehicle.
     *
     * Requires permission: `vehicles.update`.
     *
     * @bodyParam status_slug string optional Vehicle status slug. Example: maintenance
     * @bodyParam mileage integer optional Current mileage. Example: 13000
     * @bodyParam daily_price number optional Daily rental price. Example: 375
     * @bodyParam is_featured boolean optional Whether the vehicle is featured. Example: false
     */
    public function update(UpdateVehicleRequest $request, Vehicle $vehicle): VehicleResource
    {
        DB::transaction(function () use ($request, $vehicle): void {
            $vehicle->update($this->prepareVehicleData($request->validated()));
        });

        return new VehicleResource($vehicle->load([
            'brand',
            'category',
            'status',
            'transmissionType',
            'fuelType',
            'photos',
        ]));
    }

    /**
     * Soft delete a vehicle.
     *
     * Requires permission: `vehicles.delete`.
     */
    public function destroy(Vehicle $vehicle): Response
    {
        $vehicle->delete();

        return response()->noContent();
    }

    /**
     * Resolve lookup slugs to foreign keys without hardcoding lookup IDs.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function prepareVehicleData(array $data): array
    {
        if (array_key_exists('status_slug', $data)) {
            $data['status_id'] = VehicleStatus::where('slug', $data['status_slug'])->firstOrFail()->id;
            unset($data['status_slug']);
        }

        if (array_key_exists('transmission_type_slug', $data)) {
            $data['transmission_type_id'] = TransmissionType::where('slug', $data['transmission_type_slug'])->firstOrFail()->id;
            unset($data['transmission_type_slug']);
        }

        if (array_key_exists('fuel_type_slug', $data)) {
            $data['fuel_type_id'] = FuelType::where('slug', $data['fuel_type_slug'])->firstOrFail()->id;
            unset($data['fuel_type_slug']);
        }

        return $data;
    }
}
