<?php 
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

require_once __DIR__ . '/config.php';
$connection = new AMQPStreamConnection($config['host'], $config['port'], $config['user'], $config['password']);
$channel = $connection->channel();

echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";

$callback = function($msg) {
  echo " [x] Received ", $msg->body, "\n";
  sleep(substr_count($msg->body, '.'));//模拟任务延迟，通过.控制延迟的秒数
  echo " [x] Done", "\n";
};

$channel->basic_consume('hello', '', false, true, false, false, $callback);

while(count($channel->callbacks)) {
    $channel->wait();
}

$channel->close();
$connection->close();