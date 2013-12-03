<?php

namespace Nv2\Lib\Nv2\Core;

use Nv2\Lib\Nv2\Http\Request;
use Nv2\Lib\Nv2\Template\Template;

class Module
{
    private $Request;
    private $ModuleTemplate;
    private $ActionList;
    private $DebugMode;
    
    public static $sLocale;
    public static $sRequest;
    public static $sEnvironment;

    private function __construct()
    {
        $this->Request = new Request();
        $this->ModuleTemplate = new Template();
    }

    public static function instance()
    {
        return new self();
    }
    
    public function locale($identifier)
    {
        self::$sLocale = $identifier;
        $this->Request->setLocale($identifier);
        return $this;
    }
    
    public function environment($name)
    {
        self::$sEnvironment = $name;
        $this->Request->setEnvironment($name);
        return $this;
    }

    public function execute()
    {
        $module_file = ROOT_DIR . '/module/' . $this->Request->getModuleName() . '/module.php';
        $module_result = '';

        if (file_exists($module_file)) {
            require($module_file);
            $this->ActionList = $action_list;

            $action_script_file = ROOT_DIR . '/module/'
                                           . $this->Request->getModuleName() . '/'
                                           . $this->ActionList[$this->Request->getActionName()]['script'];

            if (file_exists($action_script_file)) {
                require($action_script_file);
                $module_result = $this->ModuleTemplate->getContent();
            } else {
                die('Script file "' . $action_script_file . '" not found!');   
            }
        } else {
            die('Module file "' . $module_file . '" not found!');
        }

        if ($this->ModuleTemplate->getPagelayoutActive() == true) {
            $layout_file = TEMPLATE_DIR . '/pagelayout.php';
            if (file_exists($layout_file)) {
                include($layout_file);
            } else {
                die('Template file "' . $layout_file . '" not found!');
            }
        } else {
            echo $module_result;
        }

        return $this;
    }
    
    public function getRequest()
    {
        return $this->Request;
    }
}

Module::$sRequest = new Request();