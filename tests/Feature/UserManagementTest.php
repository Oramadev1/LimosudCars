<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_assign_direct_permissions_to_user(): void
    {
        $this->seed([
            RoleSeeder::class,
            PermissionSeeder::class,
            RolePermissionSeeder::class,
            AdminUserSeeder::class,
        ]);

        $manager = User::where('email', 'manager@limosudcars.local')->firstOrFail();
        $expensesCreate = Permission::where('slug', 'expenses.create')->value('id');

        $token = $this->postJson('/api/admin/auth/login', [
            'email' => 'admin@limosudcars.local',
            'password' => env('ADMIN_PASSWORD', 'password'),
        ])->json('access_token');

        $this->withToken($token)
            ->patchJson("/api/admin/users/{$manager->id}/permissions", [
                'permission_ids' => Permission::whereIn('slug', [
                    'dashboard.view',
                    'expenses.view',
                    'expenses.create',
                ])->pluck('id')->all(),
                'role_ids' => [Role::where('slug', 'admin')->value('id')],
            ])
            ->assertOk()
            ->assertJsonPath('direct_permission_slugs.0', 'expenses.create');

        $manager->refresh();
        $this->assertTrue($manager->hasPermission('expenses.create'));
    }

    public function test_super_admin_can_sync_role_permissions(): void
    {
        $this->seed([
            RoleSeeder::class,
            PermissionSeeder::class,
            RolePermissionSeeder::class,
            AdminUserSeeder::class,
        ]);

        $accountantRole = Role::where('slug', 'accountant')->firstOrFail();
        $permissionIds = Permission::whereIn('slug', [
            'dashboard.view',
            'expenses.view',
            'payments.view',
        ])->pluck('id')->all();

        $token = $this->postJson('/api/admin/auth/login', [
            'email' => 'admin@limosudcars.local',
            'password' => env('ADMIN_PASSWORD', 'password'),
        ])->json('access_token');

        $this->withToken($token)
            ->patchJson("/api/admin/roles/{$accountantRole->id}/permissions", [
                'permission_ids' => $permissionIds,
            ])
            ->assertOk()
            ->assertJsonCount(3, 'data.permissions');

        $this->assertTrue(
            User::where('email', 'accountant@limosudcars.local')->firstOrFail()->hasPermission('expenses.view')
        );
    }
}
