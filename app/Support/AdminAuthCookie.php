<?php

namespace App\Support;

use Symfony\Component\HttpFoundation\Cookie;

class AdminAuthCookie
{
    public static function name(): string
    {
        return (string) config('jwt.cookie_key_name', 'limosud_admin_token');
    }

    public static function attach(string $token): Cookie
    {
        $minutes = (int) config('jwt.ttl', 1440);

        return cookie(
            self::name(),
            $token,
            $minutes,
            '/',
            config('admin_auth.cookie_domain'),
            (bool) config('admin_auth.cookie_secure'),
            true,
            false,
            config('admin_auth.cookie_same_site', 'lax'),
        );
    }

    public static function forget(): Cookie
    {
        return cookie()->forget(
            self::name(),
            '/',
            config('admin_auth.cookie_domain'),
        );
    }
}
