<?php 

namespace Nv2\Lib\Nv2\Data;

class FileData
{    
    public static function get($section, $file, $var=null)
    {
        $file = ROOT_DIR . '/data/' . $section . '/' . $file . '.php';
        if (file_exists($file)) {
            require($file);
            if ($var != null) {
                if (isset($data[$var])) {
                    return $data[$var];
                } else {
                    return null;
                }
            } else {
                return $data;
            }
        } else {
            echo 'Data file "' . $file . '" not found!';
            return null;   
        }
    }
}