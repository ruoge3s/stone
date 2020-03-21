<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */

use Hyperf\Server\Server;
use Hyperf\Server\SwooleEvent;

return [
    'mode' => SWOOLE_PROCESS,
    'servers' => [
        [
            'name' => 'http',
            'type' => Server::SERVER_HTTP,
            'host' => '0.0.0.0',
            'port' => 9501,
            'sock_type' => SWOOLE_SOCK_TCP,
            'callbacks' => [
                SwooleEvent::ON_REQUEST => [Hyperf\HttpServer\Server::class, 'onRequest'],
            ],
        ],
        [
            'name'      => 'jsonrpc-test',
            'type'      => Server::SERVER_BASE,
            'host'      => '0.0.0.0',
            'port'      => 9504,
            'sock_type' => SWOOLE_SOCK_TCP,
            'callbacks' => [
                SwooleEvent::ON_RECEIVE => [Hyperf\JsonRpc\TcpServer::class, 'onReceive'],
            ],
            'settings'  => [
                'open_eof_split'        => true,
                'package_eof'           => "\r\n",
                'package_max_length'    => 1024 * 1024 * 2,
            ],
        ],
    ],
    'settings' => [
        'enable_coroutine'      => true,
        'worker_num'            => swoole_cpu_num(),
        'pid_file'              => BASE_PATH . '/runtime/hyperf.pid',
        'open_tcp_nodelay'      => true,
        'max_coroutine'         => 100000,
        'open_http2_protocol'   => true,
        'max_request'           => 100000,
        'socket_buffer_size'    => 2 * 1024 * 1024,
    ],
    'callbacks' => [
        SwooleEvent::ON_BEFORE_START => [Hyperf\Framework\Bootstrap\ServerStartCallback::class, 'beforeStart'],
        SwooleEvent::ON_WORKER_START => [Hyperf\Framework\Bootstrap\WorkerStartCallback::class, 'onWorkerStart'],
        SwooleEvent::ON_PIPE_MESSAGE => [Hyperf\Framework\Bootstrap\PipeMessageCallback::class, 'onPipeMessage'],
    ],
];
