<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => array_values(array_filter(array_merge([
        'http://localhost:3000',
        'http://127.0.0.1:3000',
        'http://localhost:3001',
        'http://127.0.0.1:3001',
        'http://192.168.1.4:3000',
        'http://192.168.1.4:3001',
        'https://limosudcars.com',
        'https://www.limosudcars.com',
        'https://admin.limosudcars.com',
        'https://portal-9x.limosudcars.com',
        'https://limosud-cars-admin.vercel.app',
        'https://limosud-cars-frontend.vercel.app',
    ], array_filter(explode(',', (string) env('CORS_ALLOWED_ORIGINS', '')))))),

    'allowed_origins_patterns' => [
        '/^https:\/\/portal-[\w-]+\.limosudcars\.com$/',
        '/^https:\/\/limosud-cars[\w-]*\.vercel\.app$/',
        '/^http:\/\/192\.168\.\d{1,3}\.\d{1,3}:(3000|3001)$/',
    ],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 86400,

    'supports_credentials' => true,

];
