<?php
/**
* http server with modular middleware.
*/
require __DIR__ . "/../vendor/autoload.php";

use React\Http\Message\Response;
use Psr\Http\Message\ServerRequestInterface;
use React\Socket\SocketServer;

/**
 *  Middleware Logic
 */

$logger = function (ServerRequestInterface $request, callable $next) {
    $request = $request->withHeader('Request-Time', time());
    echo "request: [method: " . $request->getMethod() .
    ", path: " . $request->getUri()->getPath() .
    " @". time() .
    "]". PHP_EOL;
    return $next($request);
};

$redirection = function (ServerRequestInterface $request, callable $next) {
    $pattern = "#^/admin[/\w]*$#i";
    $path = $request->getUri()->getPath();
    $matches = [];
    if (preg_match($pattern, $path, $matches)) {
        $msg = [
            'message' => 'off-limits!',
            'status' => 'redirected'
        ];
        return new Response(
            301,
            [
                'location' => '/',
                'Content-Type' => 'application/json'
            ],
            json_encode($msg)
        );
    }
    return $next($request);
};

$greet = function (ServerRequestInterface $request) {
    $msg = [
        'message' => 'Hello, world!',
        'status' => 'success'
    ];
    return new Response(
        Response::STATUS_OK,
        [
            'Content-Type' => 'application/json'
        ],
        json_encode($msg)
    );
};


/**
 *  Server Logic
 */
 
$uri = '127.0.0.1:8000';
$loop = React\EventLoop\Loop::get();
$http = new React\Http\HttpServer(
    // logger
    $logger
    ,
    // redirect requests for admin routes
    $redirection
    ,
    // response
    $greet
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
echo "server listening on $listenAt" . PHP_EOL;

$loop->run();
