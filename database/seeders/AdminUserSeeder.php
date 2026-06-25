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

        $demoPassword = env('DEMO_STAFF_PASSWORD', env('ADMIN_PASSWORD', 'password'));

        $demoUsers = [
            [
                'email' => 'manager@limosudcars.local',
                'name' => 'Limosud Manager',
                'phone' => '0611111111',
                'role' => 'admin',
            ],
            [
                'email' => 'agent@limosudcars.local',
                'name' => 'Reservation Agent',
                'phone' => '0622222222',
                'role' => 'reservation_agent',
            ],
            [
                'email' => 'accountant@limosudcars.local',
                'name' => 'Limosud Accountant',
                'phone' => '0633333333',
                'role' => 'accountant',
            ],
        ];

        foreach ($demoUsers as $demoUser) {
            $staff = User::updateOrCreate(
                ['email' => $demoUser['email']],
                [
                    'name' => $demoUser['name'],
                    'phone' => $demoUser['phone'],
                    'is_active' => true,
                    'password' => $demoPassword,
                    'email_verified_at' => now(),
                ]
            );

            $role = Role::where('slug', $demoUser['role'])->first();

            if ($role) {
                $staff->roles()->sync([$role->id]);
            }
        }
    }
}
