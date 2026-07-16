<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Concerns\SanitizesInput;
use App\Support\IdentityDocument;
use App\Support\PhoneNumber;
use App\Support\ValidationRules;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
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
        } elseif ($this->has('passport_or_cin')) {
            $this->merge([
                'passport_or_cin_normalized' => null,
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
            'full_name' => ['sometimes', 'string', 'max:255', ValidationRules::PERSON_NAME],
            'nationality' => ['sometimes', 'string', 'max:255', ValidationRules::PERSON_NAME],
            'phone' => ['sometimes', 'string', 'max:20', ValidationRules::PHONE],
            'phone_normalized' => [
                'required_with:phone',
                'string',
            ],
            'email' => ['nullable', 'email', 'max:255'],
            'passport_or_cin' => ['nullable', 'string', 'max:255'],
            'passport_or_cin_normalized' => [
                'nullable',
                'string',
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
        return [];
    }
}
