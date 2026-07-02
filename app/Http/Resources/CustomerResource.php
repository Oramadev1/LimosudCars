<?php

namespace App\Http\Resources;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Customer
 */
class CustomerResource extends JsonResource
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
            'full_name' => $this->full_name,
            'nationality' => $this->nationality,
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
            'foreign_address' => $this->foreign_address,
            'passport_or_cin' => $this->passport_or_cin,
            'passport_or_cin_issued_at' => $this->passport_or_cin_issued_at,
            'driving_license_number' => $this->driving_license_number,
            'driving_license_issued_at' => $this->driving_license_issued_at,
            'driving_license_expires_at' => $this->driving_license_expires_at,
            'driving_license_country' => $this->driving_license_country,
            'reservations_count' => $this->when(isset($this->reservations_count), $this->reservations_count),
            'documents' => CustomerDocumentResource::collection($this->whenLoaded('documents')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
