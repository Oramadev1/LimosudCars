<?php

namespace App\Support;

use Symfony\Component\HttpFoundation\Cookie;

class AdminAuthCookie
{
    public const NAME = 'limosud_admin_token';

    public static function attach(string $token): Cookie
    {
        $minutes = (int) config('jwt.ttl', 1440);

        return cookie(
            self::NAME,
            $token,
            $minutes,
            '/',
            config('admin_auth.cookie_domain'),
            (bool) config('admin_auth.cookie_secure'),
            true,
            false,
            config('admin_auth.cookie_same_site', 'none'),
        );
    }

    public static function forget(): Cookie
    {
        return cookie()->forget(
            self::NAME,
            '/',
            config('admin_auth.cookie_domain'),
        );
    }
}
