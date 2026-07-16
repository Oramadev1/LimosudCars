<?php

namespace App\Http\Requests\Admin;

use App\Models\Vehicle;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVehicleRequest extends FormRequest
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
        $vehicleId = $this->vehicleId();

        return [
            'brand_id' => ['sometimes', 'integer', 'exists:vehicle_brands,id'],
            'category_id' => ['sometimes', 'integer', 'exists:vehicle_categories,id'],
            'status_slug' => ['sometimes', 'string', 'exists:vehicle_statuses,slug'],
            'transmission_type_slug' => ['sometimes', 'string', 'exists:transmission_types,slug'],
            'fuel_type_slug' => ['sometimes', 'string', 'exists:fuel_types,slug'],
            'name' => ['sometimes', 'string', 'max:255'],
            'model' => ['sometimes', 'string', 'max:255'],
            'plate_number' => ['sometimes', 'string', 'max:255', Rule::unique('vehicles', 'plate_number')->ignore($vehicleId)],
            'seats' => ['sometimes', 'integer', 'min:1', 'max:99'],
            'doors' => ['sometimes', 'integer', 'min:1', 'max:20'],
            'daily_price' => ['sometimes', 'numeric', 'min:0'],
            'weekly_price' => ['nullable', 'numeric', 'min:0'],
            'monthly_price' => ['nullable', 'numeric', 'min:0'],
            'deposit_amount' => ['sometimes', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'is_featured' => ['sometimes', 'boolean'],
            'homepage_rank' => ['nullable', 'integer', 'min:1', 'max:6'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    private function vehicleId(): mixed
    {
        $vehicle = $this->route('vehicle');

        return $vehicle instanceof Vehicle ? $vehicle->getKey() : $vehicle;
    }
}
