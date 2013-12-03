<?php

namespace Nv2\Model\Entity\Transport;

use Nv2\Model\Entity\Base\Entity;

class VehicleJourney extends Entity
{
    public $Name;
    public $Uri;
    public $Route;
    public $PhysicalMode;

    private function __construct()
    {
        $this->Name = null;
        $this->Uri = null;
        $this->Route = null;
        $this->PhysicalMode = null;
    }

    public static function create()
    {
        return new self();
    }

    public function fill($feed)
    {
        $this->Name = $feed->name;
        $this->Uri = $feed->uri;

        $routeObject = Route::create()
            ->fill($feed->route);
        $this->Route = $routeObject;

        $physicalModeObject = PhysicalMode::create()
            ->fill($feed->physical_mode);
        $this->PhysicalMode = $physicalModeObject;

        return $this;
    }
}