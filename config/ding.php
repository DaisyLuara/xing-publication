<?php

return [

    // 默认发送的机器人

    'default' => [
        // 是否要开启机器人，关闭则不再发送消息
        'enabled' => env('DING_ENABLED',true),
        // 机器人的access_token
        'token' => env('DING_TOKEN','6e55b553c2e4c057f8282bae444164edda697e3249d2b4da8ff0a14685d16ac4'),
        // 钉钉请求的超时时间
        'timeout' => env('DING_TIME_OUT',2.0)
    ],

    'other' => [
        'enabled' => env('OTHER_DING_ENABLED',true),

        'token' => env('OTHER_DING_TOKEN','d536a709b481ea7cf9c2ff2752984646340a5d2dff3ca82d321b755b153f6a1c'),

        'timeout' => env('OTHER_DING_TIME_OUT',2.0)
    ]

];