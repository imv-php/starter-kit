<?php

return [
    'base_url' => env('GATEWAY_BASE_URL', ''),
    'username' => env('GATEWAY_USERNAME', ''),
    'password' => env('GATEWAY_PASSWORD', ''),

    'cache_key' => env('GATEWAY_CACHE_KEY', 'gateway_tokens'),

    'connect_timeout' => env('GATEWAY_CONNECT_TIMEOUT', 15),
    'request_timeout' => env('GATEWAY_REQUEST_TIMEOUT', 30),
];
