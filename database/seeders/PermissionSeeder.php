<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->permissions() as $module => $permissions) {
            foreach ($permissions as $slug => $name) {
                Permission::updateOrCreate(
                    ['slug' => $slug],
                    [
                        'module' => $module,
                        'name' => $name,
                    ]
                );
            }
        }
    }

    /**
     * @return array<string, array<string, string>>
     */
    private function permissions(): array
    {
        return [
            'dashboard' => [
                'dashboard.view' => 'View dashboard',
            ],
            'users' => [
                'users.view' => 'View users',
                'users.create' => 'Create users',
                'users.update' => 'Update users',
                'users.delete' => 'Delete users',
            ],
            'roles' => [
                'roles.view' => 'View roles',
                'roles.create' => 'Create roles',
                'roles.update' => 'Update roles',
                'roles.delete' => 'Delete roles',
            ],
            'permissions' => [
                'permissions.view' => 'View permissions',
                'permissions.assign' => 'Assign permissions',
            ],
            'vehicles' => [
                'vehicles.view' => 'View vehicles',
                'vehicles.create' => 'Create vehicles',
                'vehicles.update' => 'Update vehicles',
                'vehicles.delete' => 'Delete vehicles',
            ],
            'vehicle_brands' => [
                'vehicle_brands.view' => 'View vehicle brands',
                'vehicle_brands.create' => 'Create vehicle brands',
                'vehicle_brands.update' => 'Update vehicle brands',
                'vehicle_brands.delete' => 'Delete vehicle brands',
            ],
            'vehicle_categories' => [
                'vehicle_categories.view' => 'View vehicle categories',
                'vehicle_categories.create' => 'Create vehicle categories',
                'vehicle_categories.update' => 'Update vehicle categories',
                'vehicle_categories.delete' => 'Delete vehicle categories',
            ],
            'customers' => [
                'customers.view' => 'View customers',
                'customers.create' => 'Create customers',
                'customers.update' => 'Update customers',
                'customers.delete' => 'Delete customers',
            ],
            'reservations' => [
                'reservations.view' => 'View reservations',
                'reservations.create' => 'Create reservations',
                'reservations.update' => 'Update reservations',
                'reservations.delete' => 'Delete reservations',
                'reservations.confirm' => 'Confirm reservations',
                'reservations.start' => 'Start reservations',
                'reservations.complete' => 'Complete reservations',
                'reservations.cancel' => 'Cancel reservations',
                'reservations.reject' => 'Reject reservations',
            ],
            'payments' => [
                'payments.view' => 'View payments',
                'payments.manage' => 'Manage payments',
            ],
            'contracts' => [
                'contracts.view' => 'View contracts',
                'contracts.generate' => 'Generate contracts',
                'contracts.update' => 'Update contracts',
            ],
            'locations' => [
                'locations.view' => 'View locations',
                'locations.create' => 'Create locations',
                'locations.update' => 'Update locations',
                'locations.delete' => 'Delete locations',
            ],
            'maintenance' => [
                'maintenance.view' => 'View maintenance',
                'maintenance.create' => 'Create maintenance',
                'maintenance.update' => 'Update maintenance',
                'maintenance.delete' => 'Delete maintenance',
            ],
            'expenses' => [
                'expenses.view' => 'View expenses',
                'expenses.create' => 'Create expenses',
                'expenses.update' => 'Update expenses',
                'expenses.delete' => 'Delete expenses',
            ],
            'alerts' => [
                'alerts.view' => 'View alerts',
                'alerts.create' => 'Create alerts',
                'alerts.update' => 'Update alerts',
                'alerts.close' => 'Close alerts',
            ],
            'contact_messages' => [
                'contact_messages.view' => 'View contact messages',
                'contact_messages.update' => 'Update contact messages',
                'contact_messages.delete' => 'Delete contact messages',
            ],
            'site_pages' => [
                'site_pages.view' => 'View site pages',
                'site_pages.create' => 'Create site pages',
                'site_pages.update' => 'Update site pages',
                'site_pages.delete' => 'Delete site pages',
            ],
            'audit_logs' => [
                'audit_logs.view' => 'View audit logs',
            ],
        ];
    }
}
