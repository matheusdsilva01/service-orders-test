<?php

return [
    'app' => [
        'timezone' => env('APP_TIMEZONE', 'America/Sao_Paulo'),
    ],
    'database' => [
        'host' => env('DB_HOST', 'localhost'),
        'port' => (int)env('DB_PORT', 3306),
        'dbname' => env('DB_NAME', 'service_orders'),
        'charset' => env('DB_CHARSET', 'utf8mb4'),
        'username' => env('DB_USERNAME', 'root'),
        'password' => env('DB_PASSWORD', ''),
        'timezone' => env('DB_TIMEZONE', '-03:00'),
    ],
    'mail' => [
        'host' => env('MAIL_HOST', '127.0.0.1'),
        'port' => (int)env('MAIL_PORT', 1025),
        'from_address' => env('MAIL_FROM_ADDRESS', 'no-reply@service-orders.com'),
        'from_name' => env('MAIL_FROM_NAME', 'Service Orders'),
    ],
];
