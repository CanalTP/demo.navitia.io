<?php 

namespace Nv2\Lib\Nv2\Text;

/**
 * Class that represents a translation file
 * 
 * @author Thomas Noury <thomas.noury@canaltp.fr>
 * @copyright 2014, Canal TP
 */
class TranslationFile
{
    private $transList;
    
    /**
     * Constructor
     * 
     * @param string $file
     * @param string $locale
     */
    public function __construct($file, $locale)
    {
        $this->transList = array();
        $this->load($file, $locale);
    }
    
    /**
     * Loads a specified translation file
     * 
     * @param string $file
     * @param string $locale
     */
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
    
    /**
     * Get a translation word from the current file
     * 
     * @param string $identifier
     * @return string
     */
    public function get($identifier)
    {
        $text = '[NOT_FOUND]';
        if (isset($this->transList[$identifier])) {
            $text = $this->transList[$identifier];
        }
        return $text;
    }
}
