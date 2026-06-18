<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreVehicleBrandRequest;
use App\Http\Requests\Admin\UpdateVehicleBrandRequest;
use App\Http\Requests\Admin\UploadVehicleBrandImageRequest;
use App\Http\Resources\VehicleBrandResource;
use App\Models\VehicleBrand;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * @group Vehicle Brands
 *
 * Admin vehicle brand endpoints. Requires the matching `vehicle_brands.*` permission listed on each endpoint.
 */
class VehicleBrandController extends Controller
{
    /**
     * List vehicle brands.
     *
     * Requires permission: `vehicle_brands.view`.
     */
    public function index(): AnonymousResourceCollection
    {
        $brands = VehicleBrand::query()
            ->orderBy('name')
            ->paginate(15);

        return VehicleBrandResource::collection($brands);
    }

    /**
     * Create a vehicle brand.
     *
     * Requires permission: `vehicle_brands.create`.
     *
     * @bodyParam name string required Brand name. Example: Dacia
     * @bodyParam slug string required Unique brand slug. Example: dacia
     * @bodyParam is_active boolean optional Whether the brand is active. Example: true
     */
    public function store(StoreVehicleBrandRequest $request): JsonResponse
    {
        $brand = VehicleBrand::create($request->validated());

        return (new VehicleBrandResource($brand))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display a vehicle brand.
     *
     * Requires permission: `vehicle_brands.view`.
     */
    public function show(VehicleBrand $brand): VehicleBrandResource
    {
        return new VehicleBrandResource($brand);
    }

    /**
     * Update a vehicle brand.
     *
     * Requires permission: `vehicle_brands.update`.
     *
     * @bodyParam name string optional Brand name. Example: Toyota
     * @bodyParam slug string optional Unique brand slug. Example: toyota
     * @bodyParam is_active boolean optional Whether the brand is active. Example: true
     */
    public function update(UpdateVehicleBrandRequest $request, VehicleBrand $brand): VehicleBrandResource
    {
        $brand->update($request->validated());

        return new VehicleBrandResource($brand);
    }

    /**
     * Soft delete a vehicle brand.
     *
     * Requires permission: `vehicle_brands.delete`.
     */
    public function destroy(VehicleBrand $brand): Response
    {
        DB::transaction(function () use ($brand): void {
            if ($brand->image_path) {
                Storage::disk('public')->delete($brand->image_path);
            }

            $brand->delete();
        });

        return response()->noContent();
    }

    /**
     * Upload or replace the brand logo/image.
     *
     * Requires permission: `vehicle_brands.update`.
     *
     * @bodyParam image file required Brand logo image.
     */
    public function storeImage(UploadVehicleBrandImageRequest $request, VehicleBrand $brand): VehicleBrandResource
    {
        DB::transaction(function () use ($request, $brand): void {
            if ($brand->image_path) {
                Storage::disk('public')->delete($brand->image_path);
            }

            $path = $request->file('image')->store("vehicle-brands/{$brand->id}", 'public');
            $brand->update(['image_path' => $path]);
        });

        return new VehicleBrandResource($brand->refresh());
    }

    /**
     * Remove the brand logo/image.
     *
     * Requires permission: `vehicle_brands.update`.
     */
    public function destroyImage(VehicleBrand $brand): VehicleBrandResource
    {
        DB::transaction(function () use ($brand): void {
            if ($brand->image_path) {
                Storage::disk('public')->delete($brand->image_path);
            }

            $brand->update(['image_path' => null]);
        });

        return new VehicleBrandResource($brand->refresh());
    }
}
