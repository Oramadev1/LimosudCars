<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_login_and_fetch_profile(): void
    {
        $this->seed([
            RoleSeeder::class,
            PermissionSeeder::class,
            RolePermissionSeeder::class,
            AdminUserSeeder::class,
        ]);

        $loginResponse = $this->postJson('/api/admin/auth/login', [
            'email' => 'admin@limosudcars.local',
            'password' => env('ADMIN_PASSWORD', 'password'),
        ]);

        $loginResponse
            ->assertOk()
            ->assertJsonStructure([
                'token_type',
                'access_token',
                'user' => [
                    'id',
                    'name',
                    'email',
                    'roles',
                    'permissions',
                ],
            ])
            ->assertJsonPath('token_type', 'Bearer')
            ->assertJsonPath('user.email', 'admin@limosudcars.local');

        $token = $loginResponse->json('access_token');

        $this->withToken($token)
            ->getJson('/api/admin/auth/me')
            ->assertOk()
            ->assertJsonPath('data.email', 'admin@limosudcars.local')
            ->assertJsonPath('data.roles.0.slug', 'super_admin');
    }

    public function test_inactive_admin_cannot_login(): void
    {
        $this->seed([
            RoleSeeder::class,
            PermissionSeeder::class,
            RolePermissionSeeder::class,
            AdminUserSeeder::class,
        ]);

        User::where('email', 'admin@limosudcars.local')->update([
            'is_active' => false,
        ]);

        $this->postJson('/api/admin/auth/login', [
            'email' => 'admin@limosudcars.local',
            'password' => env('ADMIN_PASSWORD', 'password'),
        ])->assertUnprocessable();
    }
}
