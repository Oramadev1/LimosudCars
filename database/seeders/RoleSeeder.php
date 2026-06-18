<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'Super Admin', 'slug' => 'super_admin'],
            ['name' => 'Admin', 'slug' => 'admin'],
            ['name' => 'Reservation Agent', 'slug' => 'reservation_agent'],
            ['name' => 'Accountant', 'slug' => 'accountant'],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['slug' => $role['slug']],
                ['name' => $role['name']]
            );
        }
    }
}
