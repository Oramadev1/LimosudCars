<?php

namespace App\Http\Resources;

use App\Models\Alert;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Alert
 */
class AlertResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'vehicle' => $this->whenLoaded('vehicle', fn (): ?array => $this->vehicle ? [
                'id' => $this->vehicle->id,
                'name' => $this->vehicle->name,
                'slug' => $this->vehicle->slug,
                'plate_number' => $this->vehicle->plate_number,
            ] : null),
            'alert_type' => $this->whenLoaded('alertType', fn (): array => [
                'id' => $this->alertType->id,
                'name' => $this->alertType->name,
                'slug' => $this->alertType->slug,
            ]),
            'alert_status' => $this->whenLoaded('alertStatus', fn (): array => [
                'id' => $this->alertStatus->id,
                'name' => $this->alertStatus->name,
                'slug' => $this->alertStatus->slug,
            ]),
            'title' => $this->title,
            'message' => $this->message,
            'due_date' => $this->due_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
