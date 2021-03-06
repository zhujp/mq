<?php 
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

require_once __DIR__ . '/config.php';
$connection = new AMQPStreamConnection($config['host'], $config['port'], $config['user'], $config['password']);
$channel = $connection->channel();

$channel->queue_declare('hello', false, false, false, false);//声明队列

$data = implode(' ', array_slice($argv, 1));
if(empty($data)) $data = "Hello World!";

$msg = new AMQPMessage($data);
$channel->basic_publish($msg, '', 'hello');//发布消息到队列
echo " [x] Sent ", $data, "\n";
$channel->close(); //通道关闭
$connection->close();//链接关闭