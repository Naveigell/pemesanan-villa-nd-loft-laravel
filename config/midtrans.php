<?php

return [
    'client_development_key' => env('MIDTRANS_DEVELOPMENT_CLIENT_KEY'),
    'server_development_key' => env('MIDTRANS_DEVELOPMENT_SERVER_KEY'),
    'client_production_key' => env('MIDTRANS_PRODUCTION_CLIENT_KEY'),
    'server_production_key' => env('MIDTRANS_PRODUCTION_SERVER_KEY'),
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
    'is_sanitized' => env('MIDTRANS_IS_SANITIZED', true),
    'is_3ds' => env('MIDTRANS_IS_3DS', true),

    'development_redirect_url' => env('MIDTRANS_DEVELOPMENT_REDIRECT_URL', 'https://app.sandbox.midtrans.com/snap/v4/redirection/:token'),
    'production_redirect_url' => env('MIDTRANS_PRODUCTION_REDIRECT_URL', 'https://app.midtrans.com/snap/v4/redirection/:token'),
];
