<?php

return [
    'paths' => [
        realpath(__DIR__ . '/../resources/views') ?: __DIR__ . '/../resources/views',
    ],

    'compiled' => env(
        'VIEW_COMPILED_PATH',
        realpath(__DIR__ . '/../storage/framework/views') ?: __DIR__ . '/../storage/framework/views'
    ),
];
