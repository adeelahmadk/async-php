<?php
namespace AdeelAhmadK\Phpchat\Utils;

require __DIR__ . "/../../vendor/autoload.php";

class AppHelper {
    public static function setupEnv() {
        if (!defined('NS_EOL')) {
            define('NS_EOL', php_sapi_name() === 'cli' ? PHP_EOL : '<br />');
        }
    }
    
    public static function getArgs($args, $keys) {
        if (empty($args)) { return []; }

        $opts = [];
        foreach ( $args as $arg ) {
            foreach ( $keys as $key ) {
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
