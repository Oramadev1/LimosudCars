<?php

namespace App\Http\Resources;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Vehicle
 */
class VehicleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'model' => $this->model,
            'plate_number' => $this->plate_number,
            'seats' => $this->seats,
            'doors' => $this->doors,
            'daily_price' => $this->daily_price,
            'weekly_price' => $this->weekly_price,
            'monthly_price' => $this->monthly_price,
            'deposit_amount' => $this->deposit_amount,
            'description' => $this->description,
            'is_featured' => $this->is_featured,
            'homepage_rank' => $this->homepage_rank,
            'is_active' => $this->is_active,
            'brand' => $this->whenLoaded('brand', fn (): array => [
                'id' => $this->brand->id,
                'name' => $this->brand->name,
                'slug' => $this->brand->slug,
                'image_path' => $this->brand->image_path,
            ]),
            'category' => $this->whenLoaded('category', fn (): array => [
                'id' => $this->category->id,
                'name' => $this->category->name,
                'slug' => $this->category->slug,
            ]),
            'status' => $this->whenLoaded('status', fn (): array => [
                'id' => $this->status->id,
                'name' => $this->status->name,
                'slug' => $this->status->slug,
            ]),
            'transmission_type' => $this->whenLoaded('transmissionType', fn (): array => [
                'id' => $this->transmissionType->id,
                'name' => $this->transmissionType->name,
                'slug' => $this->transmissionType->slug,
            ]),
            'fuel_type' => $this->whenLoaded('fuelType', fn (): array => [
                'id' => $this->fuelType->id,
                'name' => $this->fuelType->name,
                'slug' => $this->fuelType->slug,
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
            'documents' => $this->whenLoaded('documents', fn (): array => $this->documents
                ->map(fn ($document): array => [
                    'id' => $document->id,
                    'document_type' => [
                        'id' => $document->documentType?->id,
                        'name' => $document->documentType?->name,
                        'slug' => $document->documentType?->slug,
                    ],
                    'title' => $document->title,
                    'file_path' => $document->file_path,
                    'expires_at' => $document->expires_at,
                ])
                ->values()
                ->all()),
        ];
    }
}
