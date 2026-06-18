<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin User
 */
class UserResource extends JsonResource
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
            'email' => $this->email,
            'phone' => $this->phone,
            'is_active' => $this->is_active,
            'roles' => $this->whenLoaded('roles', function (): array {
                return $this->roles
                    ->map(fn ($role): array => [
                        'id' => $role->id,
                        'name' => $role->name,
                        'slug' => $role->slug,
                    ])
                    ->values()
                    ->all();
            }),
            'permissions' => $this->whenLoaded('roles', function (): array {
                return $this->roles
                    ->flatMap(fn ($role) => $role->permissions)
                    ->unique('slug')
                    ->sortBy('slug')
                    ->map(fn ($permission): array => [
                        'id' => $permission->id,
                        'module' => $permission->module,
                        'name' => $permission->name,
                        'slug' => $permission->slug,
                    ])
                    ->values()
                    ->all();
            }),
        ];
    }
}
