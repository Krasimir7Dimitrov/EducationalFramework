<?php

namespace App\System;

final class Registry
{
    /**
     * @var array
     */
    private static $storage = [];

    private function __construct()
    {

    }

    /**
     * @return array
     */
    public static function get($key)
    {
        if (empty(self::$storage)) {
            return null;
        }
        var_dump(self::$storage);
        return self::$storage;
    }

    /**
     * @param $key
     * @param $value
     * @return mixed
     * @throws \Exception
     */
    public static function set($key, $value)
    {
        if (isset(self::$storage[$key])) {
            throw new \Exception('The key is already set');
        }
        self::$storage[$key] = $value;
    }

    /**
     * @param $key
     * @return void
     */
    public static function unset($key)
    {
        if (isset(self::$storage[$key])) {
            unset(self::$storage[$key]);
        }
    }
}
