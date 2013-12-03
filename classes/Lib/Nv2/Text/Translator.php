<?php 

namespace Nv2\Lib\Nv2\Text;

use Nv2\Lib\Nv2\Core\Module;

class Translator
{    
    private static $LoadedFiles;
    
    public static function translate($context, $identifier)
    {        
        if (!isset(self::$LoadedFiles[$context])) {
            self::$LoadedFiles[$context] = new TranslationFile($context, Module::$sLocale);
        }
        
        if (isset(self::$LoadedFiles[$context])) {
            return self::$LoadedFiles[$context]->get($identifier);
        }
    }
}