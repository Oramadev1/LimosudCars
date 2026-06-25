<?php

namespace App\Http\Resources;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Reservation
 */
class CustomerReservationSummaryResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'reservation_number' => $this->reservation_number,
            'status' => [
                'id' => $this->status->id,
                'name' => $this->status->name,
                'slug' => $this->status->slug,
            ],
            'payment_status' => [
                'id' => $this->paymentStatus->id,
                'name' => $this->paymentStatus->name,
                'slug' => $this->paymentStatus->slug,
            ],
            'vehicle' => [
                'id' => $this->vehicle->id,
                'name' => $this->vehicle->name,
                'slug' => $this->vehicle->slug,
            ],
            'start_datetime' => $this->start_datetime,
            'end_datetime' => $this->end_datetime,
            'total_days' => $this->total_days,
            'total_price' => $this->total_price,
            'created_at' => $this->created_at,
        ];
    }
}
