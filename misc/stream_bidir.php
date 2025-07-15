<?php
/**
 *  STDIN/STDOUT stream read/write asynchronously using event loop.
 *
 *  Example:
 *    echo "Hello, World!" | php stream_bidir.php
 */

# Stream: used when we are dealing with an API
#         that is pushing data continuously.

require __DIR__ . '/../vendor/autoload.php';

$buffSize = 1;
$loop = React\EventLoop\Loop::get();
$readable = new React\Stream\ReadableResourceStream(STDIN, $loop, $buffSize);
$writable = new React\Stream\WritableResourceStream(STDOUT, $loop);

$readable->on('data', function ($chunk) use ($readable, $writable, $loop) {
    $writable->write($chunk);
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
