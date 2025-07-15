<?php
/**
 *  Classic callback pattern. Precursor to promises.
 */
require __DIR__ . '/../vendor/autoload.php';

function http ($url, $method, callable $onSuccess, callable $onError) {
  $response = (mt_rand(0, 1))? "OK" : "";
  
  if ($response) {
    $onSuccess($response);
  } else {
    $onError(new Exception('no response.'));
  }
}

http("http://google.com", "GET",
  function ($response) {
    echo $response . PHP_EOL;
  },
  function (Exception $e) {
    echo $e->getMessage() . PHP_EOL;
  });
