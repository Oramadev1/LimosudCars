<?php

namespace App\Http\Resources;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Location
 */
class LocationResource extends JsonResource
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
            'address' => $this->address,
            'delivery_fee' => $this->delivery_fee,
            'is_active' => $this->is_active,
            'location_type' => $this->whenLoaded('locationType', fn (): array => [
                'id' => $this->locationType->id,
                'name' => $this->locationType->name,
                'slug' => $this->locationType->slug,
            ]),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
