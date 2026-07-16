<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Concerns\SanitizesInput;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateReservationRequest extends FormRequest
{
    use SanitizesInput;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('customer_notes')) {
            $this->merge([
                'customer_notes' => $this->sanitizeMultilineText($this->input('customer_notes'), 2000),
            ]);
        }

        if ($this->has('admin_notes')) {
            $this->merge([
                'admin_notes' => $this->sanitizeMultilineText($this->input('admin_notes'), 2000),
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customer_id' => ['sometimes', 'integer', 'exists:customers,id'],
            'vehicle_id' => ['sometimes', 'integer', 'exists:vehicles,id'],
            'pickup_location_id' => ['sometimes', 'integer', 'exists:locations,id'],
            'dropoff_location_id' => ['sometimes', 'integer', 'exists:locations,id'],
            'start_datetime' => ['sometimes', 'date'],
            'end_datetime' => ['sometimes', 'date', 'after:start_datetime'],
            'customer_notes' => ['nullable', 'string', 'max:2000'],
            'admin_notes' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
