<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Alert extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'vehicle_id',
        'reservation_id',
        'alert_type_id',
        'alert_status_id',
        'title',
        'message',
        'due_date',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
        ];
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }

    public function alertType(): BelongsTo
    {
        return $this->belongsTo(AlertType::class);
    }

    public function alertStatus(): BelongsTo
    {
        return $this->belongsTo(AlertStatus::class);
    }
}
