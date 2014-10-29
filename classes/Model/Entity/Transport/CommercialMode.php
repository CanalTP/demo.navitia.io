<?php

namespace Nv2\Model\Entity\Transport;

use Nv2\Model\Entity\Base\Entity;

class CommercialMode extends Entity
{
    public $id;
    public $name;

    private function __construct()
    {
        $this->id = null;
        $this->name = null;
    }

    public static function create()
    {
        return new self();
    }

    public function fill($feed)
    {
        $this->id = $feed->id;
        $this->name = $feed->name;

        return $this;
    }
}