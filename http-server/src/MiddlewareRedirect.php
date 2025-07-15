<?php
/**
* middleware redirects requests for restricted routes.
*/
namespace Adeelahmadk\HttpServer;

require __DIR__ . "/../vendor/autoload.php";

use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;

class MiddlewareRedirect {
  public function __invoke(ServerRequestInterface $request, callable $next) {
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
  }
}
