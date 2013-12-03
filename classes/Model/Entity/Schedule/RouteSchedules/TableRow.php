<?php

namespace Nv2\Model\Entity\Schedule\RouteSchedules;

use Nv2\Model\Entity\Base\Entity;
use Nv2\Model\Entity\Transport\StopPoint;

class TableRow extends Entity
{
    public $StopPoint;
    public $StopTimeList;

    private function __construct()
    {
        $this->StopPoint = null;
        $this->StopTimeList = null;
    }

    public static function create()
    {
        return new self();
    }

    public function fill($feed)
    {
        $this->StopPoint = StopPoint::create()
            ->fill($feed->stop_point);

        if (isset($feed->stop_times)) {
            foreach ($feed->stop_times as $time) {
                $dateTime = new \DateTime($time);
                $this->StopTimeList[] = $dateTime->getTimestamp();
                unset($dateTime);
            }
        }

        return $this;
    }
}