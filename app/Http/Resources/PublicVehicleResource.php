<?php

namespace App\Http\Resources;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Public website vehicle payload — omits internal fields (year, mileage, plate, lookup slugs).
 *
 * @mixin Vehicle
 */
class PublicVehicleResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'model' => $this->model,
            'seats' => $this->seats,
            'doors' => $this->doors,
            'daily_price' => $this->daily_price,
            'weekly_price' => $this->weekly_price,
            'monthly_price' => $this->monthly_price,
            'deposit_amount' => $this->deposit_amount,
            'description' => $this->description,
            'is_featured' => $this->is_featured,
            'is_active' => $this->is_active,
            'brand' => $this->whenLoaded('brand', fn (): array => [
                'id' => $this->brand->id,
                'name' => $this->brand->name,
                'image_path' => $this->brand->image_path,
            ]),
            'category' => $this->whenLoaded('category', fn (): array => [
                'id' => $this->category->id,
                'name' => $this->category->name,
            ]),
            'status' => $this->whenLoaded('status', fn (): array => [
                'id' => $this->status->id,
                'name' => $this->status->name,
                'slug' => $this->status->slug,
            ]),
            'transmission_type' => $this->whenLoaded('transmissionType', fn (): array => [
                'id' => $this->transmissionType->id,
                'name' => $this->transmissionType->name,
            ]),
            'fuel_type' => $this->whenLoaded('fuelType', fn (): array => [
                'id' => $this->fuelType->id,
                'name' => $this->fuelType->name,
            ]),
            'photos' => $this->whenLoaded('photos', fn (): array => $this->photos
                ->map(fn ($photo): array => [
                    'id' => $photo->id,
                    'path' => $photo->path,
                    'alt_text' => $photo->alt_text,
                    'sort_order' => $photo->sort_order,
                    'is_primary' => $photo->is_primary,
                ])
                ->values()
                ->all()),
        ];
    }
}
