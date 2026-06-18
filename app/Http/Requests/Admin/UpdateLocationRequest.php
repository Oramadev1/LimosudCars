<?php

namespace App\Http\Requests\Admin;

use App\Models\Location;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLocationRequest extends FormRequest
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
        $locationId = $this->locationId();

        return [
            'location_type_slug' => ['sometimes', 'string', 'exists:location_types,slug'],
            'name' => ['sometimes', 'string', 'max:255'],
            'slug' => ['sometimes', 'string', 'max:255', 'alpha_dash', Rule::unique('locations', 'slug')->ignore($locationId)],
            'address' => ['nullable', 'string', 'max:255'],
            'delivery_fee' => ['sometimes', 'numeric', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    private function locationId(): mixed
    {
        $location = $this->route('location');

        return $location instanceof Location ? $location->getKey() : $location;
    }
}
