<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Concerns\SanitizesInput;
use App\Support\IdentityDocument;
use App\Support\PhoneNumber;
use App\Support\ValidationRules;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCustomerRequest extends FormRequest
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
        $this->merge($this->sanitizePlainTextFields($this->all(), [
            'full_name' => 255,
            'nationality' => 255,
            'phone' => 255,
            'passport_or_cin' => 255,
            'driving_license_number' => 255,
            'address' => 255,
            'foreign_address' => 255,
            'driving_license_country' => 255,
        ]));

        if ($this->has('email')) {
            $this->merge([
                'email' => $this->sanitizeEmail($this->input('email')),
            ]);
        }

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
            'full_name' => ['required', 'string', 'max:255', ValidationRules::PERSON_NAME],
            'nationality' => ['required', 'string', 'max:255', ValidationRules::PERSON_NAME],
            'phone' => ['required', 'string', 'max:20', ValidationRules::PHONE],
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
            'address' => ['nullable', 'string', 'max:255'],
            'foreign_address' => ['nullable', 'string', 'max:255'],
            'driving_license_issued_at' => ['nullable', 'date'],
            'driving_license_expires_at' => ['nullable', 'date'],
            'driving_license_country' => ['nullable', 'string', 'max:255'],
            'passport_or_cin_issued_at' => ['nullable', 'date'],
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
