<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePaymentRequest extends FormRequest
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
            'reservation_id' => ['sometimes', 'integer', 'exists:reservations,id'],
            'payment_method_slug' => ['sometimes', 'string', 'exists:payment_methods,slug'],
            'payment_type_slug' => ['sometimes', 'string', 'exists:payment_types,slug'],
            'payment_status_slug' => ['sometimes', 'string', 'exists:payment_statuses,slug'],
            'amount' => ['sometimes', 'numeric', 'min:0'],
            'payment_date' => ['sometimes', 'date'],
            'paid_by_customer_name' => ['nullable', 'string', 'max:255'],
            'reference' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
