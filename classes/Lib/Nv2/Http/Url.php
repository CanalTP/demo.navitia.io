<?php

namespace Nv2\Lib\Nv2\Http;

use Nv2\Lib\Nv2\Core\Module;

class Url
{
    public static function format($resource, $region=null)
    {
        if (!$region) {
            $region = Module::$sRequest->getRegionName();
        }
        
        $scriptName = explode('/', $_SERVER['SCRIPT_NAME']);
        foreach ($scriptName as $element) {
            if (strstr($element, '.php')) {
                $scriptName = $element;
                break;
            }
        }

        if ($resource == '/') {
            return ROOT_HREF;
        } else {
            return ROOT_HREF . $scriptName . '/' . $region . '/' . $resource;
        }
    }
}