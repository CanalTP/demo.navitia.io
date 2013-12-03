<?php

namespace Nv2\Model\Entity\Proximity;

class ProximityListItem extends Entity
{
    public $Name;
    public $Distance;
    public $Uri;
    public $Object;
    public $ObjectType;

    private function __construct()
    {
        $this->Name = null;
        $this->Distance = null;
        $this->Uri = null;
        $this->Object = null;
        $this->ObjectType = null;
    }

    public static function create()
    {
        return new self();
    }

    public function fill($feed)
    {
        $this->Name = $feed->name;
        $this->Distance = $feed->distance;
        $this->Uri = $feed->uri;
        $this->ObjectType = $feed->object->type;

        switch ($this->ObjectType) {
            case 'STOPAREA':
                $this->Object = StopArea::create()
                    ->fill($feed->object->stop_area);
                break;
            case 'STOPPOINT':
                $this->Object = StopPoint::create()
                    ->fill($feed->object->stop_point);
                break;
            case 'SITE':
                $this->Object = Site::create()
                    ->fill($feed->object->site);
                break;
            case 'ADDRESS':
                $this->Object = Address::create()
                    ->fill($feed->object->address);
                break;
            case 'CITY':
                $this->Object = City::create()
                    ->fill($feed->object->city);
                break;
            case 'COUNTRY':
                $this->Object = Country::create()
                    ->fill($feed->object->country);
                break;
        }

        return $this;
    }
}