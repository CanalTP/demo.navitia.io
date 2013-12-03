<?php

namespace Nv2\Lib\Nv2\Config;

class ConfigFile
{
    private $groupList;

    public function __construct($file)
    {
        $this->load($file);
    }

    public function load($file)
    {
        $config_file = ROOT_DIR . '/config/' . $file . '.php';
        if (file_exists($config_file)) {
            require($config_file);
            $this->groupList = $config;
        }
    }

    public function get($group=null, $param=null)
    {
        if ($group === null) {
            return $this->groupList;
        } else {
            if ($param === null) {
                return $this->groupList[$group];
            } else {
                return $this->groupList[$group][$param];
            }
        }
    }

    public function set($group, $param, $value)
    {
        $this->groupList[$group][$param] = $value;
    }
}