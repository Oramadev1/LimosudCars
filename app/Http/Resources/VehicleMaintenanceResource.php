<?php

namespace App\Http\Resources;

use App\Models\VehicleMaintenance;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin VehicleMaintenance
 */
class VehicleMaintenanceResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'vehicle' => $this->whenLoaded('vehicle', fn (): array => [
                'id' => $this->vehicle->id,
                'name' => $this->vehicle->name,
                'slug' => $this->vehicle->slug,
                'plate_number' => $this->vehicle->plate_number,
            ]),
            'maintenance_type' => $this->whenLoaded('maintenanceType', fn (): array => [
                'id' => $this->maintenanceType->id,
                'name' => $this->maintenanceType->name,
                'slug' => $this->maintenanceType->slug,
            ]),
            'maintenance_date' => $this->maintenance_date,
            'next_maintenance_date' => $this->next_maintenance_date,
            'mileage' => $this->mileage,
            'cost' => $this->cost,
            'garage_name' => $this->garage_name,
            'notes' => $this->notes,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
