<?php
/**
* http server using middleware classes.
*/
require __DIR__ . "/../vendor/autoload.php";

use React\Http\Message\Response;
use Psr\Http\Message\ServerRequestInterface;
use React\Socket\SocketServer;

use Adeelahmadk\HttpServer\MiddlewareLogger;
use Adeelahmadk\HttpServer\MiddlewareRedirect;
use Adeelahmadk\HttpServer\MiddlewareGreet;

$uri = '127.0.0.1:8000';
$loop = React\EventLoop\Loop::get();

$http = new React\Http\HttpServer(
    // logger
    new MiddlewareLogger(),
    // redirect requests for admin routes
    new MiddlewareRedirect(),
    // response
    new MiddlewareGreet()
);

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

$socket = new SocketServer($listenAt, [], $loop);
$http->listen($socket);
echo "server listening on $listenAt ..." . PHP_EOL;

$loop->run();

