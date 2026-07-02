<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMaintenanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('cost') && ($this->input('cost') === '' || $this->input('cost') === null)) {
            $this->merge(['cost' => null]);
        }
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'vehicle_id' => ['sometimes', 'integer', 'exists:vehicles,id'],
            'maintenance_type_slug' => ['sometimes', 'string', 'exists:maintenance_types,slug'],
            'maintenance_date' => ['sometimes', 'date'],
            'next_maintenance_date' => ['nullable', 'date'],
            'mileage' => ['nullable', 'integer', 'min:0'],
            'cost' => ['sometimes', 'required', 'numeric', 'gt:0'],
            'garage_name' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'vehicle_status_slug' => ['nullable', 'string', 'exists:vehicle_statuses,slug'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'cost.required' => 'Please enter the maintenance cost.',
            'cost.gt' => 'The maintenance cost must be greater than zero.',
        ];
    }
}
