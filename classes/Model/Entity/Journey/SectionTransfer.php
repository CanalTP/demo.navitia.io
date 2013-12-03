<?php

namespace Nv2\Model\Entity\Journey;

use Nv2\Model\Entity\Transport\StopPoint;

class SectionTransfer extends Section
{
    public $Duration;
    public $Origin;
    public $Destination;

    private function __construct()
    {
        $this->Duration = null;
    }

    public static function create()
    {
        return new self();
    }

    public function fill($sectionFeed)
    {
        parent::fill($sectionFeed);
        $this->Duration = $sectionFeed->duration;
      
        $this->Origin = StopPoint::create()
            ->fill($sectionFeed->origin->stop_point);
        $this->Destination = StopPoint::create()
            ->fill($sectionFeed->destination->stop_point);
    }
}