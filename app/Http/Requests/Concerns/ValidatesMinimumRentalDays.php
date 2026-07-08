<?php

namespace App\Http\Requests\Concerns;

use App\Services\ReservationPricingService;
use Illuminate\Contracts\Validation\Validator;
use InvalidArgumentException;

trait ValidatesMinimumRentalDays
{
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $startDatetime = $this->input('start_datetime');
            $endDatetime = $this->input('end_datetime');

            if (! is_string($startDatetime) || ! is_string($endDatetime)) {
                return;
            }

            try {
                $totalDays = app(ReservationPricingService::class)->calculateTotalDays($startDatetime, $endDatetime);
            } catch (InvalidArgumentException) {
                return;
            }

            $minimumDays = (int) config('limosud.min_rental_days', 3);

            if ($totalDays < $minimumDays) {
                $validator->errors()->add(
                    'end_datetime',
                    "The minimum rental period is {$minimumDays} days.",
                );
            }
        });
    }
}
