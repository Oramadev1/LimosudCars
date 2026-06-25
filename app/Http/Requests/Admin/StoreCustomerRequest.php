<?php

namespace App\Http\Requests\Admin;

use App\Support\IdentityDocument;
use App\Support\PhoneNumber;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('phone')) {
            $this->merge([
                'phone_normalized' => PhoneNumber::normalize((string) $this->input('phone')),
            ]);
        }

        if ($this->filled('passport_or_cin')) {
            $this->merge([
                'passport_or_cin_normalized' => IdentityDocument::normalize((string) $this->input('passport_or_cin')),
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
            'full_name' => ['required', 'string', 'max:255'],
            'nationality' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'phone_normalized' => [
                'required',
                'string',
                Rule::unique('customers', 'phone_normalized')->whereNull('deleted_at'),
            ],
            'email' => ['nullable', 'email', 'max:255'],
            'passport_or_cin' => ['nullable', 'string', 'max:255'],
            'passport_or_cin_normalized' => [
                'nullable',
                'string',
                Rule::unique('customers', 'passport_or_cin_normalized')->whereNull('deleted_at'),
            ],
            'driving_license_number' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'phone_normalized.unique' => 'A customer with this phone number already exists.',
            'passport_or_cin_normalized.unique' => 'A customer with this passport or CIN already exists.',
        ];
    }
}
