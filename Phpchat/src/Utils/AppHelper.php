<?php
namespace AdeelAhmadK\Phpchat\Utils;

/**
 * Application Helper Functions
 * ============================
 *
 * This file contains the AppHelper class, which provides utility methods
 * for application setup and configuration.
 *
 * @file AppHelper.php
 * @package Phpchat
 * @author Adeel Ahmad
 * @license MIT
 * @link https://adeelahmadk.github.io
 */

require __DIR__ . "/../../vendor/autoload.php";

/**
 * Provides utility methods for application setup and configuration.
 *
 * This class offers static methods to assist with environment setup and
 * command-line argument parsing, making it easier to configure and run
 * your application.
 *
 * @package Phpchat
 * @author Adeel Ahmad
 */
class AppHelper
{
    /**
     *  Sets up the constants & variables appropriate
     *  for the environment.
     *
     *  @return void
     */
    public static function setupEnv()
    {
        if (!defined('NS_EOL')) {
            define('NS_EOL', php_sapi_name() === 'cli' ? PHP_EOL : '<br />');
        }
    }

    /**
     *  Parses the CLI arguments and returns an associative
     *  array of key-value pairs for the given keys.
     *
     *  @param array $args CLI arguments
     *  @param array $keys Keys to look for in arguments
     *
     *  @return array Associative array of key-value pairs
     */
    public static function getArgs($args, $keys)
    {
        if (empty($args)) {
            return [];
        }

        $opts = [];
        foreach ($args as $arg) {
            foreach ($keys as $key) {
                if (strpos($arg, $key) === 0) {
                    $k = substr($key, 2, -1);
                    $v = substr($arg, strlen($key));
                    $opts[$k] = $v;
                }
            }
        }

        return $opts;
    }
}
