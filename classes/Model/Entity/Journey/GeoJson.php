<?php

namespace Nv2\Model\Entity\Journey;

use Nv2\Model\Entity\Base\Entity;
use Nv2\Model\Entity\Geo\Coord;

class GeoJson extends Entity
{
    public $type;
    public $properties;
    public $coordinates;
    
    private function __construct()
    {
        $this->type = null;
        $this->properties = null;
        $this->coordinates = null;
    }
    
    public static function create()
    {
        return new self();
    }
    
    public function fill($feed)
    {
        $this->type = $feed->type;
        foreach ($feed->coordinates as $coord) {
            $this->coordinates[] = Coord::createFromFloats($coord[0], $coord[1]);
        }
        return $this;
    }
}