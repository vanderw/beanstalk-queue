<?php

return [
    'default' => [
        'ip' => '127.0.0.1',
        'port' => 11300,
        'timeout' => 10, // s
        'options' => [
            'auth' => '123456', // 密码，可选参数
            'delay'  => 2,      // 延遲秒數
            'retry_after' => 5, // 秒後重试
        ]
    ],
];