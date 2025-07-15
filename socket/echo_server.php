<?php
require __DIR__ . "/../vendor/autoload.php";

$loop = React\EventLoop\Loop::get();
$socket = new React\Socket\SocketServer('127.0.0.1:8080', [], $loop);

$socket->on('connection', function (React\Socket\ConnectionInterface $connection) {
    echo 'New connection from ' . $connection->getRemoteAddress() . PHP_EOL;
    $connection->on('data', function ($data) use ($connection) {
        $connection->write($data);
        echo $connection->getRemoteAddress() . ' says: ' . $data;
    });
    $connection->on('close', function () use ($connection) {
        echo 'Connection closed by ' . $connection->getRemoteAddress() . PHP_EOL;
    });
    $connection->on('error', function (Exception $e) {
        echo 'Error: ' . $e->getMessage() . PHP_EOL;
    });
});

echo "Echo server listening on 127.0.0.1:8000\n";

$loop->run();
