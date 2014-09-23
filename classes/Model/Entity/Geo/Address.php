<?php

namespace Nv2\Model\Entity\Geo;

use Nv2\Model\Entity\Base\Entity;

class Address extends Entity
{
    public $houseNumber;
    public $id;
    public $name;
    public $coord;

    private function __construct()
    {
        $this->houseNumber = null;
        $this->id = null;
        $this->name = null;
        $this->coord = null;
    }

    public static function create()
    {
        return new self();
    }

    public function fill($feed)
    {
        //$this->HouseNumber = $feed->house_number;
        $this->id = $feed->uri;
        $this->name = $feed->name;

        $this->coord = Coord::create()
            ->fill($feed->coord);

        return $this;
    }
}