<?php

namespace Nv2\Model\Entity\Geo;

use Nv2\Model\Entity\Base\Entity;

class Address extends Entity
{
    public $HouseNumber;
    public $Uri;
    public $Name;
    public $Coord;

    private function __construct()
    {
        $this->HouseNumber = null;
        $this->Uri = null;
        $this->Name = null;
        $this->Coord = null;
    }

    public static function create()
    {
        return new self();
    }

    public function fill($feed)
    {
        //$this->HouseNumber = $feed->house_number;
        $this->Uri = $feed->uri;
        $this->Name = $feed->name;

        $this->Coord = Coord::create()
            ->fill($feed->coord);

        return $this;
    }
}