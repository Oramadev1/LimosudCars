<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreVehiclePhotoRequest;
use App\Http\Requests\Admin\UpdateVehiclePhotoRequest;
use App\Http\Resources\VehiclePhotoResource;
use App\Models\Vehicle;
use App\Models\VehiclePhoto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * @group Vehicles
 *
 * Admin vehicle photo upload and management endpoints.
 */
class VehiclePhotoController extends Controller
{
    /**
     * Upload one or more photos for a vehicle.
     *
     * Requires permission: `vehicles.update`.
     *
     * @bodyParam photo file optional Single image file.
     * @bodyParam photos file[] optional Multiple image files.
     * @bodyParam alt_text string optional Alt text applied to uploaded photos. Example: Front view
     * @bodyParam is_primary boolean optional Mark the first uploaded photo as primary. Example: true
     */
    public function store(StoreVehiclePhotoRequest $request, Vehicle $vehicle): JsonResponse
    {
        $photos = DB::transaction(function () use ($request, $vehicle): array {
            $files = $request->file('photos') ?? array_filter([$request->file('photo')]);

            if ($files === []) {
                return [];
            }

            $existingCount = $vehicle->photos()->count();
            $markPrimary = $request->boolean('is_primary') || $existingCount === 0;
            $uploaded = [];

            foreach (array_values($files) as $index => $file) {
                $path = $file->store("vehicles/{$vehicle->id}/photos", 'public');
                $isPrimary = $markPrimary && $index === 0;

                if ($isPrimary) {
                    $vehicle->photos()->update(['is_primary' => false]);
                }

                $uploaded[] = $vehicle->photos()->create([
                    'path' => $path,
                    'alt_text' => $request->input('alt_text'),
                    'sort_order' => $existingCount + $index + 1,
                    'is_primary' => $isPrimary,
                ]);
            }

            return $uploaded;
        });

        return VehiclePhotoResource::collection(collect($photos))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Update vehicle photo metadata.
     *
     * Requires permission: `vehicles.update`.
     */
    public function update(UpdateVehiclePhotoRequest $request, VehiclePhoto $photo): VehiclePhotoResource
    {
        DB::transaction(function () use ($request, $photo): void {
            $data = $request->validated();

            if (($data['is_primary'] ?? false) === true) {
                VehiclePhoto::query()
                    ->where('vehicle_id', $photo->vehicle_id)
                    ->whereKeyNot($photo->id)
                    ->update(['is_primary' => false]);
            }

            $photo->update($data);
        });

        return new VehiclePhotoResource($photo->refresh());
    }

    /**
     * Delete a vehicle photo and its stored file.
     *
     * Requires permission: `vehicles.update`.
     */
    public function destroy(VehiclePhoto $photo): Response
    {
        DB::transaction(function () use ($photo): void {
            $vehicleId = $photo->vehicle_id;
            $wasPrimary = $photo->is_primary;

            Storage::disk('public')->delete($photo->path);
            $photo->delete();

            if ($wasPrimary) {
                $nextPrimary = VehiclePhoto::query()
                    ->where('vehicle_id', $vehicleId)
                    ->orderBy('sort_order')
                    ->first();

                if ($nextPrimary) {
                    $nextPrimary->update(['is_primary' => true]);
                }
            }
        });

        return response()->noContent();
    }
}
