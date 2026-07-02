<?php

namespace App\Models;

use Database\Factories\CustomerFactory;
use App\Support\IdentityDocument;
use App\Support\PhoneNumber;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    /** @use HasFactory<CustomerFactory> */
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'full_name',
        'nationality',
        'phone',
        'phone_normalized',
        'email',
        'address',
        'foreign_address',
        'passport_or_cin',
        'passport_or_cin_normalized',
        'passport_or_cin_issued_at',
        'driving_license_number',
        'driving_license_issued_at',
        'driving_license_expires_at',
        'driving_license_country',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'driving_license_issued_at' => 'date',
            'driving_license_expires_at' => 'date',
            'passport_or_cin_issued_at' => 'date',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Customer $customer): void {
            if ($customer->isDirty('phone')) {
                $customer->phone_normalized = PhoneNumber::normalize($customer->phone);
            }

            if ($customer->isDirty('passport_or_cin')) {
                $customer->passport_or_cin_normalized = IdentityDocument::normalize($customer->passport_or_cin);
            }
        });
    }

    /**
     * @return HasMany<CustomerDocument, $this>
     */
    public function documents(): HasMany
    {
        return $this->hasMany(CustomerDocument::class);
    }

    /**
     * @return HasMany<Reservation, $this>
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }
}
