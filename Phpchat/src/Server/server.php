<?php
namespace AdeelAhmadK\Phpchat\Server;

/**
 * Server Entry Point
 * =================
 *
 * This file serves as the entry point for the application's server, handling
 * incoming requests and dispatching them to the relevant handlers.
 *
 * @file server.php
 * @package Phpchat
 * @author Adeel Ahmad
 * @license MIT
 * @link https://adeelahmadk.github.io
 */

require __DIR__ . "/../../vendor/autoload.php";

use React\Socket\SocketServer;
use React\Socket\ConnectionInterface;

use AdeelAhmadK\Phpchat\Utils\AppHelper;

AppHelper::setupEnv();

$host = '127.0.0.1';
$port = 8000;
if (isset($argv)) {
    $args = AppHelper::getArgs($argv, ['--host=', '--port=']);
    $host = $args['host'] ?? $host;
    $port = (isset($args['port']) && is_numeric($args['port'])) ? intval($args['port']) : $port;
}
$listenAt = sprintf('%s:%d', $host, $port);

$loop = \React\EventLoop\Loop::get();
$server = new SocketServer($listenAt, [], $loop);
$pool = new ConnectionPool();

$server->on(
    'connection',
    function (ConnectionInterface $connection) use ($pool) {
        $pool->add($connection);
    }
);

echo "Chat server listening on $listenAt" . NS_EOL;
$loop->run();

