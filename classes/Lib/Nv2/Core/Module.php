<?php

namespace Nv2\Lib\Nv2\Core;

use Nv2\Lib\Nv2\Template\Template;

class Module
{
    private $moduleTemplate;
    private $actionList;
    private $debugMode;
    
    public static $locale;
    public static $request;
    public static $environment;

    private function __construct()
    {
        $this->moduleTemplate = new Template();
    }

    public static function instance()
    {
        return new self();
    }
    
    public function locale($identifier)
    {
        self::$locale = $identifier;
        self::$request->setLocale($identifier);
        return $this;
    }
    
    public function environment($name)
    {
        self::$environment = $name;
        self::$request->setEnvironment($name);
        return $this;
    }

    public function execute()
    {
        $request = self::$request;
        $template = $this->moduleTemplate;
        
        $moduleFile = ROOT_DIR . '/module/' . self::$request->getModuleName() . '/module.php';
        $moduleResult = '';

        if (file_exists($moduleFile)) {
            require($moduleFile);
            $this->actionList = $actionList;

            $actionScriptFile = ROOT_DIR . '/module/'
                                           . self::$request->getModuleName() . '/'
                                           . $this->actionList[self::$request->getActionName()]['script'];

            if (file_exists($actionScriptFile)) {
                require($actionScriptFile);
                $moduleResult = $this->moduleTemplate->getContent();
            } else {
                die('Script file "' . $actionScriptFile . '" not found!');
            }
        } else {
            die('Module file "' . $moduleFile . '" not found!');
        }

        if ($this->moduleTemplate->getPagelayoutActive() == true) {
            $layoutFile = TEMPLATE_DIR . '/pagelayout.php';
            if (file_exists($layoutFile)) {
                include($layoutFile);
            } else {
                die('Template file "' . $layoutFile . '" not found!');
            }
        } else {
            echo $moduleResult;
        }

        return $this;
    }
    
    public function getRequest()
    {
        return self::$request;
    }
}
