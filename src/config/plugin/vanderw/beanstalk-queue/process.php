<?php

return [
    'consumer'  => [
        'handler'     => Vanderw\BeanstalkQueue\Process\Consumer::class,
        'count'       => 1, // 可以设置多进程同时消费
        'constructor' => [
            // 消费者类目录
            'consumer_dir' => app_path() . '/queue/beanstalk'
        ]
    ]
];