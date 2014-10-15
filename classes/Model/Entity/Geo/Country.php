<?php

namespace Nv2\Model\Entity\Geo;

use Nv2\Model\Entity\Base\Entity;

class Country extends Entity
{
    public $name;
    public $id;
    public $coord;

    private function __construct()
    {
        $this->name = null;
        $this->id = null;
        $this->coord = null;
    }

    public static function create()
    {
        return new self();
    }

    public function fill($feed)
    {
        $this->name = $feed->name;
        $this->id = $feed->id;

        $this->coord = Coord::create()
            ->fill($feed->coord);

        return $this;
    }
}
