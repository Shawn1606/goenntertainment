<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Gilt für die API-Routen. Die Expo-App nutzt Token-Auth (kein Cookie),
    | daher ist supports_credentials = false und alle Origins sind erlaubt.
    | Für einen Web-Build der App kann man die Origins in FRONTEND_URL
    | eingrenzen (kommagetrennt).
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => array_filter(explode(',', (string) env('FRONTEND_URL', '*'))),

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,

];
