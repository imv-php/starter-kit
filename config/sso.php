<?php

// config for Imv/Sso
return [
    'base_url' => env('SSO_BASE_URL'),
    'client_id' => env('SSO_CLIENT_ID'),
    'client_secret' => env('SSO_CLIENT_SECRET'),
    'timeout' => [
        'connect' => env('SSO_CONNECT_TIMEOUT', 60),
        'request' => env('SSO_REQUEST_TIMEOUT', 120),
    ],
];
