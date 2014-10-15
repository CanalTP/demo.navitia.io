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

    public function get($group = null, $param = null)
    {
        $returnList = array();
        if ($group === null) {
            $returnList = $this->groupList;
        } else {
            if ($param === null) {
                $returnList = $this->groupList[$group];
            } else {
                $returnList = $this->groupList[$group][$param];
            }
        }
        return $returnList;
    }

    public function set($group, $param, $value)
    {
        $this->groupList[$group][$param] = $value;
    }
}
