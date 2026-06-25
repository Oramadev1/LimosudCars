<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LoginRequest;
use App\Http\Requests\Admin\UpdateProfileRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * @group Admin Auth
 *
 * Endpoints for issuing and revoking Laravel Sanctum admin API tokens.
 */
class AuthController extends Controller
{
    /**
     * Authenticate an admin user and issue a Sanctum token.
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

        $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        if (! $user->is_active) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        $user->load('roles.permissions');

        return response()->json([
            'token_type' => 'Bearer',
            'access_token' => $user->createToken('admin-api')->plainTextToken,
            'user' => new UserResource($user),
        ]);
    }

    /**
     * Return the authenticated admin user with roles and permissions.
     *
     * Send a Sanctum bearer token in the Authorization header.
     */
    public function me(Request $request): UserResource
    {
        /** @var User $user */
        $user = $request->user();

        return new UserResource($user->load(['roles.permissions', 'permissions']));
    }

    /**
     * Update the authenticated admin user's profile.
     *
     * Send a Sanctum bearer token in the Authorization header.
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
     * Revoke the current Sanctum token.
     *
     * Send a Sanctum bearer token in the Authorization header.
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()?->currentAccessToken()?->delete();

        return response()->json([
            'message' => 'Logged out successfully.',
        ]);
    }
}
