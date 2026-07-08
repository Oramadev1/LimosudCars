<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreVehicleRequest extends FormRequest
{
    use GeneratesVehicleSlug;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => $this->uniqueVehicleSlug((string) $this->input('name', 'vehicle')),
            'year' => (int) date('Y'),
            'mileage' => 0,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'brand_id' => ['required', 'integer', 'exists:vehicle_brands,id'],
            'category_id' => ['required', 'integer', 'exists:vehicle_categories,id'],
            'status_slug' => ['required', 'string', 'exists:vehicle_statuses,slug'],
            'transmission_type_slug' => ['required', 'string', 'exists:transmission_types,slug'],
            'fuel_type_slug' => ['required', 'string', 'exists:fuel_types,slug'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', 'unique:vehicles,slug'],
            'model' => ['required', 'string', 'max:255'],
            'year' => ['required', 'integer', 'min:1900', 'max:'.(((int) date('Y')) + 1)],
            'plate_number' => ['required', 'string', 'max:255', 'unique:vehicles,plate_number'],
            'mileage' => ['required', 'integer', 'min:0'],
            'seats' => ['required', 'integer', 'min:1', 'max:99'],
            'doors' => ['required', 'integer', 'min:1', 'max:20'],
            'daily_price' => ['required', 'numeric', 'min:0'],
            'weekly_price' => ['nullable', 'numeric', 'min:0'],
            'monthly_price' => ['nullable', 'numeric', 'min:0'],
            'deposit_amount' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'is_featured' => ['sometimes', 'boolean'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
