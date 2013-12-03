<?php

use Nv2\Lib\Nv2\Http\Url;
use Nv2\Lib\Nv2\Config\Config;
use Nv2\Lib\Nv2\Core\Module;
use Nv2\Lib\Nv2\Text\Translator;
use Nv2\Lib\Nv2\Data\FileData;

function css_link($fileName)
{
    $filePath = ROOT_DIR . '/web/css/' . $fileName; 
    $time = @filemtime($filePath);
    
    echo WEB_HREF . '/css/' . $fileName . '?' . $time;
}

function js_link($fileName)
{
    $filePath = ROOT_DIR . '/web/js/' . $fileName;
    $time = @filemtime($filePath);
    
    echo WEB_HREF . '/js/' . $fileName . '?' . $time;
}

function url_link($ressource, $region=null)
{
    echo Url::format($ressource, $region);
}

function img_src($fileName)
{
    $filePath = ROOT_DIR . '/web/' . $fileName;
    $time = @filemtime($filePath);
    
    echo WEB_HREF . $fileName . '?' . $time;
}

function escape($string, $policy='html')
{
    return htmlentities($string, ENT_QUOTES, 'UTF-8');    
}

function config_get($file, $group, $param)
{
    return Config::get($file, $group, $param);
}

function request_get($name, $index=0)
{
    switch ($name) {
        case 'RegionName':
            return Module::$sRequest->getRegionName();
            break;
        default:
            $index = $name;
        case 'param':
            return Module::$sRequest->getParam($index);
            break;
    }
}

function translate($context, $identifier)
{
    return Translator::translate($context, $identifier);    
}

function slugify($text)
{
    $text = preg_replace('~[^\\pL\d]+~u', '-', $text);
    $text = trim($text, '-');
    if (function_exists('iconv'))
    {
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    }
 
    $text = strtolower($text);
    $text = preg_replace('~[^-\w]+~', '', $text);
 
    if (empty($text))
    {
        return 'n-a';
    }
 
    return $text;
}

function data_get($section, $file, $var=null)
{
    return FileData::get($section, $file, $var);
}