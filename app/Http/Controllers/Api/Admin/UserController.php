<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SyncRolePermissionsRequest;
use App\Http\Requests\Admin\SyncUserPermissionsRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Http\Resources\PermissionResource;
use App\Http\Resources\RoleResource;
use App\Http\Resources\UserResource;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $this->authorize('viewAny', User::class);

        $users = User::query()
            ->with(['roles', 'permissions'])
            ->orderBy('name')
            ->paginate(20);

        return UserResource::collection($users);
    }

    public function show(User $user): JsonResponse
    {
        $this->authorize('view', $user);

        $user->load(['roles.permissions', 'permissions']);

        return response()->json([
            'data' => new UserResource($user),
            'role_permission_slugs' => $user->rolePermissionSlugs(),
            'direct_permission_slugs' => $user->directPermissionSlugs(),
            'effective_permission_slugs' => $user->effectivePermissionSlugs(),
        ]);
    }

    public function update(UpdateUserRequest $request, User $user): UserResource
    {
        $validated = $request->validated();

        $user->fill(collect($validated)->only(['name', 'email', 'phone', 'is_active'])->all());
        $user->save();

        if (array_key_exists('role_ids', $validated)) {
            $user->roles()->sync($validated['role_ids']);
        }

        return new UserResource($user->load(['roles.permissions', 'permissions']));
    }

    public function syncPermissions(SyncUserPermissionsRequest $request, User $user): JsonResponse
    {
        $validated = $request->validated();

        if (array_key_exists('role_ids', $validated)) {
            $user->roles()->sync($validated['role_ids']);
        }

        $rolePermissionIds = $user->roles()
            ->with('permissions')
            ->get()
            ->flatMap(fn (Role $role) => $role->permissions)
            ->pluck('id')
            ->unique()
            ->all();

        $requestedIds = collect($validated['permission_ids'])
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        $directPermissionIds = $requestedIds
            ->diff($rolePermissionIds)
            ->values()
            ->all();

        $user->permissions()->sync($directPermissionIds);
        $user->load(['roles.permissions', 'permissions']);

        return response()->json([
            'data' => new UserResource($user),
            'role_permission_slugs' => $user->rolePermissionSlugs(),
            'direct_permission_slugs' => $user->directPermissionSlugs(),
            'effective_permission_slugs' => $user->effectivePermissionSlugs(),
        ]);
    }
}
