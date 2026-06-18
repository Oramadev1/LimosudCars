<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class VehicleMaintenance extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'vehicle_id',
        'maintenance_type_id',
        'maintenance_date',
        'next_maintenance_date',
        'mileage',
        'cost',
        'garage_name',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'maintenance_date' => 'date',
            'next_maintenance_date' => 'date',
            'mileage' => 'integer',
            'cost' => 'decimal:2',
        ];
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function maintenanceType(): BelongsTo
    {
        return $this->belongsTo(MaintenanceType::class);
    }
}
