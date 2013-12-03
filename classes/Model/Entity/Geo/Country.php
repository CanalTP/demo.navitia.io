<?php

namespace Nv2\Model\Entity\Geo;

use Nv2\Model\Entity\Base\Entity;

class Country extends Entity
{
    public $Name;
    public $Uri;
    public $Coord;

    private function __construct()
    {
        $this->Name = null;
        $this->Uri = null;
        $this->Coord = null;
    }

    public static function create()
    {
        return new self();
    }

    public function fill($feed)
    {
        $this->Name = $feed->name;
        $this->Uri = $feed->uri;

        $this->Coord = Coord::create()
            ->fill($feed->coord);

        return $this;
    }
}