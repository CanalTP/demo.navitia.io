<?php

namespace Nv2\Model\Entity\Transport;

use Nv2\Model\Entity\Base\Entity;

class StopDateTime extends Entity
{
    public $arrivalDateTime;
    public $departureDateTime;
    public $stopPoint;

    private function __construct()
    {
        $this->arrivalDateTime = null;
        $this->departureDateTime = null;
        $this->stopPoint = null;
    }

    public static function create()
    {
        return new self();
    }

    public function fill($feed)
    {
        $this->departureDateTime = new \DateTime($feed->departure_date_time);
        $this->arrivalDateTime = new \DateTime($feed->arrival_date_time);

        $stopPointObject = StopPoint::create()
            ->fill($feed->stop_point);
        $this->stopPoint = $stopPointObject;

        return $this;
    }
}