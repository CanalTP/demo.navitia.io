<?php

namespace Nv2\Model\Entity\Geo;

use Nv2\Model\Entity\Base\Entity;

class City extends Entity
{
    public $name;
    public $id;
    public $zipCode;
    public $coord;

    private function __construct()
    {
        $this->name = null;
        $this->id = null;
        $this->zipCode = null;
        $this->coord = null;
    }

    public static function create()
    {
        return new self();
    }

    public function fill($feed)
    {
        $this->name = $feed->name;
        $this->id = $feed->uri;
        //$this->ZipCode = $feed->zip_code;

        $this->coord = CCoord::create()
            ->fill($feed->coord);

        return $this;
    }
}
