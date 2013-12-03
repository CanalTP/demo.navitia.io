<?php

namespace Nv2\Model\Entity\Journey;

class SectionWaiting extends Section
{
    public $Duration;

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
    }
}