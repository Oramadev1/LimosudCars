<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContractSetting extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'company_name',
        'company_legal_name',
        'company_tagline',
        'company_address',
        'company_email',
        'company_phone',
        'company_phone_alt',
        'company_city',
        'stamp_address',
        'stamp_phones',
        'insurance_deductible',
        'terms_fr',
        'terms_ar',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'insurance_deductible' => 'integer',
        ];
    }

    public static function current(): self
    {
        return static::query()->firstOrCreate([], static::defaultsFromConfig());
    }

    /**
     * @return array<string, mixed>
     */
    public static function defaultsFromConfig(): array
    {
        $company = config('limosud.company');

        return [
            'company_name' => $company['name'],
            'company_legal_name' => $company['legal_name'],
            'company_tagline' => $company['tagline'],
            'company_address' => $company['address'],
            'company_email' => $company['email'],
            'company_phone' => $company['phone'],
            'company_phone_alt' => $company['phone_alt'],
            'company_city' => $company['city'],
            'stamp_address' => $company['stamp_address'],
            'stamp_phones' => $company['stamp_phones'],
            'insurance_deductible' => $company['insurance_deductible'],
            'terms_fr' => config('limosud.contract_legal_fr'),
            'terms_ar' => config('limosud.contract_legal_ar'),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toCompanyArray(): array
    {
        return [
            'name' => $this->company_name,
            'legal_name' => $this->company_legal_name,
            'tagline' => $this->company_tagline,
            'address' => $this->company_address,
            'email' => $this->company_email,
            'phone' => $this->company_phone,
            'phone_alt' => $this->company_phone_alt,
            'city' => $this->company_city,
            'stamp_address' => $this->stamp_address,
            'stamp_phones' => $this->stamp_phones,
            'insurance_deductible' => $this->insurance_deductible,
        ];
    }
}
