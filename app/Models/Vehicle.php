<?php

namespace App\Models;

use Database\Factories\VehicleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    /** @use HasFactory<VehicleFactory> */
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'brand_id',
        'category_id',
        'status_id',
        'transmission_type_id',
        'fuel_type_id',
        'name',
        'slug',
        'model',
        'year',
        'plate_number',
        'vin',
        'color',
        'mileage',
        'fuel_level',
        'current_mileage_updated_at',
        'seats',
        'doors',
        'daily_price',
        'weekly_price',
        'monthly_price',
        'deposit_amount',
        'description',
        'is_featured',
        'homepage_rank',
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
            'year' => 'integer',
            'mileage' => 'integer',
            'current_mileage_updated_at' => 'datetime',
            'seats' => 'integer',
            'doors' => 'integer',
            'daily_price' => 'decimal:2',
            'weekly_price' => 'decimal:2',
            'monthly_price' => 'decimal:2',
            'deposit_amount' => 'decimal:2',
            'is_featured' => 'boolean',
            'homepage_rank' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * @return HasMany<VehicleAvailabilityHold, $this>
     */
    public function availabilityHolds(): HasMany
    {
        return $this->hasMany(VehicleAvailabilityHold::class);
    }

    /**
     * @return BelongsTo<VehicleBrand, $this>
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(VehicleBrand::class, 'brand_id');
    }

    /**
     * @return BelongsTo<VehicleCategory, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(VehicleCategory::class, 'category_id');
    }

    /**
     * @return BelongsTo<VehicleStatus, $this>
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(VehicleStatus::class, 'status_id');
    }

    /**
     * @return BelongsTo<TransmissionType, $this>
     */
    public function transmissionType(): BelongsTo
    {
        return $this->belongsTo(TransmissionType::class, 'transmission_type_id');
    }

    /**
     * @return BelongsTo<FuelType, $this>
     */
    public function fuelType(): BelongsTo
    {
        return $this->belongsTo(FuelType::class, 'fuel_type_id');
    }

    /**
     * @return HasMany<VehiclePhoto, $this>
     */
    public function photos(): HasMany
    {
        return $this->hasMany(VehiclePhoto::class);
    }

    /**
     * @return HasMany<VehicleDocument, $this>
     */
    public function documents(): HasMany
    {
        return $this->hasMany(VehicleDocument::class);
    }

    /**
     * @return HasMany<Reservation, $this>
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function maintenances(): HasMany
    {
        return $this->hasMany(VehicleMaintenance::class);
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }

    public function alerts(): HasMany
    {
        return $this->hasMany(Alert::class);
    }
}
