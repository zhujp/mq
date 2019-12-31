<?php 
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

require_once __DIR__ . '/config.php';
$connection = new AMQPStreamConnection($config['host'], $config['port'], $config['user'], $config['password']);
$channel = $connection->channel();

$channel->exchange_declare('logs', 'fanout', false, false, false); //申明logs交换机为扇形交换机

$data = implode(' ', array_slice($argv, 1));
if(empty($data)) $data = "info: Hello World!";
$msg = new AMQPMessage($data);

$channel->basic_publish($msg, 'logs'); //发布到logs交换机

echo " [x] Sent ", $data, "\n";

$channel->close(); //通道关闭
$connection->close();//链接关闭