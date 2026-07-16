<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Concerns\SanitizesInput;
use App\Support\ValidationRules;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateVehicleAvailabilityHoldRequest extends FormRequest
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
            'starts_at' => ['sometimes', 'date'],
            'ends_at' => ['sometimes', 'date'],
            'customer_name' => ['sometimes', 'string', 'max:255', ValidationRules::PERSON_NAME],
            'phone' => ['nullable', 'string', 'max:50', ValidationRules::PHONE],
            'note' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            $startsAt = $this->input('starts_at');
            $endsAt = $this->input('ends_at');

            if ($startsAt && $endsAt && strtotime((string) $endsAt) <= strtotime((string) $startsAt)) {
                $validator->errors()->add('ends_at', 'The end date must be after the start date.');
            }
        });
    }
}
