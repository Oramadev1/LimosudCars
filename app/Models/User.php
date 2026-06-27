<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'is_active',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'is_active' => 'boolean',
            'password' => 'hashed',
        ];
    }

    /**
     * The roles assigned to the user.
     *
     * @return BelongsToMany<Role, $this>
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles')
            ->withTimestamps();
    }

    /**
     * Extra permissions granted directly to the user (on top of role permissions).
     *
     * @return BelongsToMany<Permission, $this>
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'user_permissions')
            ->withTimestamps();
    }

    /**
     * @return HasMany<Reservation, $this>
     */
    public function createdReservations(): HasMany
    {
        return $this->hasMany(Reservation::class, 'created_by');
    }

    /**
     * @return HasMany<Payment, $this>
     */
    public function createdPayments(): HasMany
    {
        return $this->hasMany(Payment::class, 'created_by');
    }

    /**
     * @return HasMany<Contract, $this>
     */
    public function generatedContracts(): HasMany
    {
        return $this->hasMany(Contract::class, 'generated_by');
    }

    public function createdExpenses(): HasMany
    {
        return $this->hasMany(Expense::class, 'created_by');
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super_admin');
    }

    public function hasRole(string $role): bool
    {
        return $this->roles()
            ->where('slug', $role)
            ->exists();
    }

    /**
     * @param  array<int, string>  $roles
     */
    public function hasAnyRole(array $roles): bool
    {
        return $this->roles()
            ->whereIn('slug', $roles)
            ->exists();
    }

    public function hasPermission(string $permission): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        if ($this->permissions()
            ->where('slug', $permission)
            ->exists()) {
            return true;
        }

        return $this->roles()
            ->whereHas('permissions', function ($query) use ($permission): void {
                $query->where('slug', $permission);
            })
            ->exists();
    }

    /**
     * @return array<int, string>
     */
    public function rolePermissionSlugs(): array
    {
        if (! $this->relationLoaded('roles')) {
            $this->load('roles.permissions');
        }

        return $this->roles
            ->flatMap(fn (Role $role) => $role->permissions)
            ->pluck('slug')
            ->unique()
            ->sort()
            ->values()
            ->all();
    }

    /**
     * @return array<int, string>
     */
    public function directPermissionSlugs(): array
    {
        if (! $this->relationLoaded('permissions')) {
            $this->load('permissions');
        }

        return $this->permissions
            ->pluck('slug')
            ->sort()
            ->values()
            ->all();
    }

    /**
     * @return array<int, string>
     */
    public function effectivePermissionSlugs(): array
    {
        if ($this->isSuperAdmin()) {
            return Permission::query()->orderBy('slug')->pluck('slug')->all();
        }

        return collect($this->rolePermissionSlugs())
            ->merge($this->directPermissionSlugs())
            ->unique()
            ->sort()
            ->values()
            ->all();
    }

    /**
     * @param  array<int, string>  $permissions
     */
    public function hasAnyPermission(array $permissions): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        return $this->roles()
            ->whereHas('permissions', function ($query) use ($permissions): void {
                $query->whereIn('slug', $permissions);
            })
            ->exists();
    }

    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    /**
     * @return array<string, mixed>
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }
}
