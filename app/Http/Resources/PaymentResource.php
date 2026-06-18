<?php

namespace App\Http\Resources;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\MissingValue;

/**
 * @mixin Payment
 */
class PaymentResource extends JsonResource
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
            'reservation' => $this->whenLoaded('reservation', fn (): array => [
                'id' => $this->reservation->id,
                'reservation_number' => $this->reservation->reservation_number,
                'total_price' => $this->reservation->total_price,
            ]),
            'payment_method' => $this->lookup('paymentMethod'),
            'payment_type' => $this->lookup('paymentType'),
            'payment_status' => $this->lookup('paymentStatus'),
            'amount' => $this->amount,
            'payment_date' => $this->payment_date,
            'paid_by_customer_name' => $this->paid_by_customer_name,
            'reference' => $this->reference,
            'notes' => $this->notes,
            'created_by' => $this->whenLoaded('createdBy', fn (): ?array => $this->createdBy ? [
                'id' => $this->createdBy->id,
                'name' => $this->createdBy->name,
                'email' => $this->createdBy->email,
            ] : null),
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
