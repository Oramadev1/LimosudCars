<?php

namespace App\Http\Resources;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\MissingValue;

/**
 * @mixin Reservation
 */
class ReservationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'reservation_number' => $this->reservation_number,
            'customer' => new CustomerResource($this->whenLoaded('customer')),
            'vehicle' => new VehicleResource($this->whenLoaded('vehicle')),
            'source' => $this->lookup('source'),
            'status' => $this->lookup('status'),
            'payment_status' => $this->lookup('paymentStatus'),
            'pickup_location' => new LocationResource($this->whenLoaded('pickupLocation')),
            'dropoff_location' => new LocationResource($this->whenLoaded('dropoffLocation')),
            'start_datetime' => $this->start_datetime,
            'end_datetime' => $this->end_datetime,
            'total_days' => $this->total_days,
            'price_per_day' => $this->price_per_day,
            'delivery_fee' => $this->delivery_fee,
            'deposit_amount' => $this->deposit_amount,
            'total_price' => $this->total_price,
            'customer_notes' => $this->customer_notes,
            'admin_notes' => $this->admin_notes,
            'created_by' => $this->whenLoaded('createdBy', fn (): ?array => $this->createdBy ? [
                'id' => $this->createdBy->id,
                'name' => $this->createdBy->name,
                'email' => $this->createdBy->email,
            ] : null),
            'confirmed_at' => $this->confirmed_at,
            'started_at' => $this->started_at,
            'completed_at' => $this->completed_at,
            'cancelled_at' => $this->cancelled_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    /**
     * @return array{id: int|null, name: string|null, slug: string|null}|MissingValue
     */
    private function lookup(string $relationship): array|MissingValue
    {
        return $this->whenLoaded($relationship, fn (): array => [
            'id' => $this->{$relationship}->id,
            'name' => $this->{$relationship}->name,
            'slug' => $this->{$relationship}->slug,
        ]);
    }
}
