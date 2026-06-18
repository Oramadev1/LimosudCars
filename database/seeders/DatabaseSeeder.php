<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Production bootstrap: admin login only.
     *
     * System lookup values (statuses, payment types, etc.) are inserted by migration.
     * Brands, categories, vehicles, and customers are created through the admin panel.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            RolePermissionSeeder::class,
            AdminUserSeeder::class,
        ]);
    }
}
