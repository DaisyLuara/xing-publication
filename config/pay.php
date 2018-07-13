<?php

return [
    'alipay' => [
        // 支付宝分配的 APPID
        'app_id' => '2016091300502243',

        // 支付宝异步通知地址
        'notify_url' => 'http://ad.jingfree.top/api/pay/notify',

        // 支付成功后同步通知地址
        'return_url' => 'http://ad.jingfree.top/api/pay/return',

        // 阿里公共密钥，验证签名时使用
        'ali_public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA2cXlasQvjDEwY4V/qLFCtqytF3QwTe710YOj2340wFIMw7fUU4aiVOBFCnDQHGVhIDVQLoTKZe569YBw1bSeN6VJ8fMOVGfG8CLjo5n31RVbi9PTj45iht1s23Mhy52nl4afQ+D6WVaCw/91RfV17khrO4BuwDtXhQvQgf7kHb9i0mPQjUamedpqafHVh+WReiPhGShc0LlGQrN5aQ4Lt7rF15LDrlBSciS0HhSoRvPphNqG4oYrUfduMJcOptRwzjLYZeJdczcCZJmvfzwhKwalrfN2wyiSHNht5vJDIh42xqGt9T/v1JOhfsuV1Rrapp/93J5YoSaLTQc1jDL2AwIDAQAB',

        // 自己的私钥，签名时使用
        'private_key' => 'MIIEowIBAAKCAQEAk0YmOfYEqwgNHNnT3EXuI5MHyQySkUCUKwkYw285oP41KDgKk3Hki6p6Ws4kupQVa51R/7t2RQJ7OJzzwPyglEmAFSajRkA/Nt0yrFcYIoa9o8MTNwNsPJk4aSkxO1BSmn/qTIHTe0fps1V57ZMhLFiuD4DSjPg9ac0Jg9shneu0E2p0GWe1pMc+BRMxNkfpazvjhKUJfBM8fHlJ6jp7nlAjVEMLSnDP4HdnEeSctX5/O5JJEhM9G6McPQNUsjdNCLqb+jsoq87xUxnJ6NrumaXYZ7EJvBTr8SYsJ2idIhLlXb2nElZZNnYKTLFExXxbRWy3Pzd9SUWvXRpw/lQYfQIDAQABAoIBACTuKFPMf2sr/63GabwIxSiijAc8YdyOpxkLlOwdDgRy+n11YCGNI9RqEXiHzOoEjEKKFq/cIFI5xxBxOyafsty8eaPUJolNPwzQ9w4EYQb3ZsKT/lODRjcD7QNh0viesbgQb35z58Y4SEAyJPsQM/dc3XkBwsqazTuksMghwRJKUWFZEOuo5RX1xKzthx5IuoDdu3sb2Z8otnGnHI4E+THyvfdsvRtfp5sZkBxVJbCRxNUVR9Du7oKRuAilplXEbiPBO2UpKuNJgIUP144O9XMiLcJ9GmvPEev+hgY5DGOOH3Nr3AXsS54C7SdIgVrp1DIYg+dppdioZSjKbTuLXPkCgYEAw6EhOgEL/fxMPbdbqyU1AZB7hYxnDE/Om2sK5Emiq0ydW5rcq0sKCTI/jp27x161icIM6d7k/Ss25p+oB/vekXa3CncikNd4a1AMfDsRNmOfTm+BvPfy2i2z+K1DigbxSfxrlNrP1hG7+2GM+zgYGYiJIkg0+GHWExqN4GEVOAsCgYEAwLjoRMUdfUqo4eqsFtC9xzXgO6+pXlxNw4UN2DqJbrJHk9FI5OcesxKEz3uV//aeQTrt0gCivycIoP//gIA39Yv6y8QDoAQfoaswf7cYZLbjWeMUVjEe9UVy2uRdzFgAAZtHjKi8ypOxMS+IOOy1Di14yGccu2Olms7wimo0XpcCgYBeOCCUmyR3cygVIVZZG1ZoJnoXXAPVfip32Boq0PwznHa9Q9y78ywmObk6quVhpE+yxW8KxHttI38RnBuFnN0ZGiAWPkK7l8s7hBY/TwN/OoPjdvj5VkH0qsZfIrPAqo9TnusBilFHPMtYyybnZ6DTy1qg1D4O0rAUC1oBk0Bw+QKBgQCECqxo4bzQqSSIlOaPQzmjkZAulK4wToGhIjpYGdMrms5bwpp06r0n59pC5ePh2bCX9K4YogLfgPjyhjMicbblb6DHl2b8bZbWh5nVBzeZWYzvexvl6gXR+mE1BB4X8Xsfnqoo/ztKeGKA1pY4mkV6j0Ef3TXcVeuS45H+48E5xQKBgFhNvb6bAG6E3gVByHy/WZlDEwfmgotuMDzg+0nFHiOeCygZPfjOltqMty5TE+moxj7/229dqHAt51LKaJP+THiL4yfCr+UWCPiG7QIR3OLkNx6EFAy/N0e48DudPXFw4b+Zg6uJMmqFLRiFjxVATSSRTPRIvsNROcY9LHrw4f1J',

        // optional，默认 warning；日志路径为：sys_get_temp_dir().'/logs/yansongda.pay.log'
        'log' => [
            'file' => storage_path('logs/alipay.log'),
            //     'level' => 'debug'
        ],

        // optional，设置此参数，将进入沙箱模式
        'mode' => 'dev',
    ],

    'wechat' => [
        // 公众号 APPID
        'app_id' => '',

        // 小程序 APPID
        'miniapp_id' => '',

        // APP 引用的 appid
        'appid' => '',

        // 微信支付分配的微信商户号
        'mch_id' => '',

        // 微信支付异步通知地址
        'notify_url' => '',

        // 微信支付签名秘钥
        'key' => '',

        // 客户端证书路径，退款、红包等需要用到。请填写绝对路径，linux 请确保权限问题。pem 格式。
        'cert_client' => '',

        // 客户端秘钥路径，退款、红包等需要用到。请填写绝对路径，linux 请确保权限问题。pem 格式。
        'cert_key' => '',

        // optional，默认 warning；日志路径为：sys_get_temp_dir().'/logs/yansongda.pay.log'
        'log' => [
            'file' => storage_path('logs/wechat.log'),
            //     'level' => 'debug'
        ],

        // optional
        // 'dev' 时为沙箱模式
        // 'hk' 时为东南亚节点
        // 'mode' => 'dev',
    ],
];
