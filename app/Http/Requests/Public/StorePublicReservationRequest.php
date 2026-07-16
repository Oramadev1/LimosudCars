<?php

namespace App\Http\Requests\Public;

use App\Http\Requests\Concerns\SanitizesInput;
use App\Http\Requests\Concerns\ValidatesMinimumRentalDays;
use App\Support\ValidationRules;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePublicReservationRequest extends FormRequest
{
    use SanitizesInput;
    use ValidatesMinimumRentalDays;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $customer = $this->input('customer');

        if (is_array($customer)) {
            $customer = $this->sanitizePlainTextFields($customer, [
                'full_name' => 255,
                'nationality' => 255,
                'phone' => 255,
                'passport_or_cin' => 255,
                'driving_license_number' => 255,
            ]);

            if (array_key_exists('email', $customer)) {
                $customer['email'] = $this->sanitizeEmail(
                    is_string($customer['email']) ? $customer['email'] : null,
                );
            }

            $this->merge(['customer' => $customer]);
        }

        if ($this->has('customer_notes')) {
            $this->merge([
                'customer_notes' => $this->sanitizeMultilineText(
                    $this->input('customer_notes'),
                    2000,
                ),
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
            'customer.full_name' => ['required', 'string', 'max:255', ValidationRules::PERSON_NAME],
            'customer.nationality' => ['required', 'string', 'max:255', ValidationRules::PERSON_NAME],
            'customer.phone' => ['required', 'string', 'max:20', ValidationRules::PHONE],
            'customer.email' => ['required', 'email', 'max:255'],
            'customer.passport_or_cin' => ['nullable', 'string', 'max:255'],
            'customer.driving_license_number' => ['nullable', 'string', 'max:255'],
            'vehicle_id' => ['required', 'integer', 'exists:vehicles,id'],
            'pickup_location_id' => ['required', 'integer', 'exists:locations,id'],
            'dropoff_location_id' => ['required', 'integer', 'exists:locations,id'],
            'start_datetime' => ['required', 'date'],
            'end_datetime' => ['required', 'date', 'after:start_datetime'],
            'customer_notes' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
