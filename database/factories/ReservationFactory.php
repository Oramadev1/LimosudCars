<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Location;
use App\Models\PaymentStatus;
use App\Models\Reservation;
use App\Models\ReservationSource;
use App\Models\ReservationStatus;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Reservation>
 */
class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startAt = now()->addDays(10)->setTime(10, 0);
        $endAt = $startAt->copy()->addDays(3);

        return [
            'reservation_number' => 'RSV-'.now()->format('Ymd').'-'.fake()->unique()->numerify('####'),
            'customer_id' => Customer::factory(),
            'vehicle_id' => Vehicle::factory(),
            'source_id' => ReservationSource::where('slug', 'website')->value('id'),
            'status_id' => ReservationStatus::where('slug', 'pending')->value('id'),
            'payment_status_id' => PaymentStatus::where('slug', 'unpaid')->value('id'),
            'pickup_location_id' => Location::factory(),
            'dropoff_location_id' => Location::factory(),
            'start_datetime' => $startAt,
            'end_datetime' => $endAt,
            'total_days' => 3,
            'price_per_day' => 300,
            'delivery_fee' => 0,
            'deposit_amount' => 1000,
            'total_price' => 1900,
            'customer_notes' => null,
            'admin_notes' => null,
            'created_by' => User::factory(),
        ];
    }
}
