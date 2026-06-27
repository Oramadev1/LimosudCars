<?php

namespace Tests\Concerns;

use App\Support\AdminAuthCookie;

trait AuthenticatesAdmin
{
    protected function adminLoginCredentials(): array
    {
        return [
            'email' => 'admin@limosudcars.local',
            'password' => env('ADMIN_PASSWORD', 'password'),
        ];
    }

    protected function adminToken(): string
    {
        $response = $this->postJson('/api/admin/auth/login', $this->adminLoginCredentials());

        $response->assertOk();

        return (string) $response->getCookie(AdminAuthCookie::NAME, false)->getValue();
    }

    protected function withAdminAuth(): static
    {
        $token = $this->adminToken();

        return $this->withUnencryptedCookie(AdminAuthCookie::NAME, $token);
    }
}
