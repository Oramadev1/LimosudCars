<?php

namespace Tests\Feature;

use App\Models\Alert;
use App\Models\AlertStatus;
use App\Models\AlertType;
use App\Models\Customer;
use App\Models\Location;
use App\Models\LocationType;
use App\Models\PaymentStatus;
use App\Models\Reservation;
use App\Models\ReservationSource;
use App\Models\ReservationStatus;
use App\Models\Vehicle;
use App\Models\VehicleStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_reservation_creates_pending_request_without_changing_vehicle_status(): void
    {
        $this->seed();
        $vehicle = $this->vehicle();
        [$pickupLocation, $dropoffLocation] = $this->locations();

        $response = $this->postJson('/api/public/reservations', [
            'customer' => [
                'full_name' => 'Ahmed Dakhla',
                'nationality' => 'Moroccan',
                'phone' => '+212600000101',
                'email' => 'ahmed@example.com',
            ],
            'vehicle_id' => $vehicle->id,
            'pickup_location_id' => $pickupLocation->id,
            'dropoff_location_id' => $dropoffLocation->id,
            'start_datetime' => now()->addDays(5)->setTime(10, 0)->toDateTimeString(),
            'end_datetime' => now()->addDays(8)->setTime(10, 0)->toDateTimeString(),
            'customer_notes' => 'Airport pickup please.',
        ]);

        $response
            ->assertCreated()
            ->assertJsonPath('data.source.slug', 'website')
            ->assertJsonPath('data.status.slug', 'pending')
            ->assertJsonPath('data.payment_status.slug', 'unpaid')
            ->assertJsonPath('data.total_days', 3);

        $reservation = Reservation::firstOrFail();

        $this->assertSame(VehicleStatus::where('slug', 'available')->value('id'), $vehicle->refresh()->status_id);
        $this->assertSame(Customer::where('email', 'ahmed@example.com')->value('id'), $reservation->customer_id);

        $this->assertDatabaseHas('alerts', [
            'vehicle_id' => $vehicle->id,
            'alert_type_id' => AlertType::where('slug', 'reservation_follow_up')->value('id'),
            'alert_status_id' => AlertStatus::where('slug', 'pending')->value('id'),
            'title' => 'New reservation '.$reservation->reservation_number,
        ]);
    }

    public function test_confirming_reservation_resolves_follow_up_alert(): void
    {
        $this->seed();
        $vehicle = $this->vehicle('alert-resolve-car');
        [$pickupLocation, $dropoffLocation] = $this->locations();

        $this->postJson('/api/public/reservations', [
            'customer' => [
                'full_name' => 'Sara Alert',
                'nationality' => 'Moroccan',
                'phone' => '+212600000202',
            ],
            'vehicle_id' => $vehicle->id,
            'pickup_location_id' => $pickupLocation->id,
            'dropoff_location_id' => $dropoffLocation->id,
            'start_datetime' => now()->addDays(6)->setTime(10, 0)->toDateTimeString(),
            'end_datetime' => now()->addDays(9)->setTime(10, 0)->toDateTimeString(),
        ])->assertCreated();

        $reservation = Reservation::firstOrFail();

        $this->withToken($this->adminToken())
            ->getJson('/api/admin/alerts/pending')
            ->assertOk()
            ->assertJsonPath('meta.total', 1);

        $this->withToken($this->adminToken())
            ->postJson("/api/admin/reservations/{$reservation->id}/confirm")
            ->assertOk();

        $this->assertSame(0, Alert::query()
            ->where('alert_status_id', AlertStatus::where('slug', 'pending')->value('id'))
            ->where('title', 'New reservation '.$reservation->reservation_number)
            ->count());

        $this->withToken($this->adminToken())
            ->getJson('/api/admin/alerts/pending')
            ->assertOk()
            ->assertJsonPath('meta.total', 0);
    }

    public function test_public_reservation_reuses_existing_customer_by_phone(): void
    {
        $this->seed();
        $vehicle = $this->vehicle('reuse-customer-car');
        [$pickupLocation, $dropoffLocation] = $this->locations();

        $existingCustomer = Customer::factory()->create([
            'full_name' => 'Ayoub',
            'phone' => '0635354343',
            'email' => 'mssallak8@gmail.com',
        ]);

        $payload = [
            'customer' => [
                'full_name' => 'Ayoub',
                'nationality' => 'Moroccan',
                'phone' => '+212635354343',
                'email' => 'mssallak8@gmail.com',
            ],
            'vehicle_id' => $vehicle->id,
            'pickup_location_id' => $pickupLocation->id,
            'dropoff_location_id' => $dropoffLocation->id,
            'start_datetime' => now()->addDays(30)->setTime(10, 0)->toDateTimeString(),
            'end_datetime' => now()->addDays(33)->setTime(10, 0)->toDateTimeString(),
        ];

        $this->postJson('/api/public/reservations', $payload)->assertCreated();

        $this->assertSame(1, Customer::count());
        $this->assertSame($existingCustomer->id, Reservation::firstOrFail()->customer_id);
    }

    public function test_public_reservation_reuses_existing_customer_by_passport_or_cin(): void
    {
        $this->seed();
        $vehicle = $this->vehicle('reuse-passport-car');
        [$pickupLocation, $dropoffLocation] = $this->locations();

        $existingCustomer = Customer::factory()->create([
            'full_name' => 'Ayoub',
            'phone' => '0611111111',
            'passport_or_cin' => 'AA123456',
        ]);

        $this->postJson('/api/public/reservations', [
            'customer' => [
                'full_name' => 'Ayoub',
                'nationality' => 'Moroccan',
                'phone' => '0622222222',
                'passport_or_cin' => 'AA 123-456',
            ],
            'vehicle_id' => $vehicle->id,
            'pickup_location_id' => $pickupLocation->id,
            'dropoff_location_id' => $dropoffLocation->id,
            'start_datetime' => now()->addDays(40)->setTime(10, 0)->toDateTimeString(),
            'end_datetime' => now()->addDays(43)->setTime(10, 0)->toDateTimeString(),
        ])->assertCreated();

        $this->assertSame(1, Customer::count());
        $this->assertSame($existingCustomer->id, Reservation::firstOrFail()->customer_id);
    }

    public function test_admin_manual_reservation_uses_admin_manual_source_and_calendar_endpoint(): void
    {
        $this->seed();
        $token = $this->adminToken();
        $vehicle = $this->vehicle();
        $customer = Customer::factory()->create();
        [$pickupLocation, $dropoffLocation] = $this->locations();

        $response = $this->withToken($token)->postJson('/api/admin/reservations', [
            'customer_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'pickup_location_id' => $pickupLocation->id,
            'dropoff_location_id' => $dropoffLocation->id,
            'start_datetime' => now()->addDays(15)->setTime(9, 0)->toDateTimeString(),
            'end_datetime' => now()->addDays(17)->setTime(9, 0)->toDateTimeString(),
            'admin_notes' => 'Manual booking.',
        ]);

        $response
            ->assertCreated()
            ->assertJsonPath('data.source.slug', 'admin_manual')
            ->assertJsonPath('data.status.slug', 'pending');

        $this->withToken($token)
            ->getJson('/api/admin/reservations-calendar')
            ->assertOk()
            ->assertJsonPath('data.0.source.slug', 'admin_manual');
    }

    public function test_admin_can_confirm_start_and_complete_reservation(): void
    {
        $this->seed();
        $token = $this->adminToken();
        $vehicle = $this->vehicle();
        $reservation = $this->reservation($vehicle, 'pending');

        $this->withToken($token)
            ->postJson("/api/admin/reservations/{$reservation->id}/confirm")
            ->assertOk()
            ->assertJsonPath('data.status.slug', 'confirmed');

        $this->assertNotNull($reservation->refresh()->confirmed_at);
        $this->assertSame(VehicleStatus::where('slug', 'reserved')->value('id'), $vehicle->refresh()->status_id);

        $this->withToken($token)
            ->postJson("/api/admin/reservations/{$reservation->id}/start")
            ->assertOk()
            ->assertJsonPath('data.status.slug', 'in_progress');

        $this->assertNotNull($reservation->refresh()->started_at);
        $this->assertSame(VehicleStatus::where('slug', 'rented')->value('id'), $vehicle->refresh()->status_id);

        $this->withToken($token)
            ->postJson("/api/admin/reservations/{$reservation->id}/complete")
            ->assertOk()
            ->assertJsonPath('data.status.slug', 'completed');

        $this->assertNotNull($reservation->refresh()->completed_at);
        $this->assertSame(VehicleStatus::where('slug', 'available')->value('id'), $vehicle->refresh()->status_id);
    }

    public function test_admin_can_cancel_and_reject_reservations(): void
    {
        $this->seed();
        $token = $this->adminToken();

        $confirmedVehicle = $this->vehicle('cancel-flow-car');
        $confirmedReservation = $this->reservation($confirmedVehicle, 'confirmed');
        $confirmedVehicle->update(['status_id' => VehicleStatus::where('slug', 'reserved')->value('id')]);

        $this->withToken($token)
            ->postJson("/api/admin/reservations/{$confirmedReservation->id}/cancel")
            ->assertOk()
            ->assertJsonPath('data.status.slug', 'cancelled');

        $this->assertNotNull($confirmedReservation->refresh()->cancelled_at);
        $this->assertSame(VehicleStatus::where('slug', 'available')->value('id'), $confirmedVehicle->refresh()->status_id);

        $pendingReservation = $this->reservation($this->vehicle('reject-flow-car'), 'pending');

        $this->withToken($token)
            ->postJson("/api/admin/reservations/{$pendingReservation->id}/reject")
            ->assertOk()
            ->assertJsonPath('data.status.slug', 'rejected');
    }

    public function test_admin_can_reopen_cancelled_or_rejected_reservation_as_pending(): void
    {
        $this->seed();
        $token = $this->adminToken();

        $cancelledVehicle = $this->vehicle('reopen-cancel-car');
        $cancelledReservation = $this->reservation($cancelledVehicle, 'confirmed');
        $cancelledVehicle->update(['status_id' => VehicleStatus::where('slug', 'reserved')->value('id')]);

        $this->withToken($token)
            ->postJson("/api/admin/reservations/{$cancelledReservation->id}/cancel")
            ->assertOk()
            ->assertJsonPath('data.status.slug', 'cancelled');

        $this->withToken($token)
            ->postJson("/api/admin/reservations/{$cancelledReservation->id}/reopen")
            ->assertOk()
            ->assertJsonPath('data.status.slug', 'pending');

        $cancelledReservation->refresh();
        $this->assertNull($cancelledReservation->cancelled_at);
        $this->assertNull($cancelledReservation->confirmed_at);

        $rejectedReservation = $this->reservation($this->vehicle('reopen-reject-car'), 'pending');
        $this->withToken($token)
            ->postJson("/api/admin/reservations/{$rejectedReservation->id}/reject")
            ->assertOk();

        $this->withToken($token)
            ->postJson("/api/admin/reservations/{$rejectedReservation->id}/reopen")
            ->assertOk()
            ->assertJsonPath('data.status.slug', 'pending');
    }

    public function test_admin_check_availability_returns_blocked_and_suggested_periods(): void
    {
        $this->seed();
        $token = $this->adminToken();
        $vehicle = $this->vehicle('schedule-car');
        $existing = $this->reservation($vehicle, 'confirmed', now()->addDays(10)->setTime(10, 0), now()->addDays(13)->setTime(10, 0));

        $this->withToken($token)
            ->getJson('/api/admin/reservations/vehicle-availability?vehicle_id='.$vehicle->id)
            ->assertOk()
            ->assertJsonPath('vehicle_rentable', true)
            ->assertJsonCount(1, 'blocked_periods')
            ->assertJsonPath('blocked_periods.0.reservation_number', $existing->reservation_number);

        $this->withToken($token)
            ->postJson('/api/admin/reservations/check-availability', [
                'vehicle_id' => $vehicle->id,
                'start_datetime' => now()->addDays(11)->setTime(10, 0)->toDateTimeString(),
                'end_datetime' => now()->addDays(12)->setTime(10, 0)->toDateTimeString(),
            ])
            ->assertOk()
            ->assertJsonPath('available', false)
            ->assertJsonCount(1, 'conflicting_periods')
            ->assertJsonPath('conflicting_periods.0.reservation_number', $existing->reservation_number)
            ->assertJson(fn ($json) => $json->has('suggested_periods')->etc());
    }

    public function test_overlap_prevention_blocks_active_reservation_conflicts(): void
    {
        $this->seed();
        $vehicle = $this->vehicle();
        $this->reservation($vehicle, 'confirmed', now()->addDays(10), now()->addDays(13));
        [$pickupLocation, $dropoffLocation] = $this->locations();

        $this->postJson('/api/public/reservations/check-availability', [
            'vehicle_id' => $vehicle->id,
            'start_datetime' => now()->addDays(11)->toDateTimeString(),
            'end_datetime' => now()->addDays(12)->toDateTimeString(),
        ])
            ->assertOk()
            ->assertJsonPath('available', false);

        $this->postJson('/api/public/reservations', [
            'customer' => [
                'full_name' => 'Overlap Client',
                'nationality' => 'Moroccan',
                'phone' => '+212600000102',
            ],
            'vehicle_id' => $vehicle->id,
            'pickup_location_id' => $pickupLocation->id,
            'dropoff_location_id' => $dropoffLocation->id,
            'start_datetime' => now()->addDays(11)->toDateTimeString(),
            'end_datetime' => now()->addDays(12)->toDateTimeString(),
        ])->assertUnprocessable();

        $this->postJson('/api/public/reservations/check-availability', [
            'vehicle_id' => $vehicle->id,
            'start_datetime' => now()->addDays(13)->toDateTimeString(),
            'end_datetime' => now()->addDays(14)->toDateTimeString(),
        ])
            ->assertOk()
            ->assertJsonPath('available', true);
    }

    public function test_overlap_prevention_blocks_pending_reservation_conflicts(): void
    {
        $this->seed();
        $vehicle = $this->vehicle('pending-overlap-car');
        $this->reservation($vehicle, 'pending', now()->addDays(5), now()->addDays(8));
        [$pickupLocation, $dropoffLocation] = $this->locations();

        $this->postJson('/api/public/reservations/check-availability', [
            'vehicle_id' => $vehicle->id,
            'start_datetime' => now()->addDays(6)->toDateTimeString(),
            'end_datetime' => now()->addDays(7)->toDateTimeString(),
        ])
            ->assertOk()
            ->assertJsonPath('available', false);

        $this->postJson('/api/public/reservations', [
            'customer' => [
                'full_name' => 'Pending Overlap Client',
                'nationality' => 'Moroccan',
                'phone' => '+212600000103',
            ],
            'vehicle_id' => $vehicle->id,
            'pickup_location_id' => $pickupLocation->id,
            'dropoff_location_id' => $dropoffLocation->id,
            'start_datetime' => now()->addDays(6)->toDateTimeString(),
            'end_datetime' => now()->addDays(7)->toDateTimeString(),
        ])->assertUnprocessable();
    }

    public function test_availability_blocks_vehicles_in_maintenance(): void
    {
        $this->seed();
        $vehicle = $this->vehicle('maintenance-car');
        $vehicle->update([
            'status_id' => VehicleStatus::where('slug', 'maintenance')->value('id'),
        ]);

        $this->postJson('/api/public/reservations/check-availability', [
            'vehicle_id' => $vehicle->id,
            'start_datetime' => now()->addDays(20)->toDateTimeString(),
            'end_datetime' => now()->addDays(22)->toDateTimeString(),
        ])
            ->assertOk()
            ->assertJsonPath('available', false);
    }

    public function test_admin_reservation_show_includes_soft_deleted_locations(): void
    {
        $this->seed();
        $vehicle = $this->vehicle('deleted-location-car');
        $reservation = $this->reservation($vehicle, 'pending');
        $pickupLocation = $reservation->pickupLocation;
        $pickupLocation?->delete();

        $this->withToken($this->adminToken())
            ->getJson("/api/admin/reservations/{$reservation->id}")
            ->assertOk()
            ->assertJsonPath('data.pickup_location.name', $pickupLocation?->name)
            ->assertJsonPath('data.dropoff_location.name', $reservation->dropoffLocation?->name);
    }

    private function vehicle(string $slug = 'reservation-test-car'): Vehicle
    {
        return Vehicle::factory()->create([
            'name' => str($slug)->headline()->toString(),
            'slug' => $slug,
            'plate_number' => strtoupper(str_replace('-', '', $slug)),
        ]);
    }

    /**
     * @return array{0: Location, 1: Location}
     */
    private function locations(): array
    {
        $agencyTypeId = LocationType::where('slug', 'agency')->value('id');
        $airportTypeId = LocationType::where('slug', 'airport')->value('id');

        return [
            Location::firstOrCreate(
                ['slug' => 'reservation-agency'],
                [
                    'location_type_id' => $agencyTypeId,
                    'name' => 'Reservation Agency',
                    'address' => 'Dakhla Center',
                    'delivery_fee' => 0,
                    'is_active' => true,
                ]
            ),
            Location::firstOrCreate(
                ['slug' => 'reservation-airport'],
                [
                    'location_type_id' => $airportTypeId,
                    'name' => 'Reservation Airport',
                    'address' => 'Dakhla Airport',
                    'delivery_fee' => 150,
                    'is_active' => true,
                ]
            ),
        ];
    }

    private function reservation(Vehicle $vehicle, string $statusSlug, mixed $startAt = null, mixed $endAt = null): Reservation
    {
        [$pickupLocation, $dropoffLocation] = $this->locations();
        $startAt ??= now()->addDays(20)->setTime(10, 0);
        $endAt ??= now()->addDays(23)->setTime(10, 0);

        return Reservation::create([
            'reservation_number' => 'RSV-TEST-'.fake()->unique()->numerify('####'),
            'customer_id' => Customer::factory()->create()->id,
            'vehicle_id' => $vehicle->id,
            'source_id' => ReservationSource::where('slug', 'website')->value('id'),
            'status_id' => ReservationStatus::where('slug', $statusSlug)->value('id'),
            'payment_status_id' => PaymentStatus::where('slug', 'unpaid')->value('id'),
            'pickup_location_id' => $pickupLocation->id,
            'dropoff_location_id' => $dropoffLocation->id,
            'start_datetime' => $startAt,
            'end_datetime' => $endAt,
            'total_days' => 3,
            'price_per_day' => 300,
            'delivery_fee' => 150,
            'deposit_amount' => 1000,
            'total_price' => 2050,
        ]);
    }

}
