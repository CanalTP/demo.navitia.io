<?php

namespace Nv2\Model\Entity\Geo;

use Nv2\Model\Entity\Base\Entity;

class City extends Entity
{
    public $Name;
    public $Uri;
    public $ZipCode;
    public $Coord;

    private function __construct()
    {
        $this->Name = null;
        $this->Uri = null;
        $this->ZipCode = null;
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
        //$this->ZipCode = $feed->zip_code;

        $this->Coord = CCoord::create()
            ->fill($feed->coord);

        return $this;
    }
}