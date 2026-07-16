<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Concerns\SanitizesInput;
use App\Support\ValidationRules;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreVehicleAvailabilityHoldRequest extends FormRequest
{
    use SanitizesInput;

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge($this->sanitizePlainTextFields($this->all(), [
            'customer_name' => 255,
            'phone' => 50,
        ]));

        if ($this->has('note')) {
            $this->merge([
                'note' => $this->sanitizeMultilineText($this->input('note'), 2000),
            ]);
        }
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'starts_at' => ['required', 'date'],
            'ends_at' => ['required', 'date', 'after:starts_at'],
            'customer_name' => ['required', 'string', 'max:255', ValidationRules::PERSON_NAME],
            'phone' => ['nullable', 'string', 'max:50', ValidationRules::PHONE],
            'note' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
