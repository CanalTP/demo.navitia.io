<?php

namespace Nv2\Lib\Nv2\Service;

class IdConverter
{
    public function encodeSlashes($id)
    {
        return str_replace(array('/', '%2F'), '#ENSL#', $id);
    }
    
    public function decodeSlashes($id)
    {
        return str_replace('#ENSL#', '/', $id);
    }
}