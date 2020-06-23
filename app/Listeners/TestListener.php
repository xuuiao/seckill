<?php
/**
 * Created by PhpStorm.
 * User: simple
 * Date: 2018/11/29
 * Time: 3:22 PM
 */

namespace App\Listeners;


use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class TestListener implements ShouldQueue
{
    /**
     * 任务应该发送到的队列的连接的名称
     *
     * @var string|null
     */
    public $connection = 'redis';

    /**
     * 任务应该发送到的队列的名称
     *
     * @var string|null
     */
    public $queue = 'listeners';

    public function __construct()
    {
        // ...
    }

    public function handle()
    {
        sleep(5);

        Log::info('test');

        echo 'test'.PHP_EOL;
    }
}
