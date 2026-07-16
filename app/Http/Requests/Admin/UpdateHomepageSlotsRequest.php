<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdateHomepageSlotsRequest extends FormRequest
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
            'vehicle_ids' => ['required', 'array', 'size:6'],
            'vehicle_ids.*' => ['nullable', 'integer', 'exists:vehicles,id'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $vehicleIds = array_values(array_filter(
                $this->input('vehicle_ids', []),
                fn (mixed $id): bool => $id !== null && $id !== ''
            ));

            if (count($vehicleIds) !== count(array_unique($vehicleIds))) {
                $validator->errors()->add('vehicle_ids', 'Each homepage slot must use a different vehicle.');
            }
        });
    }
}
