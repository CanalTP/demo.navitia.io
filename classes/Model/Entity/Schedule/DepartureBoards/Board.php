<?php

namespace Nv2\Model\Entity\Schedule\DepartureBoards;

use Nv2\Model\Entity\Base\Entity;
use Nv2\Model\Entity\Transport\Route;
use Nv2\Model\Entity\Transport\Line;
use Nv2\Model\Entity\Transport\StopPoint;
use Nv2\Model\Entity\Geo\Coord;

class Board extends Entity
{
    public $StopPoint;
    public $Route;
    public $BoardItems;

    private function __construct()
    {
        $this->StopPoint = null;
        $this->Route = null;
        $this->BoardItems = null;
    }

    public static function create()
    {
        return new self();
    }

    public function fill($boardFeed)
    {
        if (isset($boardFeed->route)) {
            $this->Route = Route::create()
                ->fill($boardFeed->route);
        }

        $this->StopPoint = StopPoint::create()
            ->fill($boardFeed->stop_point);

        if (isset($boardFeed->board_items)) {
            foreach ($boardFeed->board_items as $item) {
                $itemObject = BoardItem::create()
                    ->fill($item);
                $this->addBoardItem($itemObject);
            }
        }

        return $this;
    }
    
    public function updateProximityData(Coord $coords)
    {
        $distance = $this->StopPoint->Coord->getDistanceFrom($coords);
        $this->StopPoint->Distance = $distance;
    }

    public function addBoardItem(BoardItem $item)
    {
        $this->BoardItems[] = $item;
    }
}