<?php

namespace Nv2\Model\Entity\Journey;

class SectionWaiting extends Section
{
    private function __construct()
    {
        
    }

    public static function create()
    {
        return new self();
    }

    public function fill($sectionFeed)
    {
        parent::fill($sectionFeed);
    }
}