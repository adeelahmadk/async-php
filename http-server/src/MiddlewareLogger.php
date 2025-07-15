<?php
/**
* middleware logs requests for http server.
*/
namespace Adeelahmadk\HttpServer;

require __DIR__ . "/../vendor/autoload.php";

use Psr\Http\Message\ServerRequestInterface;

class MiddlewareLogger {
  public function __invoke(ServerRequestInterface $request, callable $next) {
    $request = $request->withHeader('Request-Time', time());
    
    echo "request: [method: " . $request->getMethod() .
        ", path: " . $request->getUri()->getPath() .
        " @". time() . "]". PHP_EOL;
    return $next($request);
  }
}
