<?php

namespace Tests\Feature;

use App\Models\Location;
use App\Models\LocationType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocationModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_list_update_show_and_delete_location(): void
    {
        $this->seed();

        $token = $this->adminToken();

        $createResponse = $this->withToken($token)->postJson('/api/admin/locations', [
            'location_type_slug' => 'airport',
            'name' => 'Dakhla Airport',
            'slug' => 'dakhla-airport',
            'address' => 'Dakhla Airport Terminal',
            'delivery_fee' => 150,
            'is_active' => true,
        ]);

        $createResponse
            ->assertCreated()
            ->assertJsonPath('data.slug', 'dakhla-airport')
            ->assertJsonPath('data.location_type.slug', 'airport')
            ->assertJsonPath('data.is_active', true);

        $location = Location::where('slug', 'dakhla-airport')->firstOrFail();

        $this->assertSame(LocationType::where('slug', 'airport')->value('id'), $location->location_type_id);

        $this->withToken($token)
            ->getJson('/api/admin/locations')
            ->assertOk()
            ->assertJsonPath('data.0.slug', 'dakhla-airport');

        $this->withToken($token)
            ->patchJson("/api/admin/locations/{$location->id}", [
                'location_type_slug' => 'hotel',
                'delivery_fee' => 200,
                'address' => null,
            ])
            ->assertOk()
            ->assertJsonPath('data.location_type.slug', 'hotel')
            ->assertJsonPath('data.delivery_fee', '200.00')
            ->assertJsonPath('data.address', null);

        $this->assertSame(LocationType::where('slug', 'hotel')->value('id'), $location->refresh()->location_type_id);

        $this->withToken($token)
            ->getJson("/api/admin/locations/{$location->id}")
            ->assertOk()
            ->assertJsonPath('data.slug', 'dakhla-airport');

        $this->withToken($token)
            ->deleteJson("/api/admin/locations/{$location->id}")
            ->assertNoContent();

        $this->assertSoftDeleted('locations', ['id' => $location->id]);
    }

    public function test_public_locations_endpoint_returns_only_active_locations(): void
    {
        $this->seed();

        $agencyTypeId = LocationType::where('slug', 'agency')->value('id');

        Location::create([
            'location_type_id' => $agencyTypeId,
            'name' => 'Limosud Cars Agency',
            'slug' => 'limosud-cars-agency',
            'address' => 'Dakhla Center',
            'delivery_fee' => 0,
            'is_active' => true,
        ]);

        Location::create([
            'location_type_id' => $agencyTypeId,
            'name' => 'Inactive Office',
            'slug' => 'inactive-office',
            'address' => 'Hidden',
            'delivery_fee' => 0,
            'is_active' => false,
        ]);

        $this->getJson('/api/public/locations')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.slug', 'limosud-cars-agency');
    }

    public function test_admin_location_endpoints_require_authentication(): void
    {
        $this->getJson('/api/admin/locations')->assertUnauthorized();
    }

}
