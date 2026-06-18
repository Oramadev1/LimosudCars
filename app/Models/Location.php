<?php

namespace App\Models;

use Database\Factories\LocationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
    /** @use HasFactory<LocationFactory> */
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'location_type_id',
        'name',
        'slug',
        'address',
        'delivery_fee',
        'is_active',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'delivery_fee' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    /**
     * @return BelongsTo<LocationType, $this>
     */
    public function locationType(): BelongsTo
    {
        return $this->belongsTo(LocationType::class);
    }

    /**
     * @return HasMany<Reservation, $this>
     */
    public function pickupReservations(): HasMany
    {
        return $this->hasMany(Reservation::class, 'pickup_location_id');
    }

    /**
     * @return HasMany<Reservation, $this>
     */
    public function dropoffReservations(): HasMany
    {
        return $this->hasMany(Reservation::class, 'dropoff_location_id');
    }
}
