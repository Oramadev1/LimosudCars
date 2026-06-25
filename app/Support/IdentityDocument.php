<?php

namespace App\Support;

class IdentityDocument
{
    public static function normalize(?string $value): string
    {
        if ($value === null) {
            return '';
        }

        $normalized = strtoupper(trim($value));

        if ($normalized === '') {
            return '';
        }

        return preg_replace('/[\s\-.]+/', '', $normalized) ?? '';
    }
}
