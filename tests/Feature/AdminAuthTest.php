<?php

namespace Tests\Feature;

use App\Models\User;
use App\Support\AdminAuthCookie;
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

        $loginResponse = $this->postJson('/api/admin/auth/login', $this->adminLoginCredentials());

        $loginResponse
            ->assertOk()
            ->assertJsonStructure([
                'user' => [
                    'id',
                    'name',
                    'email',
                    'roles',
                    'permissions',
                ],
            ])
            ->assertJsonPath('user.email', 'admin@limosudcars.local')
            ->assertCookie(AdminAuthCookie::NAME);

        $token = (string) $loginResponse->getCookie(AdminAuthCookie::NAME, false)->getValue();

        $this->withUnencryptedCookie(AdminAuthCookie::NAME, $token)
            ->getJson('/api/admin/auth/me')
            ->assertOk()
            ->assertJsonPath('data.email', 'admin@limosudcars.local')
            ->assertJsonPath('data.roles.0.slug', 'super_admin');
    }

    public function test_admin_can_update_own_profile(): void
    {
        $this->seed([
            RoleSeeder::class,
            PermissionSeeder::class,
            RolePermissionSeeder::class,
            AdminUserSeeder::class,
        ]);

        $this->withAdminAuth()
            ->patchJson('/api/admin/auth/profile', [
                'name' => 'Updated Admin Name',
                'phone' => '0612345678',
            ])
            ->assertOk()
            ->assertJsonPath('data.name', 'Updated Admin Name')
            ->assertJsonPath('data.phone', '0612345678')
            ->assertJsonPath('data.email', 'admin@limosudcars.local');

        $this->assertDatabaseHas('users', [
            'email' => 'admin@limosudcars.local',
            'name' => 'Updated Admin Name',
            'phone' => '0612345678',
        ]);
    }

    public function test_admin_can_change_password_with_current_password(): void
    {
        $this->seed([
            RoleSeeder::class,
            PermissionSeeder::class,
            RolePermissionSeeder::class,
            AdminUserSeeder::class,
        ]);

        $this->withAdminAuth()
            ->patchJson('/api/admin/auth/profile', [
                'current_password' => env('ADMIN_PASSWORD', 'password'),
                'password' => 'new-secure-password',
                'password_confirmation' => 'new-secure-password',
            ])
            ->assertOk();

        $this->postJson('/api/admin/auth/login', [
            'email' => 'admin@limosudcars.local',
            'password' => 'new-secure-password',
        ])->assertOk();

        User::where('email', 'admin@limosudcars.local')->update([
            'password' => env('ADMIN_PASSWORD', 'password'),
        ]);
    }

    public function test_profile_update_requires_current_password_when_changing_password(): void
    {
        $this->seed([
            RoleSeeder::class,
            PermissionSeeder::class,
            RolePermissionSeeder::class,
            AdminUserSeeder::class,
        ]);

        $this->withAdminAuth()
            ->patchJson('/api/admin/auth/profile', [
                'password' => 'new-secure-password',
                'password_confirmation' => 'new-secure-password',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['current_password']);
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

        $this->postJson('/api/admin/auth/login', $this->adminLoginCredentials())->assertUnprocessable();
    }
}
