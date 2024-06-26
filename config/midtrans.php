<?php

return [
    'client_development_key' => env('MIDTRANS_DEVELOPMENT_CLIENT_KEY'),
    'server_development_key' => env('MIDTRANS_DEVELOPMENT_SERVER_KEY'),
    'client_production_key' => env('MIDTRANS_PRODUCTION_CLIENT_KEY'),
    'server_production_key' => env('MIDTRANS_PRODUCTION_SERVER_KEY'),
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
    'is_sanitized' => env('MIDTRANS_IS_SANITIZED', true),
    'is_3ds' => env('MIDTRANS_IS_3DS', true),
];
