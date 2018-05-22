<?php

namespace App\Console\Commands;

use App\Console\RpcServer;
use App\Core\Swoole\Handler\TestHandler;
use ReflectionClass;
use swoole_server;
use Exception;

class Server extends RpcServer
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'swoole:server';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'swoole server';

    /**
     * host
     * @var string
     */
    protected $host = '0.0.0.0';

    /**
     * 端口号
     * @var int
     */
    protected $port = '11520';

    /**
     * 配置项
     * @var array
     */
    protected $config = [
        'pid_file' => './socket.pid',
        'daemonize' => false,
        'max_request' => 500, // 每个worker进程最大处理请求次数
        'worker_num' => 5,
        'open_eof_check' => true,
        'package_eof' => "\r\n",
    ];

    /**
     * @var array
     */
    public $services = [];

    public function handle()
    {
        $this->setHandler('test', TestHandler::class);
        return parent::handle(); // TODO: Change the autogenerated stub
    }

    /**
     * @param $service
     * @param $hander
     * @return $this
     */
    public function setHandler($service, $hander)
    {
        $this->services[$service] = $hander;
        return $this;
    }

    /**
     * @param swoole_server $server
     * @param $workerId
     */
    public function workerStart(swoole_server $server, $workerId)
    {
        // TODO: Implement workerStart() method.
    }

    /**
     * @param swoole_server $server
     * @param $fd
     * @param $reactor_id
     * @param $data
     */
    public function receive(swoole_server $server, $fd, $reactor_id, $data)
    {
        $data = trim($data);
        if ($this->debug) {
            echo "fd:{$fd} data:{$data}" . PHP_EOL;
        }
        // TODO: Implement receive() method.
        try {
            $data = json_decode($data, true);
            $service = $data['service'];
            $method = $data['method'];
            $arguments = $data['arguments'];

            if (!isset($this->services[$service])) {
                throw new Exception("The service handler is not exist!");
            }

            $ref = new ReflectionClass($this->services[$service]);
            $handler = $ref->newInstance($server, $fd, $reactor_id);
            $result = $handler->$method(...$arguments);

            $server->send($fd, $this->success($result));
        } catch (\Exception $ex) {
            $server->send($fd, $this->fail($ex->getCode(), $ex->getMessage()));
        }
    }
}
