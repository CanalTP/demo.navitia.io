<?php

namespace Nv2\Model\Entity\Data;

use Nv2\Model\Entity\Places\Place;

class Uri
{
    public $Value;

    private function __construct($string)
    {
        $this->Value = $string;
    }
    
    public static function create($string)
    {
        return new self($string);
    }
    
    public function getType()
    {
        $data = explode(':', $this->Value);
        switch ($data[0]) {
            case 'address':
                return Place::OBJECT_TYPE_ADDRESS;
                break;
            case 'stop_area':
                return Place::OBJECT_TYPE_STOP_AREA;
                break;
            case 'stop_point':
                return Place::OBJECT_TYPE_STOP_POINT;
                break;
            case 'poi':
                return Place::OBJECT_TYPE_POI;
                break;
            case 'administrative_region':
                return Place::OBJECT_TYPE_ADMIN;
                break;
            default:
                return Place::OBJECT_TYPE_UNKNOWN;
                break;
        }
    }
}