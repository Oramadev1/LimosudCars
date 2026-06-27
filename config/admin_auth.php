<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Admin JWT cookie (HttpOnly)
    |--------------------------------------------------------------------------
    |
    | Local (.env): ADMIN_COOKIE_SECURE=false, ADMIN_COOKIE_SAME_SITE=lax, no domain
    | Production:    ADMIN_COOKIE_SECURE=true, ADMIN_COOKIE_SAME_SITE=none,
    |                ADMIN_COOKIE_DOMAIN=.limosudcars.com (portal → api CORS)
    |
    */

    'cookie_domain' => env('ADMIN_COOKIE_DOMAIN'),

    'cookie_same_site' => env(
        'ADMIN_COOKIE_SAME_SITE',
        env('APP_ENV') === 'production' ? 'none' : 'lax',
    ),

    'cookie_secure' => filter_var(
        env('ADMIN_COOKIE_SECURE', env('APP_ENV') === 'production'),
        FILTER_VALIDATE_BOOL,
    ),

];
