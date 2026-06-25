<?php

namespace App\Support;

class PhoneNumber
{
    public static function normalize(string $phone): string
    {
        $digits = preg_replace('/\D+/', '', $phone) ?? '';

        if ($digits === '') {
            return '';
        }

        if (str_starts_with($digits, '00')) {
            $digits = substr($digits, 2);
        }

        if (preg_match('/^0(\d{9})$/', $digits, $matches)) {
            $digits = '212'.$matches[1];
        }

        return $digits;
    }
}
