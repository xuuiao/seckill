<?php
/**
 * Created by PhpStorm.
 * User: simple
 * Date: 2018/12/24
 * Time: 10:55 AM
 */

namespace App\Console\Commands\Swoole;

use Illuminate\Console\Command;
use Swoole;

class Server extends Command
{
    /**
     * @var string
     */
    protected $signature = 'swoole:server {cmd}';

    /**
     * @var string
     */
    protected $description = 'Swoole TCP服务端';

    protected $cmd = null;

    protected $masterPid = null;

    /**
     * @author   陈朔  chenshuo@vchangyi.com
     * @date     2018/12/24 11:35 AM
     */
    public function handle()
    {
        $this->cmd = $this->argument('cmd');

        if ($this->cmd === 'stop') {
            echo $this->masterPid;
            // shell_exec('kill -9 '.$this->masterPid);
            return true;
        }

        if (empty($config = config('swoole.tcp.server'))) {
            $this->error('Swoole 配置文件错误');
            return false;
        }

        $host = $config['host'];
        $port = $config['port'];
        $mode = $config['mode'];
        $sockType = $config['sock_type'];

        $server = new Swoole\Server($host, $port, $mode, $sockType);

        $server->set($config['set']);

        $server->on('start', [$this, 'start']);
        $server->on('connect', [$this, 'connect']);
        $server->on('receive', [$this, 'receive']);
        $server->on('workerStart', [$this, 'workerStart']);
        $server->on('workerStop', [$this, 'workerStop']);
        $server->on('task', [$this, 'task']);
        $server->on('finish', [$this, 'finish']);
        $server->on('close', [$this, 'close']);

        if ($this->cmd === 'start') {
            // 启动Swoole
            if (!$server->start()) {
                $this->error('start error');
                return false;
            }
        }

        return true;
    }

    public function start(Swoole\Server $server)
    {
        $this->info('start swoole successful ... '.SWOOLE_VERSION);
        $this->info('master process id: ' . $server->master_pid);
        $this->info('manager process id: ' . $server->manager_pid);
    }

    public function workerStart(Swoole\Server $server, $workId)
    {
        $this->info('[' . $workId . '] worker process id: ' . $server->worker_pid);
    }

    public function workerStop(Swoole\Server $server)
    {
        $this->info('worker stop');
    }

    public function connect(Swoole\Server $server)
    {
        echo 'connect' . PHP_EOL;
    }

    public function receive(Swoole\Server $server, $fd, $fromId, $data)
    {
        $server->reload();

        echo 'receive...' . PHP_EOL;
        echo 'fd:' . $fd . PHP_EOL;
        echo 'fromId:' . $fromId . PHP_EOL;
        echo 'data:'. $data . PHP_EOL;
        $taskId = $server->task($data);
        echo 'taskId:' . $taskId . PHP_EOL;
    }

    public function close(Swoole\Server $server, $fd, $fromId)
    {
        echo 'close fd:' . $fd . PHP_EOL;
    }

    public function task(Swoole\Server $server, $taskId, $fromId, $data)
    {
        echo 'task...' . PHP_EOL;
        echo 'taskId:' . $taskId . PHP_EOL;
        echo 'fromId:' . $fromId . PHP_EOL;
        echo 'data:'. $data . PHP_EOL;

        sleep(5);
        echo PHP_EOL;
        $server->finish($data);
    }

    public function finish(Swoole\Server $server, $taskId, $data)
    {
        echo 'finish...' . PHP_EOL;
        echo 'taskId:'. $taskId . PHP_EOL;
        echo 'data:'. $data . PHP_EOL;
        echo PHP_EOL;
    }
}
