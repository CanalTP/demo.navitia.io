<?php

namespace Nv2\Model\Entity\Journey;

use Nv2\Model\Entity\Base\Entity;
use Nv2\Model\Entity\Geo\Coord;

class StreetNetwork extends Entity
{
    public $Length;
    public $Duration;
    public $PathItemList;
    public $CoordinateList;

    private function __construct()
    {
        $this->Length = null;
        $this->Duration = null;
        $this->PathItemList = null;
        $this->CoordinateList = null;
    }

    public static function create()
    {
        return new self();
    }

    public function fill($feed)
    {
        $this->Length = $feed->length;

        if (isset($feed->path_items)) {
            foreach ($feed->path_items as $pathItem) {
                $pathItemObject = StreetNetworkPathItem::create()
                    ->fill($pathItem);
                $this->addItem($pathItemObject);
            }
        }
        if (isset($feed->coordinates)) {
            foreach ($feed->coordinates as $coord) {
                $coordObject = Coord::create()
                    ->fill($coord);
                $this->addCoordinate($coordObject);
            }
        }

        return $this;
    }

    public function addItem(StreetNetworkPathItem $item)
    {
        $this->PathItemList[] = $item;
    }

    public function addCoordinate(Coord $coord)
    {
        $this->CoordinateList[] = $coord;
    }
}