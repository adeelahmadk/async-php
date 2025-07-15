<?php
/**
* http server with modular middleware using promises
* for deferred resolution or rejection.
*/
require __DIR__ . "/../vendor/autoload.php";

use React\Http\Message\Response;
use Psr\Http\Message\ServerRequestInterface;
use React\Promise\Promise;
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

$resolver = function (ServerRequestInterface $request, callable $next) {
    $promise = new Promise(function ($resolve) use ($next, $request) {
        $resolve($next($request));
    });
    return $promise->then(null, function (Exception $e) {
        $msg = [
            'log' => 'Internal error: ' . $e->getMessage(),
            'message' => 'query failed!',
            'status' => 'failure'
        ];
        return Response::json($msg)
          ->withStatus(Response::STATUS_INTERNAL_SERVER_ERROR);
    });
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
        'message' => [
            'id' => 42,
            'product_name'=> 'Coffee Mug',
            'price'=> '$12.5' 
        ],
        'status' => 'success'
    ];
    
    if (mt_rand(0, 1) === 1) {
            throw new RuntimeException('Database error');
    }
    
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

$loop = React\EventLoop\Loop::get();
$http = new React\Http\HttpServer(
    // logger
    $logger,
    // resolver
    $resolver,
    // redirect requests for admin routes
    $redirection,
    // response
    $greet
);

/**
 *  Server Setup
 */

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
