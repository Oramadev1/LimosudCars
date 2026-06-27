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
 * JWT in an HttpOnly cookie (not Sanctum bearer tokens).
 */
class AuthController extends Controller
{
    /**
     * Authenticate an admin user and set an HttpOnly JWT cookie.
     *
     * @unauthenticated
     *
     * @throws ValidationException
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');

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

        $ttlMinutes = (int) config('jwt.ttl', 1440);

        return response()
            ->json([
                'message' => 'Login successful',
                'expires_in_minutes' => $ttlMinutes,
                'user' => new UserResource($user),
            ])
            ->withCookie(AdminAuthCookie::attach($token));
    }

    public function me(Request $request): UserResource
    {
        /** @var User $user */
        $user = $request->user();

        return new UserResource($user->load(['roles.permissions', 'permissions']));
    }

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
