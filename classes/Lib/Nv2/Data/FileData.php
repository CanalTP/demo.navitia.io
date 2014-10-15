<?php 

namespace Nv2\Lib\Nv2\Data;

class FileData
{
    public static function get($section, $file, $var = null)
    {
        $file = ROOT_DIR . '/data/' . $section . '/' . $file . '.php';
        $returnValue = null;
        if (file_exists($file)) {
            require($file);
            if ($var != null) {
                if (isset($data[$var])) {
                    $returnValue = $data[$var];
                }
            } else {
                $returnValue = $data;
            }
        } else {
            throw new \Exception('Data file "' . $file . '" not found!');
        }
        return $returnValue;
    }
}
