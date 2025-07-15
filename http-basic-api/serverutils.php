<?php

class ServerUtils
{
    public static function keysExist($data, $keys): bool
    {
        foreach ($keys as $key) {
            if (!array_key_exists($key, $data)) {
                return false;
            }
        }
        return true;
    }

    public static function keysMatch($data, $required): bool
    {
        return count(array_intersect_key(array_flip($required), $data)) === count($required);
    }
}
