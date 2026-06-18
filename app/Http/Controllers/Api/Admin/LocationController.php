<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreLocationRequest;
use App\Http\Requests\Admin\UpdateLocationRequest;
use App\Http\Resources\LocationResource;
use App\Models\Location;
use App\Models\LocationType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

/**
 * @group Locations
 *
 * Admin location endpoints. Requires the matching `locations.*` permission listed on each endpoint.
 */
class LocationController extends Controller
{
    /**
     * List locations for the admin dashboard.
     *
     * Requires permission: `locations.view`.
     */
    public function index(): AnonymousResourceCollection
    {
        $locations = Location::query()
            ->with('locationType')
            ->latest()
            ->paginate(15);

        return LocationResource::collection($locations);
    }

    /**
     * Store a new location.
     *
     * Requires permission: `locations.create`.
     *
     * @bodyParam location_type_slug string required Location type slug. Example: agency
     * @bodyParam name string required Location name. Example: Dakhla Agency
     * @bodyParam slug string required Unique location slug. Example: dakhla-agency
     * @bodyParam address string optional Location address. Example: Avenue Mohammed V, Dakhla
     * @bodyParam delivery_fee number optional Delivery fee. Example: 100
     * @bodyParam is_active boolean optional Whether public users can choose this location. Example: true
     */
    public function store(StoreLocationRequest $request): JsonResponse
    {
        $location = DB::transaction(function () use ($request): Location {
            return Location::create($this->prepareLocationData($request->validated()));
        });

        return (new LocationResource($location->load('locationType')))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display a location.
     *
     * Requires permission: `locations.view`.
     */
    public function show(Location $location): LocationResource
    {
        return new LocationResource($location->load('locationType'));
    }

    /**
     * Update a location.
     *
     * Requires permission: `locations.update`.
     *
     * @bodyParam name string optional Location name. Example: Dakhla Airport
     * @bodyParam delivery_fee number optional Delivery fee. Example: 150
     * @bodyParam is_active boolean optional Whether public users can choose this location. Example: true
     */
    public function update(UpdateLocationRequest $request, Location $location): LocationResource
    {
        DB::transaction(function () use ($request, $location): void {
            $location->update($this->prepareLocationData($request->validated()));
        });

        return new LocationResource($location->load('locationType'));
    }

    /**
     * Soft delete a location.
     *
     * Requires permission: `locations.delete`.
     */
    public function destroy(Location $location): Response
    {
        $location->delete();

        return response()->noContent();
    }

    /**
     * Resolve location type slugs to IDs without hardcoding lookup IDs.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function prepareLocationData(array $data): array
    {
        if (array_key_exists('location_type_slug', $data)) {
            $data['location_type_id'] = LocationType::where('slug', $data['location_type_slug'])->firstOrFail()->id;
            unset($data['location_type_slug']);
        }

        return $data;
    }
}
