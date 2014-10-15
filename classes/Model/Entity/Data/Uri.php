<?php

namespace Nv2\Model\Entity\Data;

use Nv2\Model\Entity\Places\Place;

class Uri
{
    public $value;

    private function __construct($string)
    {
        $this->value = $string;
    }
    
    public static function create($string)
    {
        return new self($string);
    }
    
    public function getType()
    {
        $data = explode(':', $this->value);
        switch ($data[0]) {
            case 'address':
                $type = Place::OBJECT_TYPE_ADDRESS;
                break;
            case 'stop_area':
                $type = Place::OBJECT_TYPE_STOP_AREA;
                break;
            case 'stop_point':
                $type = Place::OBJECT_TYPE_STOP_POINT;
                break;
            case 'poi':
                $type = Place::OBJECT_TYPE_POI;
                break;
            case 'administrative_region':
                $type = Place::OBJECT_TYPE_ADMIN;
                break;
            default:
                $type = Place::OBJECT_TYPE_UNKNOWN;
                break;
        }
        return $type;
    }
}
