<?php

namespace Nv2\Lib\Nv2\Config;

class Config
{
    private static $LoadedFiles;

    public static function get($file, $group=null, $param=null)
    {
        if (!isset(self::$LoadedFiles[$file])) {
            self::$LoadedFiles[$file] = new ConfigFile($file);
        }

        if (isset(self::$LoadedFiles[$file])) {
            return self::$LoadedFiles[$file]->get($group, $param);
        }
    }

    public static function set($file, $group, $param, $value)
    {
        self::$LoadedFiles[$file]->set($group, $param, $value);
    }
}