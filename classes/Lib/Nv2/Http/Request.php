<?php

namespace Nv2\Lib\Nv2\Http;

use Nv2\Lib\Nv2\Debug\Debug;

class Request
{
    private $moduleName;
    private $actionName;
    private $params;
    private $regionName;
    private $locale;
    private $environment;

    public function __construct()
    {
        $this->regionName = null;
        $this->moduleName = 'home';
        $this->actionName = 'index';
        $this->params = array();
        $this->locale = null;
        $this->environment = null;

        $this->initFromUri();
    }

    public function getRegionName()
    {
        return $this->regionName;
    }

    public function getModuleName()
    {
        return $this->moduleName;
    }

    public function getActionName()
    {
        return $this->actionName;
    }

    public function getParam($index)
    {        
        if (isset($this->params[$index])) {
            return $this->params[$index];
        } else if (isset($_REQUEST[$index])) {
            return $_REQUEST[$index];
        } else {
            return NULL;
        }
    }

    public function getParams()
    {
        return $this->params;
    }
    
    public function getFormattedParam($name)
    {
        $param = $this->getParam($name);
        if ($param != null) {
            if (is_array($param)) {
                $str = '';
                foreach ($param as $p) {
                    $str .= '&' . $name . '[]=' . $p;
                }                
                return $str;
            } else {
                return $name . '=' . $param;
            }
        }
        return null;
    }

    public function getUrl()
    {
        return $_SERVER['REQUEST_URI'];
    }
    
    public function getLocale()
    {
        return $this->locale;        
    }
    
    public function getEnvironment()
    {
        return $this->environment;
    }
    
    public function getDebugStatus()
    {
        $debug = $this->getParam('debug');
        switch ($debug) {
            case '1':
                return Debug::STATUS_WEB;
                break;
            case '2':
                return Debug::STATUS_RAW;
                break;
            default:
                return Debug::STATUS_NONE;
                break;
        }
    }
    
    public function setLocale($identifier)
    {
        $this->locale = $identifier;        
    }
    
    public function setEnvironment($name)
    {
        $this->environment = $name;
    }

    public function redirect($uri, $region=null)
    {
        $this->regionName = $region;
        $location = Url::format($uri, $region);
        header('location: '. $location);
        exit();
    }

    public function initFromUri()
    {
        $uri = $_SERVER['REQUEST_URI'];
        
        $scriptName = explode('/', $_SERVER['SCRIPT_NAME']);
        foreach ($scriptName as $element) {
            if (strstr($element, '.php')) {
                $scriptName = $element;
                break;
            }
        }
        $script = str_replace('/', '', $scriptName);
        
        $uriData = explode($script, $uri);
        if (isset($uriData[1])) {
            $uriData = explode('/', $uriData[1]);
        }

        $uriCount = count($uriData);

        if (isset($uriData[1]) && $uriData[1] != '') {
            $this->regionName = $uriData[1];
        }
        if (isset($uriData[2]) && $uriData[2] != '') {
            $this->moduleName = $uriData[2];
        }
        if (isset($uriData[3]) && $uriData[3] != '') {
            $this->actionName = $uriData[3];
        }
        for ($i = 4; $i < $uriCount; $i++) {
            if (isset($uriData[$i])) {
                $this->params[] = $uriData[$i];
            }
        }
        foreach ($_REQUEST as $varName => $varValue) {
            $this->params[$varName] = $varValue;
        }
    }
}