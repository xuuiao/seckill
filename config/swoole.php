<?php

$log = storage_path('logs/swoole.log');

return [
    'tcp' => [
        'server' => [
            'host' => '127.0.0.1',
            'port' => 9502,
            'mode' => SWOOLE_PROCESS,
            'sock_type' => SWOOLE_SOCK_TCP,
            'set' => [
                'worker_num' => 3,
                'task_worker_num' => 3,
                'log_file' => $log
            ]
        ]
    ]
];
