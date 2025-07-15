<?php
require __DIR__ . "/../vendor/autoload.php";

use React\Socket\ConnectionInterface;


$host = '127.0.0.1';
$port = 8000;
$listenAt = sprintf('%s:%d', $host, $port);

if (isset($argv)) {
    foreach ($argv as $arg) {
        if (strpos($arg, '--host=') === 0) {
            $host = substr($arg, 7);
        } elseif (strpos($arg, '--port=') === 0) {
            $port = (int)substr($arg, 7);
        }
    }
    $listenAt = sprintf('%s:%d', $host, $port);
}


$loop = React\EventLoop\Loop::get();
$server = new React\Socket\SocketServer($listenAt, [], $loop);
$pool = new ConnectionPool();

$server->on(
    'connection',
    function (ConnectionInterface $connection) use ($pool) {
        $pool->add($connection);
        //echo 'New connection from ' . $connection->getRemoteAddress() . PHP_EOL;
    }
);

echo "server listening on $listenAt\n";

$loop->run();
