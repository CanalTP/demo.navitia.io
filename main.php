<?php

use Nv2\Lib\Nv2\Http\Request;
use Nv2\Lib\Nv2\Core\Module;

//error_reporting(E_ALL);
ini_set('display_errors', 'off');

define('ROOT_DIR', getcwd());
define('CLASS_DIR', ROOT_DIR . '/classes');
define('TEMPLATE_DIR', ROOT_DIR . '/template');

$script_name = explode('/', filter_input(INPUT_SERVER, 'SCRIPT_NAME', FILTER_SANITIZE_STRING));

foreach ($script_name as $element) {
    if (strstr($element, '.php')) {
        $script_name = $element;
        break;
    }
}

$root_href_data = explode($script_name, filter_input(INPUT_SERVER, 'SCRIPT_NAME', FILTER_SANITIZE_STRING));

define('ROOT_HREF', $root_href_data[0]);
define('WEB_HREF', ROOT_HREF . 'web');

require('helper/design.php');

function __autoload($className)
{
    require('autoload.php');

    if (key_exists($className, $classList)) {
        if (file_exists($classList[$className])) {
            require($classList[$className]);
        }
    }
}

Module::$request = new Request();