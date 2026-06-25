<?php

namespace App\Models;

use Database\Factories\ReservationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends Model
{
    /** @use HasFactory<ReservationFactory> */
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'reservation_number',
        'customer_id',
        'vehicle_id',
        'source_id',
        'status_id',
        'payment_status_id',
        'pickup_location_id',
        'dropoff_location_id',
        'start_datetime',
        'end_datetime',
        'total_days',
        'price_per_day',
        'delivery_fee',
        'deposit_amount',
        'total_price',
        'customer_notes',
        'admin_notes',
        'created_by',
        'confirmed_at',
        'started_at',
        'completed_at',
        'cancelled_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'start_datetime' => 'datetime',
            'end_datetime' => 'datetime',
            'total_days' => 'integer',
            'price_per_day' => 'decimal:2',
            'delivery_fee' => 'decimal:2',
            'deposit_amount' => 'decimal:2',
            'total_price' => 'decimal:2',
            'confirmed_at' => 'datetime',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<Customer, $this>
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class)->withTrashed();
    }

    /**
     * @return BelongsTo<Vehicle, $this>
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class)->withTrashed();
    }

    /**
     * @return BelongsTo<ReservationSource, $this>
     */
    public function source(): BelongsTo
    {
        return $this->belongsTo(ReservationSource::class, 'source_id');
    }

    /**
     * @return BelongsTo<ReservationStatus, $this>
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(ReservationStatus::class, 'status_id');
    }

    /**
     * @return BelongsTo<PaymentStatus, $this>
     */
    public function paymentStatus(): BelongsTo
    {
        return $this->belongsTo(PaymentStatus::class, 'payment_status_id');
    }

    /**
     * @return BelongsTo<Location, $this>
     */
    public function pickupLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'pickup_location_id')->withTrashed();
    }

    /**
     * @return BelongsTo<Location, $this>
     */
    public function dropoffLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'dropoff_location_id')->withTrashed();
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * @return HasMany<Payment, $this>
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * @return HasOne<Contract, $this>
     */
    public function contract(): HasOne
    {
        return $this->hasOne(Contract::class);
    }
}
