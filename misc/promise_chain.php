<?php
/**
 *  Explains usage of promise chaining using simulated http request/response.
 *
 *  Example:
 *    php promise_chain.php
 */

# Promise: used when we need a single value
#          from a deferred process.

require __DIR__ . '/../vendor/autoload.php';

function http($url, $method)
{
    $min = 0;
    $max = 100;
    $rnum = mt_rand($min, $max) * 10000;
    usleep($rnum);

    $response = (mt_rand(0, 1))? "Data" : "";
    $deferred = new React\Promise\Deferred();

    if ($response) {
        $deferred->resolve($response);
    } else {
        $deferred->reject(new Exception('no response.'));
    }

    return $deferred->promise();
}

http("http://google.com", "GET")
    ->then(
        function ($response) {
            return strtoupper($response);
        }
    )
    ->then(
        function ($response) {
            echo $response . PHP_EOL;
        }
    )
    ->catch(
        function (Exception $e) {
            echo $e->getMessage() . PHP_EOL;
        }
    );
