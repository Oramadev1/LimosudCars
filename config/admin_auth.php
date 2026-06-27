<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Admin JWT cookie (cross-origin CORS)
    |--------------------------------------------------------------------------
    |
    | When the admin SPA and API are on different origins, the browser only
    | sends cookies if SameSite=None and Secure=true (HTTPS on production).
    |
    */

    'cookie_domain' => env('ADMIN_COOKIE_DOMAIN'),

    'cookie_same_site' => env('ADMIN_COOKIE_SAME_SITE', 'none'),

    'cookie_secure' => env('ADMIN_COOKIE_SECURE', env('APP_ENV') === 'production'),

];
