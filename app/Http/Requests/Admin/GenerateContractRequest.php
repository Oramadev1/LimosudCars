<?php

namespace App\Http\Requests\Admin;

use App\Support\ContractPaymentMethods;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GenerateContractRequest extends FormRequest
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
            'contract_series' => ['nullable', 'string', 'max:8'],
            'details' => ['nullable', 'array'],
            'details.customer' => ['nullable', 'array'],
            'details.customer.passport_or_cin' => ['nullable', 'string', 'max:255'],
            'details.customer.driving_license_number' => ['nullable', 'string', 'max:255'],
            'details.customer.address' => ['nullable', 'string', 'max:255'],
            'details.customer.foreign_address' => ['nullable', 'string', 'max:255'],
            'details.customer.license_issued_at' => ['nullable', 'date'],
            'details.customer.license_expires_at' => ['nullable', 'date'],
            'details.customer.license_country' => ['nullable', 'string', 'max:255'],
            'details.customer.passport_or_cin_issued_at' => ['nullable', 'date'],
            'details.vehicle' => ['nullable', 'array'],
            'details.vehicle.vin' => ['nullable', 'string', 'max:255'],
            'details.vehicle.color' => ['nullable', 'string', 'max:255'],
            'details.vehicle.fuel_level' => ['nullable', 'string', 'max:255'],
            'details.vehicle.mileage' => ['nullable', 'integer', 'min:0'],
            'details.additional_driver' => ['nullable', 'array'],
            'details.additional_driver.enabled' => ['nullable', 'boolean'],
            'details.additional_driver.full_name' => ['nullable', 'string', 'max:255'],
            'details.additional_driver.address' => ['nullable', 'string', 'max:255'],
            'details.additional_driver.passport_or_cin' => ['nullable', 'string', 'max:255'],
            'details.additional_driver.driving_license_number' => ['nullable', 'string', 'max:255'],
            'details.additional_driver.license_issued_at' => ['nullable', 'date'],
            'details.additional_driver.license_expires_at' => ['nullable', 'date'],
            'details.additional_driver.nationality' => ['nullable', 'string', 'max:255'],
            'details.additional_driver.phone' => ['nullable', 'string', 'max:255'],
            'details.equipment' => ['nullable', 'array'],
            'details.documents' => ['nullable', 'array'],
            'details.condition' => ['nullable', 'array'],
            'details.rental' => ['nullable', 'array'],
            'details.rental.dropoff_datetime' => ['nullable', 'date'],
            'details.rental.total_days' => ['nullable', 'integer', 'min:3'],
            'details.rental.extension' => ['nullable', 'string', 'max:255'],
            'details.rental.extension_total' => ['nullable', 'string', 'max:255'],
            'details.rental.actual_return_date' => ['nullable', 'date'],
            'details.rental.actual_return_time' => ['nullable', 'string', 'max:32'],
            'details.payment' => ['nullable', 'array'],
            'details.payment.discount' => ['nullable', 'numeric', 'min:0'],
            'details.payment.additional_fees' => ['nullable', 'numeric', 'min:0'],
            'details.payment.late_return_fees' => ['nullable', 'numeric', 'min:0'],
            'details.payment.fuel_charges' => ['nullable', 'numeric', 'min:0'],
            'details.payment.cleaning_charges' => ['nullable', 'numeric', 'min:0'],
            'details.payment.damage_charges' => ['nullable', 'numeric', 'min:0'],
            'details.payment.tax' => ['nullable', 'numeric', 'min:0'],
            'details.payment.scheduled_payment_date' => ['nullable', 'date'],
            'details.payment.payment_method_slug' => ['nullable', 'string', Rule::in(ContractPaymentMethods::slugs())],
            'details.insurance' => ['nullable', 'array'],
            'details.insurance.type' => ['nullable', Rule::in(['basic', 'premium'])],
            'details.insurance.deductible' => ['nullable', 'integer', 'min:0'],
            'details.special_authorization' => ['nullable', 'array'],
            'details.special_authorization.leave_urban_area' => ['nullable', 'boolean'],
            'details.persist_customer' => ['nullable', 'boolean'],
            'details.persist_vehicle' => ['nullable', 'boolean'],
        ];
    }
}
