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
            'cost' => ['nullable', 'numeric', 'min:0'],
            'garage_name' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'vehicle_status_slug' => ['nullable', 'string', 'exists:vehicle_statuses,slug'],
            'create_expense' => ['sometimes', 'boolean'],
            'expense_category_slug' => ['required_if:create_expense,true', 'nullable', 'string', 'exists:expense_categories,slug'],
        ];
    }
}
