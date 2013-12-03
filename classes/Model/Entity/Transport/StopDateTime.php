<?php

namespace Nv2\Model\Entity\Transport;

use Nv2\Model\Entity\Base\Entity;

class StopDateTime extends Entity
{
    public $ArrivalTime;
    public $DepartureTime;
    public $StopPoint;

    private function __construct()
    {
        $this->ArrivalTimestamp = null;
        $this->DepartureTimestamp = null;
        $this->StopPoint = null;
    }

    public static function create()
    {
        return new self();
    }

    public function fill($feed)
    {
        $this->DepartureTime = new \DateTime($feed->departure_date_time);
        $this->ArrivalTime = new \DateTime($feed->arrival_date_time);

        $stopPointObject = StopPoint::create()
            ->fill($feed->stop_point);
        $this->StopPoint = $stopPointObject;

        return $this;
    }
}