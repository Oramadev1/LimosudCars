<?php

namespace App\Support;

class ContractPaymentMethods
{
    /**
     * @return list<string>
     */
    public static function slugs(): array
    {
        return ['cash', 'bank_transfer', 'credit_card'];
    }

    public static function normalize(?string $slug): string
    {
        return match ($slug) {
            'bank_transfer' => 'bank_transfer',
            'credit_card', 'debit_card', 'online' => 'credit_card',
            default => 'cash',
        };
    }

    public static function normalizeInsuranceType(?string $type): string
    {
        return match ($type) {
            'premium', 'full_coverage' => 'premium',
            default => 'basic',
        };
    }
}
