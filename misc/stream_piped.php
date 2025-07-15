<?php
/**
 *  Streams piped to perform read/transform/write (STDIN-Through-STDOUT) asynchronously using event loop.
 *
 *  Example:
 *    cat data.txt | php stream_piped.php
 */

# Stream: used when we are dealing with an API
#         that is pushing da6a continuously.

require __DIR__ . '/../vendor/autoload.php';

$loop = React\EventLoop\Loop::get();
$readable = new React\Stream\ReadableResourceStream(STDIN, $loop);
$writable = new React\Stream\WritableResourceStream(STDOUT, $loop);
$toUpper = new React\Stream\ThroughStream(function($chunk) {
  return strtoupper($chunk);
});

$readable->pipe($toUpper)->pipe($writable);

$readable->on('error', function (Exception $e) {
  echo 'Error: ' . $e->getMessage() . PHP_EOL;
});

$loop->run();
