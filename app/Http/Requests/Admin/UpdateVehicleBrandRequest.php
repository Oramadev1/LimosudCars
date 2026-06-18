<?php

namespace App\Http\Requests\Admin;

use App\Models\VehicleBrand;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVehicleBrandRequest extends FormRequest
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
        $brand = $this->route('brand');
        $brandId = $brand instanceof VehicleBrand ? $brand->getKey() : $brand;

        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'slug' => ['sometimes', 'string', 'max:255', 'alpha_dash', Rule::unique('vehicle_brands', 'slug')->ignore($brandId)],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
