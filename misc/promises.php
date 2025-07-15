<?php
/**
 *  Explains usage of promises using simulated http request/response.
 *
 *  Example:
 *    php promises.php
 */
 
require __DIR__ . '/../vendor/autoload.php';

function http ($url, $method) {
  $min = 0;
  $max = 100;
  $rnum = mt_rand($min, $max) * 10000;
  echo "random delay: " . ($rnum / 1000) . " msec" . PHP_EOL;
  usleep($rnum);
  
  $response = (mt_rand(0, 1))? "OK" : "";
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
      echo $response . PHP_EOL;
    },
    function (Exception $e) {
      echo $e->getMessage() . PHP_EOL;
    });
