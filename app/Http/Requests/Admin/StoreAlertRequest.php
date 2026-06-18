<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreAlertRequest extends FormRequest
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
            'vehicle_id' => ['nullable', 'integer', 'exists:vehicles,id'],
            'alert_type_slug' => ['required', 'string', 'exists:alert_types,slug'],
            'alert_status_slug' => ['nullable', 'string', 'exists:alert_statuses,slug'],
            'title' => ['required', 'string', 'max:255'],
            'message' => ['nullable', 'string'],
            'due_date' => ['nullable', 'date'],
        ];
    }
}
