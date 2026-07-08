<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $allPermissionIds = Permission::query()->pluck('id')->all();

        Role::where('slug', 'super_admin')->first()?->permissions()->sync($allPermissionIds);

        $this->syncRolePermissions('admin', [
            'dashboard.view',
            'users.view',
            'users.create',
            'users.update',
            'roles.view',
            'permissions.view',
            'permissions.assign',
            'vehicles.view',
            'vehicles.create',
            'vehicles.update',
            'vehicles.delete',
            'vehicle_brands.view',
            'vehicle_brands.create',
            'vehicle_brands.update',
            'vehicle_brands.delete',
            'vehicle_categories.view',
            'vehicle_categories.create',
            'vehicle_categories.update',
            'vehicle_categories.delete',
            'customers.view',
            'customers.create',
            'customers.update',
            'customers.delete',
            'reservations.view',
            'reservations.create',
            'reservations.update',
            'reservations.delete',
            'reservations.confirm',
            'reservations.start',
            'reservations.complete',
            'reservations.cancel',
            'reservations.reject',
            'payments.view',
            'payments.manage',
            'contracts.view',
            'contracts.generate',
            'contracts.update',
            'locations.view',
            'locations.create',
            'locations.update',
            'locations.delete',
            'maintenance.view',
            'maintenance.create',
            'maintenance.update',
            'maintenance.delete',
            'expenses.view',
            'alerts.view',
            'alerts.create',
            'alerts.update',
            'alerts.close',
            'site_pages.view',
            'site_pages.create',
            'site_pages.update',
            'site_pages.delete',
            'audit_logs.view',
        ]);

        $this->syncRolePermissions('reservation_agent', [
            'dashboard.view',
            'vehicles.view',
            'vehicle_brands.view',
            'vehicle_categories.view',
            'customers.view',
            'customers.create',
            'customers.update',
            'reservations.view',
            'reservations.create',
            'reservations.update',
            'reservations.confirm',
            'reservations.start',
            'reservations.complete',
            'reservations.cancel',
            'reservations.reject',
            'payments.view',
            'contracts.view',
            'contracts.generate',
            'locations.view',
            'alerts.view',
            'alerts.update',
            'alerts.close',
        ]);

        $this->syncRolePermissions('accountant', [
            'dashboard.view',
            'reservations.view',
            'payments.view',
            'payments.manage',
            'expenses.view',
            'expenses.create',
            'expenses.update',
            'contracts.view',
        ]);
    }

    /**
     * @param  array<int, string>  $permissionSlugs
     */
    private function syncRolePermissions(string $roleSlug, array $permissionSlugs): void
    {
        $role = Role::where('slug', $roleSlug)->first();

        if (! $role) {
            return;
        }

        $permissionIds = Permission::whereIn('slug', $permissionSlugs)
            ->pluck('id')
            ->all();

        $role->permissions()->sync($permissionIds);
    }
}
