<?php
/**
 *  STDIN stream read asynchronously using event loop.
 *
 *  Example:
 *    echo "Hello, World!" | php readable.php
 */
 
require __DIR__ . '/../vendor/autoload.php';

$buffSize = 1;
$loop = React\EventLoop\Loop::get();
$readable = new React\Stream\ReadableResourceStream(STDIN, $loop, $buffSize);

$readable->on('data', function ($chunk) use ($readable, $loop) {
    echo $chunk . PHP_EOL;
    $readable->pause();

    $loop->addTimer(1, function () use ($readable) {
        $readable->resume();
    });
});

$readable->on('end', function () {
    echo 'STDIN stream ended.' . PHP_EOL;
});

$readable->on('error', function (Exception $e) {
    echo 'Error: ' . $e->getMessage() . PHP_EOL;
});

$loop->run();
