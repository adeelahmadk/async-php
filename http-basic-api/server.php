<?php
/**
 *  Implementation of HTTP server to process http GET and POST requests on simulated data store.
 *
 *  Example:
 *  - php server.php
 *  - curl -i 127.0.0.1:8000/
 */

require __DIR__ . '/../vendor/autoload.php';
$posts = require 'posts.php';

use React\Socket\SocketServer;
use React\Http\HttpServer;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;

$loop = React\EventLoop\Loop::get();
$endpoints = [
    '/posts' => [
        'method' => 'GET',
        'handler' => function ($params) use (&$posts): Response {
            $tag = $params['tag'] ?? null;
            $filteredPosts = array_filter(
                $posts,
                function (array $post) use ($tag) {
                    if ($tag) {
                        return in_array($tag, $post['tags']);
                    }
                    return true;
                }
            );

            $page = $params['page'] ?? 1;
            $postsPerPage = 3;
            $filteredPosts = array_chunk($filteredPosts, $postsPerPage)[$page - 1] ?? [];

            return new Response(
                Response::STATUS_OK,  // 200
                ['Content-Type' => 'application/json'],
                json_encode($filteredPosts)
            );
        }
    ],
    '/addpost' => [
        'method' => 'POST',
        'handler' => function ($data) use (&$posts): Response {
            if (!ServerUtils::keysMatch($data, ['title', 'body', 'tags'])) {
                return new Response(
                    Response::STATUS_BAD_REQUEST,  // 400
                    ['Content-Type' => 'application/json'],
                    json_encode(['error' => 'Invalid data'])
                );
            }

            $data['id'] = count($posts) + 1;
            $posts[] = $data;
            echo "New post added, count: " . count($posts) . PHP_EOL;
            return new Response(
                Response::STATUS_CREATED,  // 201
                ['Content-Type' => 'application/json'],
                json_encode(['success' => true])
            );
        }
    ]
];

$server = new HttpServer(
    function (ServerRequestInterface $request) use ($endpoints): Response {
        $requestMethod = $request->getMethod();
        $targetPath = $request->getUri()->getPath();
        $params = $request->getQueryParams();

        echo 'Received request: ' . $request->getMethod() . ' ' .
            $targetPath . PHP_EOL;

        if (
            array_key_exists($targetPath, $endpoints) &&
            $endpoints[$targetPath]['method'] === $requestMethod
        ) {
            if ($requestMethod === 'POST') {
                $data = [];
                if ($request->getHeaderLine('Content-Type') === 'application/json') {
                    $data = json_decode((string) $request->getBody(), true);
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        return new Response(
                            Response::STATUS_BAD_REQUEST,  // 400
                            ['Content-Type' => 'application/json'],
                            json_encode(['error' => 'Invalid JSON data'])
                        );
                    }
                } else if ($request->getHeaderLine('Content-Type') === 'application/x-www-form-urlencoded') {
                    parse_str((string) $request->getBody(), $data);
                } else {
                    return new Response(
                        Response::STATUS_UNSUPPORTED_MEDIA_TYPE,  // 415
                        ['Content-Type' => 'text/plain'],
                        json_encode(['error' => 'Unsupported content type'])
                    );
                }
                return $endpoints[$targetPath]['handler']($data);
            } else {
                return $endpoints[$targetPath]['handler']($params);
            }
        }

        return new Response(
            Response::STATUS_BAD_REQUEST,  // 400
            ['Content-Type' => 'application/json'],
            json_encode(['error' => 'Invalid path'])
        );
    }
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
$server->listen($socket);
echo "server listening on $listenAt\n";
echo "post count: " . count($posts) . PHP_EOL;

$loop->run();
