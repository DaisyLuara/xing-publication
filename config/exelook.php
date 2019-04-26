<?php
return [
    'check_close' => false,
    'debug' => env('APP_ENV') !== 'publication',
    'log' => [
        'name' => 'exelook-api',
        'file' => storage_path('/logs/exelook-api.log'),
        'level' => 'debug',
        'permission' => 0777,
    ]
];