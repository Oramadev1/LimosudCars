<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreVehicleCategoryRequest;
use App\Http\Requests\Admin\UpdateVehicleCategoryRequest;
use App\Http\Resources\VehicleCategoryResource;
use App\Models\VehicleCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

/**
 * @group Vehicle Categories
 *
 * Admin vehicle category endpoints. Requires the matching `vehicle_categories.*` permission listed on each endpoint.
 */
class VehicleCategoryController extends Controller
{
    /**
     * List vehicle categories.
     *
     * Requires permission: `vehicle_categories.view`.
     */
    public function index(): AnonymousResourceCollection
    {
        $categories = VehicleCategory::query()
            ->orderBy('name')
            ->paginate(15);

        return VehicleCategoryResource::collection($categories);
    }

    /**
     * Create a vehicle category.
     *
     * Requires permission: `vehicle_categories.create`.
     *
     * @bodyParam name string required Category name. Example: Economy
     * @bodyParam slug string required Unique category slug. Example: economy
     * @bodyParam description string optional Category description. Example: Affordable daily rental vehicles.
     * @bodyParam is_active boolean optional Whether the category is active. Example: true
     */
    public function store(StoreVehicleCategoryRequest $request): JsonResponse
    {
        $category = VehicleCategory::create($request->validated());

        return (new VehicleCategoryResource($category))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display a vehicle category.
     *
     * Requires permission: `vehicle_categories.view`.
     */
    public function show(VehicleCategory $category): VehicleCategoryResource
    {
        return new VehicleCategoryResource($category);
    }

    /**
     * Update a vehicle category.
     *
     * Requires permission: `vehicle_categories.update`.
     *
     * @bodyParam name string optional Category name. Example: SUV
     * @bodyParam slug string optional Unique category slug. Example: suv
     * @bodyParam description string optional Category description. Example: High clearance vehicles.
     * @bodyParam is_active boolean optional Whether the category is active. Example: true
     */
    public function update(UpdateVehicleCategoryRequest $request, VehicleCategory $category): VehicleCategoryResource
    {
        $category->update($request->validated());

        return new VehicleCategoryResource($category);
    }

    /**
     * Soft delete a vehicle category.
     *
     * Requires permission: `vehicle_categories.delete`.
     */
    public function destroy(VehicleCategory $category): Response
    {
        $category->delete();

        return response()->noContent();
    }
}
