#!/usr/bin/env php
<?php

// ------------------------------------------------------------------

// swoole tcp server
$config = [
    'host' => '127.0.0.1',
    'port' => 9501,
    'mode' => SWOOLE_PROCESS,
    'sock_type' => SWOOLE_SOCK_TCP,
    'set' => [
        'worker_num' => 4,
        'daemonize' => true,
        'task_worker_num' => 4,
        'pid_file' => __DIR__.'/storage/app/swoole.pid',
        'log_file' => __DIR__.'/storage/logs/swoole.log'
    ]
];

// run
new Command($config);

// ------------------------------------------------------------------

class Command
{

    /**
     * 命令行参数
     *
     * @var array
     */
    protected $argv = [];

    /**
     * 当前执行的命令行
     *
     * @var mixed|null
     */
    protected $command = null;

    /**
     * swoole server 配置文件
     *
     * @var array
     */
    protected $config = [];

    /**
     * swoole server
     *
     * @var null
     */
    protected $server = null;

    /**
     * Command constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->argv = $_SERVER['argv'];

        if (isset($this->argv[1])) {
            $this->command = $this->argv[1];
        }

        if (!method_exists($this, $this->command)) {
            echo 'command not found'.PHP_EOL;
            return false;
        }

        $this->config = $config;
        return call_user_func_array([$this, $this->command], []);
    }

    /**
     * 启动服务
     *
     * @return bool|Server
     * @author   陈朔  chenshuo@vchangyi.com
     * @date     2019/1/7 3:25 PM
     */
    protected function start()
    {
        if ($masterPid = $this->getMasterPid()) {
            echo 'swoole server is running!'.PHP_EOL;
            return false;
        }

        echo 'start ...'.PHP_EOL;

        return $this->server = new Server($this->config);
    }

    /**
     * 重启
     *
     * @author   陈朔  chenshuo@vchangyi.com
     * @date     2019/1/7 3:25 PM
     */
    protected function restart()
    {
        $this->stop();
        sleep(1);
        $this->start();

        echo 'restart ...'.PHP_EOL;
    }

    /**
     * 热重载
     *
     * @return bool
     * @author   陈朔  chenshuo@vchangyi.com
     * @date     2019/1/7 3:25 PM
     */
    protected function reload()
    {
        if (!$masterPid = $this->getMasterPid()) {
            echo 'swoole server not running!'.PHP_EOL;
            return false;
        }

        $cmd = 'kill -USR1 '.$masterPid;
        shell_exec($cmd);
        echo 'swoole server reload successful!'.PHP_EOL;
        return true;
    }

    /**
     * 停止服务
     *
     * @return bool
     * @author   陈朔  chenshuo@vchangyi.com
     * @date     2019/1/7 3:25 PM
     */
    protected function stop()
    {
        if (!$masterPid = $this->getMasterPid()) {
            echo 'swoole server not running!'.PHP_EOL;
            return false;
        }

        $cmd = 'kill -15 '.$masterPid;

        shell_exec($cmd);
        echo 'stop ...'.PHP_EOL;

        return true;
    }

    /**
     * 进程状态
     *
     * @return bool
     * @author   陈朔  chenshuo@vchangyi.com
     * @date     2019/1/7 3:26 PM
     */
    protected function status()
    {
        if (!$masterPid = $this->getMasterPid()) {
            echo 'swoole server not running!'.PHP_EOL;
            return false;
        }

        $cmd = 'ps -A | grep swoole.php';
        $result = shell_exec($cmd);

        echo $result;
        return true;
    }

    /**
     * 获取主进程ID
     *
     * @return int|null
     * @author   陈朔  chenshuo@vchangyi.com
     * @date     2019/1/7 3:26 PM
     */
    protected function getMasterPid()
    {
        $pidFile = $this->config['set']['pid_file'];

        if (file_exists($pidFile)) {
            return (int) file_get_contents($pidFile);
        }

        return null;
    }
}

class Server
{
    /**
     * 配置文件
     *
     * @var array
     */
    protected $config = [];

    /**
     * 主机地址
     *
     * @var null
     */
    protected $host = null;

    /**
     * 端口号
     *
     * @var null
     */
    protected $port = null;

    /**
     * swoole 模型
     *
     * @var int
     */
    protected $mode = SWOOLE_PROCESS;

    /**
     * socket 类型
     *
     * @var int
     */
    protected $sockType = SWOOLE_SOCK_TCP;

    /**
     * 配置参数
     *
     * @var array
     */
    protected $setting = [];

    /**
     * Server constructor.
     * @param $config
     */
    public function __construct($config)
    {
        $this->config = $config;

        $this->host = $config['host'];
        $this->port = $config['port'];
        $this->setting = $config['set'];

        $this->init();
    }

    /**
     * 初始化
     *
     * @author   陈朔  chenshuo@vchangyi.com
     * @date     2019/1/7 4:28 PM
     */
    protected function init()
    {
        $server = new Swoole\Server($this->host, $this->port, $this->mode, $this->sockType);

        $server->on('start', [$this, 'onStart']);
        $server->on('connect', [$this, 'onConnect']);
        $server->on('receive', [$this, 'onReceive']);
        $server->on('workerStart', [$this, 'onWorkerStart']);
        $server->on('workerStop', [$this, 'onWorkerStop']);
        $server->on('managerStart', [$this, 'onManagerStart']);
        $server->on('managerStop', [$this, 'onManagerStop']);
        $server->on('task', [$this, 'onTask']);
        $server->on('finish', [$this, 'onFinish']);
        $server->on('close', [$this, 'onClose']);

        $server->set($this->setting);

        $server->start();
    }

    /**
     * 当master进程启动
     *
     * @param \Swoole\Server $server
     * @author   陈朔  chenshuo@vchangyi.com
     * @date     2019/1/7 4:28 PM
     */
    public function onStart(Swoole\Server $server)
    {
        echo 'start... '.PHP_EOL;
        echo 'version: '.SWOOLE_VERSION.PHP_EOL;
        echo 'master process id: '.$server->master_pid.PHP_EOL;
    }

    /**
     * 当manager进程启动
     *
     * @param \Swoole\Server $server
     * @author   陈朔  chenshuo@vchangyi.com
     * @date     2019/1/7 4:43 PM
     */
    public function onManagerStart(Swoole\Server $server)
    {
        echo 'manager start ...'.PHP_EOL;
        echo 'manager process id: '.$server->manager_pid.PHP_EOL;
    }

    /**
     * 当manager进程关闭
     *
     * @param \Swoole\Server $server
     * @author   陈朔  chenshuo@vchangyi.com
     * @date     2019/1/7 4:43 PM
     */
    public function onManagerStop(Swoole\Server $server)
    {
        echo 'manager stop ...'.PHP_EOL;
        echo 'manager process id: '.$server->manager_pid.PHP_EOL;
    }

    /**
     * 当worker进程启动
     *
     * @param \Swoole\Server $server
     * @author   陈朔  chenshuo@vchangyi.com
     * @date     2019/1/7 4:29 PM
     */
    public function onWorkerStart(Swoole\Server $server)
    {
        require_once __DIR__.'/bootstrap/app.php';

        echo 'worker start'.PHP_EOL;
        echo 'worker process id: '.$server->worker_pid.PHP_EOL;
    }

    /**
     * 当worker进程关闭
     *
     * @param \Swoole\Server $server
     * @author   陈朔  chenshuo@vchangyi.com
     * @date     2019/1/7 4:29 PM
     */
    public function onWorkerStop(Swoole\Server $server)
    {
        echo 'worker stop'.PHP_EOL;
        echo 'worker_pid:'.$server->worker_pid.PHP_EOL;
    }

    /**
     * 当客户端连接时
     *
     * @param \Swoole\Server $server
     * @param int $fd TCP连接中客户端的唯一标识
     * @param int $reactorId TCP连接所在的Reactor线程ID
     * @author   陈朔  chenshuo@vchangyi.com
     * @date     2019/1/9 9:21 AM
     */
    public function onConnect(Swoole\Server $server, int $fd, int $reactorId)
    {
        echo 'connection ...'.PHP_EOL;

        echo 'client fd: '.$fd.PHP_EOL;
        echo 'reactor id: '.$reactorId.PHP_EOL;

        $connections = $server->connections;

        var_export($connections);
    }

    /**
     * 数据解析
     *
     * @param \Swoole\Server $server
     * @param $fd
     * @param int $reactorId TCP连接所在的Reactor线程ID
     * @param $data
     * @return bool
     * @author   陈朔  chenshuo@vchangyi.com
     * @date     2019/1/9 9:22 AM
     */
    public function onReceive(Swoole\Server $server, $fd, $reactorId, $data)
    {
        echo 'receive ...'.PHP_EOL;
        echo 'reactor id: '.$reactorId.PHP_EOL;

        $data = json_decode($data, true);

        if ($data['sync'] === true) {
            $server->send($fd, '[task]');
            $server->task($data);
            return true;
        }

        $result = $this->call($data);
        $result = json_encode($result);
        $server->send($fd, $result);
        echo '不是异步任务'.PHP_EOL;
        return true;
    }

    /**
     * 处理异步任务
     *
     * @param \Swoole\Server $server
     * @param int $taskId 任务ID
     * @param $workerId
     * @param $data
     * @author   陈朔  chenshuo@vchangyi.com
     * @date     2019/1/9 9:22 AM
     */
    public function onTask(Swoole\Server $server, $taskId, $workerId, $data)
    {
        echo '是异步调用'.PHP_EOL;
        $this->call($data);

        echo 'task id: '.$taskId.PHP_EOL;
        echo 'worker id: '.$workerId.PHP_EOL;

        $server->finish($data);
    }

    /**
     * 当异步任务完成时调用
     *
     * @param \Swoole\Server $server
     * @param $taskId
     * @param $data
     * @author   陈朔  chenshuo@vchangyi.com
     * @date     2019/1/9 9:23 AM
     */
    public function onFinish(Swoole\Server $server, $taskId, $data)
    {
        echo 'finish ...'.PHP_EOL;

        echo 'task id: '.$taskId.PHP_EOL;
    }

    /**
     * 当连接关闭调用
     *
     * @author   陈朔  chenshuo@vchangyi.com
     * @date     2019/1/9 11:03 AM
     */
    public function onClose()
    {
        echo 'close ...'.PHP_EOL;
    }

    /**
     * 动态调用
     *
     * @param $data
     *
     * @return mixed
     * @author   陈朔  chenshuo@vchangyi.com
     * @date     2019/1/8 11:52 AM
     */
    protected function call($data)
    {
        $class = 'App\\Task\\'.$data['class'];
        $method = $data['method'];
        $params = (array) $data['params'];

        return call_user_func_array([new $class, $method], $params);
    }
}
