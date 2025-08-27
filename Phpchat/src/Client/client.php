<?php
namespace AdeelAhmadK\Phpchat\Client;

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

use React\Socket\Connector;
use React\Socket\ConnectionInterface;
use React\Stream\ReadableResourceStream;
use React\Stream\WritableResourceStream;

use AdeelAhmadK\Phpchat\Utils\AppHelper;

AppHelper::setupEnv();

$host = '127.0.0.1';
$port = 8000;
if (isset($argv)) {
    $args = AppHelper::getArgs($argv, ['--host=', '--port=']);
    $host = $args['host'] ?? $host;
    $port = (isset($args['port']) && is_numeric($args['port'])) ? intval($args['port']) : $port;
}
$requestTo = sprintf('%s:%d', $host, $port);

echo "Connecting to the chat server on $requestTo" . NS_EOL;

$loop = \React\EventLoop\Loop::get();
$input = new ReadableResourceStream(STDIN, $loop);
$output = new WritableResourceStream(STDOUT, $loop);

$connector = new Connector($loop);
$connection = $connector->connect($requestTo)
    ->then(
        function (ConnectionInterface $connection) use ($input, $output) {
            $input->pipe($connection)->pipe($output);
        },
        function (\Exception $e) {
            echo 'Error: ' . $e->getMessage() . PHP_EOL;
        }
    );

$loop->run();
