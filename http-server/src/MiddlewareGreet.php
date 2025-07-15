<?php
/**
* middleware greets in response to requests received by http server.
*/
namespace Adeelahmadk\HttpServer;

require __DIR__ . "/../vendor/autoload.php";

use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;

class MiddlewareGreet {
  public function __invoke(ServerRequestInterface $request) {
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
  }
}
