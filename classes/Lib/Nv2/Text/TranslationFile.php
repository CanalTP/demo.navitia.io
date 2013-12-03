<?php 

namespace Nv2\Lib\Nv2\Text;

class TranslationFile
{    
    private $transList;
    
    public function __construct($file, $locale)
    {
        $this->transList = array();
        $this->load($file, $locale);
    }
    
    public function load($file, $locale)
    {
        $trans_file = ROOT_DIR . '/data/translations/' . $locale . '/' . $file . '.php';
        if (file_exists($trans_file)) {
            require($trans_file);
            if (isset($translations)) {
                $this->transList = $translations;
            }
        }
    }
    
    public function get($identifier)
    {
        if (isset($this->transList[$identifier])) {
            return $this->transList[$identifier];
        } else {
            return '[NOT_FOUND]';
        }
    }
}