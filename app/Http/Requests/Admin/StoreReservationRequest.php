<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreReservationRequest extends FormRequest
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
            'customer_id' => ['required', 'integer', 'exists:customers,id'],
            'vehicle_id' => ['required', 'integer', 'exists:vehicles,id'],
            'pickup_location_id' => ['required', 'integer', 'exists:locations,id'],
            'dropoff_location_id' => ['required', 'integer', 'exists:locations,id'],
            'start_datetime' => ['required', 'date'],
            'end_datetime' => ['required', 'date', 'after:start_datetime'],
            'customer_notes' => ['nullable', 'string'],
            'admin_notes' => ['nullable', 'string'],
        ];
    }
}
