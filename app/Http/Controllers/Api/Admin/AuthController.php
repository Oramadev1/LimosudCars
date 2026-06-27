<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LoginRequest;
use App\Http\Requests\Admin\UpdateProfileRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Support\AdminAuthCookie;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

/**
 * @group Admin Auth
 *
 * JWT authentication for the admin panel. The token is issued as an HttpOnly cookie.
 */
class AuthController extends Controller
{
    /**
     * Authenticate an admin user and set an HttpOnly JWT cookie.
     *
     * @unauthenticated
     *
     * @bodyParam email string required Admin email address. Example: admin@limosudcars.local
     * @bodyParam password string required Admin password. Example: password
     *
     * @throws ValidationException
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        if (! $token = Auth::guard('api')->attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        /** @var User $user */
        $user = Auth::guard('api')->user();

        if (! $user->is_active) {
            Auth::guard('api')->logout();

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        $user->load('roles.permissions');

        return response()
            ->json([
                'user' => new UserResource($user),
            ])
            ->withCookie(AdminAuthCookie::attach($token));
    }

    /**
     * Return the authenticated admin user with roles and permissions.
     */
    public function me(Request $request): UserResource
    {
        /** @var User $user */
        $user = $request->user();

        return new UserResource($user->load(['roles.permissions', 'permissions']));
    }

    /**
     * Update the authenticated admin user's profile.
     */
    public function updateProfile(UpdateProfileRequest $request): UserResource
    {
        /** @var User $user */
        $user = $request->user();

        $validated = $request->validated();

        $user->fill(collect($validated)->only(['name', 'email', 'phone'])->all());

        if (! empty($validated['password'])) {
            $user->password = $validated['password'];
        }

        $user->save();

        return new UserResource($user->load(['roles.permissions', 'permissions']));
    }

    /**
     * Invalidate the current JWT and clear the auth cookie.
     */
    public function logout(Request $request): JsonResponse
    {
        Auth::guard('api')->logout();

        return response()
            ->json([
                'message' => 'Logged out successfully.',
            ])
            ->withCookie(AdminAuthCookie::forget());
    }
}
