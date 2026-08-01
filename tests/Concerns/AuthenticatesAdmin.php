<?php

namespace Tests\Concerns;

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

        return (string) $response->json('access_token');
    }

    protected function withAdminAuth(): static
    {
        return $this->withToken($this->adminToken());
    }
}
