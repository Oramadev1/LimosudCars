<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = env('ADMIN_EMAIL', 'admin@limosudcars.local');

        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => env('ADMIN_NAME', 'Limosud Cars Admin'),
                'phone' => env('ADMIN_PHONE'),
                'is_active' => true,
                'password' => env('ADMIN_PASSWORD', 'password'),
                'email_verified_at' => now(),
            ]
        );

        $superAdminRole = Role::where('slug', 'super_admin')->first();

        if ($superAdminRole) {
            $user->roles()->syncWithoutDetaching([$superAdminRole->id]);
        }
    }
}
