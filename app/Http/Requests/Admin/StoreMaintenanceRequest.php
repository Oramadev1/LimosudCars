<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreMaintenanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $vehicleId = $this->input('vehicle_id');

        if ($vehicleId === 0 || $vehicleId === '0' || $vehicleId === '') {
            $this->merge(['vehicle_id' => null]);
        }

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
            'vehicle_id' => ['required', 'integer', 'exists:vehicles,id'],
            'maintenance_type_slug' => ['required', 'string', 'exists:maintenance_types,slug'],
            'maintenance_date' => ['required', 'date'],
            'next_maintenance_date' => ['nullable', 'date', 'after_or_equal:maintenance_date'],
            'mileage' => ['nullable', 'integer', 'min:0'],
            'cost' => ['required', 'numeric', 'gt:0'],
            'garage_name' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'vehicle_status_slug' => ['nullable', 'string', 'exists:vehicle_statuses,slug'],
            'create_expense' => ['sometimes', 'boolean'],
            'expense_category_slug' => ['required_if:create_expense,true', 'nullable', 'string', 'exists:expense_categories,slug'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'vehicle_id.required' => 'Please select a vehicle.',
            'vehicle_id.exists' => 'The selected vehicle is invalid.',
            'maintenance_type_slug.required' => 'Please select a maintenance type.',
            'maintenance_type_slug.exists' => 'The selected maintenance type is invalid.',
            'maintenance_date.required' => 'Please enter the maintenance date.',
            'maintenance_date.date' => 'The maintenance date must be a valid date.',
            'next_maintenance_date.after_or_equal' => 'The next maintenance date must be on or after the maintenance date.',
            'cost.required' => 'Please enter the maintenance cost.',
            'cost.gt' => 'The maintenance cost must be greater than zero.',
            'expense_category_slug.required_if' => 'Please select an expense category when recording a cost.',
            'expense_category_slug.exists' => 'The selected expense category is invalid.',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'vehicle_id' => 'vehicle',
            'maintenance_type_slug' => 'maintenance type',
            'maintenance_date' => 'maintenance date',
            'next_maintenance_date' => 'next maintenance date',
            'cost' => 'cost',
            'garage_name' => 'garage name',
            'expense_category_slug' => 'expense category',
        ];
    }
}
