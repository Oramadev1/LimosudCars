<?php

namespace Database\Factories;

use App\Models\Contract;
use App\Models\ContractStatus;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Contract>
 */
class ContractFactory extends Factory
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
            'status_id' => ContractStatus::where('slug', 'generated')->value('id'),
            'contract_number' => 'CTR-'.now()->format('Ymd').'-'.fake()->unique()->numerify('####'),
            'pdf_path' => null,
            'signed_pdf_path' => null,
            'generated_by' => User::factory(),
            'generated_at' => now(),
            'signed_at' => null,
        ];
    }
}
