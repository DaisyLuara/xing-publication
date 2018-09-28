<?php

use Qbhy\LaravelApiAuth\Middleware;

return [
    'status' => Middleware::STATUS_ON, // 状态，LaravelApiAuth::STATUS_ON  或者 LaravelApiAuth::STATUS_OFF

    'roles' => [
        'x0OutMFdnkjzFwRa7flNs0lhCL9Ri1dp' => [
            'name' => 'h5',        // 角色名字，例如 android
            'secret_key' => 'QAtdFu1yazOfv7c2a5e1GZGSHVruVZ4l',
        ],
    ],

    'signature_methods' => [
        'md5' => \Qbhy\LaravelApiAuth\Signatures\Md5::class,
    ],

    'skip' => [
        'is' => [Middleware::class, 'default_excludes_handler'],
        'urls' => [],
    ],

    'timeout' => 60, // 签名失效时间，单位: 秒
];