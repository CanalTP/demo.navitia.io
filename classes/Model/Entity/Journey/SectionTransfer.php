<?php

namespace Nv2\Model\Entity\Journey;

use Nv2\Model\Entity\Transport\StopPoint;

class SectionTransfer extends Section
{
    public $from;
    public $to;

    private function __construct()
    {
        $this->duration = null;
    }

    public static function create()
    {
        return new self();
    }

    public function fill($sectionFeed)
    {
        parent::fill($sectionFeed);
      
        $this->from = StopPoint::create()
            ->fill($sectionFeed->from->stop_point);
        $this->to = StopPoint::create()
            ->fill($sectionFeed->to->stop_point);
    }
}