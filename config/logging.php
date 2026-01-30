<?php

use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogUdpHandler;

return [
    'default' => env('LOG_CHANNEL', 'stack'),

    'channels' => [
        'stack' => [
            'driver' => 'single',
            'path' => __DIR__ . '/../storage/logs/laravel.log',
            'level' => env('LOG_LEVEL', 'debug'),
        ],
        'single' => [
            'driver' => 'single',
            'path' => __DIR__ . '/../storage/logs/laravel.log',
            'level' => env('LOG_LEVEL', 'debug'),
        ],
    ],
];
