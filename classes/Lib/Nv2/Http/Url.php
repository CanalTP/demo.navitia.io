<?php

namespace Nv2\Lib\Nv2\Http;

use Nv2\Lib\Nv2\Core\Module;

class Url
{
    public static function format($resource, $region = null)
    {
        if (!$region) {
            $region = Module::$request->getRegionName();
        }
        
        $scriptName = explode('/', filter_input(INPUT_SERVER, 'SCRIPT_NAME', FILTER_SANITIZE_STRING));
        foreach ($scriptName as $element) {
            if (strstr($element, '.php')) {
                $scriptName = $element;
                break;
            }
        }

        $returnUrl = '';
        if ($resource == '/') {
            $returnUrl = ROOT_HREF;
        } else {
            $returnUrl = ROOT_HREF . $scriptName . '/' . $region . '/' . $resource;
        }
        return $returnUrl;
    }
}
