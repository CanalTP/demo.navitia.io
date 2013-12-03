<?php

namespace Nv2\Model\Entity\Journey;

use Nv2\Model\Entity\Base\Entity;

class StreetNetworkPathItem extends Entity
{
    public $Name;
    public $Duration;
    public $Length;

    private function __construct()
    {
        $this->Name = null;
        $this->Duration = null;
        $this->Length = null;
    }

    public static function create()
    {
        return new self();
    }

    public function fill($feed)
    {
        $this->Name = $feed->name;
        $this->Length = (int)$feed->length;

        return $this;
    }
}