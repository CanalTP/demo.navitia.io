<?php

namespace Nv2\Model\Entity\Schedule\DepartureBoards;

use Nv2\Model\Entity\Base\Entity;
use Nv2\Model\Entity\Transport\Route;
use Nv2\Model\Entity\Transport\Line;
use Nv2\Model\Entity\Transport\StopPoint;
use Nv2\Model\Entity\Geo\Coord;

class Board extends Entity
{
    public $stopPoint;
    public $route;
    public $schedules;
    public $additionalInformations;
    public $displayInformations;

    private function __construct()
    {
        $this->stopPoint = null;
        $this->route = null;
        $this->schedules = null;
        $this->additionalInformations = null;
        $this->displayInformations = null;
    }

    public static function create()
    {
        return new self();
    }

    public function fill($boardFeed)
    {
        if (isset($boardFeed->route)) {
            $this->route = Route::create()
                ->fill($boardFeed->route);
        }

        $this->stopPoint = StopPoint::create()
            ->fill($boardFeed->stop_point);

        if (isset($boardFeed->date_times)) {
            foreach ($boardFeed->date_times as $dateTime) {
                $this->addDateTime($dateTime);
            }
        }

        return $this;
    }
    
    public function updateProximityData(Coord $coords)
    {
        $distance = $this->stopPoint->coord->getDistanceFrom($coords);
        $this->stopPoint->distance = $distance;
    }

    public function addDateTime(\stdClass $dateTime)
    {
        $schedule = new \DateTime($dateTime->date_time);
        $this->schedules[$schedule->format('H')][] = $schedule->format('i');
    }
}