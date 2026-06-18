<?php

namespace App\Http\Requests\Public;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePublicReservationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customer.full_name' => ['required', 'string', 'max:255'],
            'customer.nationality' => ['required', 'string', 'max:255'],
            'customer.phone' => ['required', 'string', 'max:255'],
            'customer.email' => ['nullable', 'email', 'max:255'],
            'customer.passport_or_cin' => ['nullable', 'string', 'max:255'],
            'customer.driving_license_number' => ['nullable', 'string', 'max:255'],
            'vehicle_id' => ['required', 'integer', 'exists:vehicles,id'],
            'pickup_location_id' => ['required', 'integer', 'exists:locations,id'],
            'dropoff_location_id' => ['required', 'integer', 'exists:locations,id'],
            'start_datetime' => ['required', 'date'],
            'end_datetime' => ['required', 'date', 'after:start_datetime'],
            'customer_notes' => ['nullable', 'string'],
        ];
    }
}
