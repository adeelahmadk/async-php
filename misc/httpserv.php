<?php
/**
 *  Explains usage of HTTP server and socket objects to implement a simple http request/response.
 *
 *  Example:
 *    php httpserv.php
 */

require __DIR__ . '/../vendor/autoload.php';

use React\Socket\SocketServer;
use React\Http\HttpServer;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;

$loop = React\EventLoop\Loop::get();

$server = new HttpServer(
    function (ServerRequestInterface $request) {
        echo 'Received request: ' . $request->getMethod() . ' ' . $request->getUri() . PHP_EOL;
        return new Response(
            Response::STATUS_OK,  // 200
            ['Content-Type' => 'text/html'],
            '<h1>ReactPHP HTTP Server</h1><p>Hello, World!</p>'
        );
    }
);

$socket = new SocketServer('127.0.0.1:8000', [], $loop);
$server->listen($socket);

echo "server listening on 127.0.0.1:8000\n";
$loop->run();
