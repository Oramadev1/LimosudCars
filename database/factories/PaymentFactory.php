<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\PaymentStatus;
use App\Models\PaymentType;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'reservation_id' => Reservation::factory(),
            'payment_method_id' => PaymentMethod::where('slug', 'cash')->value('id'),
            'payment_type_id' => PaymentType::where('slug', 'rental_payment')->value('id'),
            'payment_status_id' => PaymentStatus::where('slug', 'paid')->value('id'),
            'amount' => fake()->randomFloat(2, 100, 5000),
            'payment_date' => now()->toDateString(),
            'paid_by_customer_name' => fake()->optional()->name(),
            'reference' => fake()->optional()->bothify('PAY-####'),
            'notes' => fake()->optional()->sentence(),
            'created_by' => User::factory(),
        ];
    }
}
