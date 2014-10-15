<?php

namespace Nv2\Lib\Nv2\Config;

class Config
{
    private static $loadedFiles;

    public static function get($file, $group = null, $param = null)
    {
        if (!isset(self::$loadedFiles[$file])) {
            self::$loadedFiles[$file] = new ConfigFile($file);
        }

        if (isset(self::$loadedFiles[$file])) {
            return self::$loadedFiles[$file]->get($group, $param);
        }
    }

    public static function set($file, $group, $param, $value)
    {
        self::$loadedFiles[$file]->set($group, $param, $value);
    }
}
