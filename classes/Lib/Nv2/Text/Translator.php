<?php 

namespace Nv2\Lib\Nv2\Text;

use Nv2\Lib\Nv2\Core\Module;

class Translator
{
    private static $loadedFiles;
    
    public static function translate($context, $identifier)
    {
        if (!isset(self::$loadedFiles[$context])) {
            self::$loadedFiles[$context] = new TranslationFile($context, Module::$locale);
        }
        
        if (isset(self::$loadedFiles[$context])) {
            return self::$loadedFiles[$context]->get($identifier);
        }
    }
}
