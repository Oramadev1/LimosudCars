<?php

namespace App\Support;

class InputSanitizer
{
    public static function plainText(?string $value, int $maxLength = 255): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = strip_tags($value);
        $value = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $value) ?? '';
        $value = trim(preg_replace('/\s+/u', ' ', $value) ?? '');

        if ($value === '') {
            return $value;
        }

        return mb_substr($value, 0, $maxLength);
    }

    public static function email(?string $value, int $maxLength = 255): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = strtolower(trim($value));

        if ($value === '') {
            return $value;
        }

        return mb_substr($value, 0, $maxLength);
    }

    public static function multilineText(?string $value, int $maxLength = 5000): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = strip_tags($value);
        $value = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $value) ?? '';
        $value = trim($value);

        if ($value === '') {
            return $value;
        }

        return mb_substr($value, 0, $maxLength);
    }
}
