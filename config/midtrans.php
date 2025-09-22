<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Midtrans Configuration
    |--------------------------------------------------------------------------
    |
    | Server key & Client key didapat dari dashboard Midtrans
    | https://dashboard.midtrans.com/settings/configurations
    |
    */

    'server_key' => env('MIDTRANS_SERVER_KEY', ''),
    'client_key' => env('MIDTRANS_CLIENT_KEY', ''),

    // false = sandbox, true = production
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),

    // Optional: URL redirect/finish/cancel
    'redirect' => env('MIDTRANS_REDIRECT', ''),

    // Optional: enable sanitization
    'is_sanitized' => true,

    // Optional: enable 3D-Secure
    'is_3ds' => true,   
];
