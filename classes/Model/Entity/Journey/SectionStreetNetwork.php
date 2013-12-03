<?php

namespace Nv2\Model\Entity\Journey;

class SectionStreetNetwork extends Section
{
    public $StreetNetwork;

    private function __construct()
    {
        $this->StreetNetwork = null;
    }

    public static function create()
    {
        return new self();
    }

    public function fill($sectionFeed)
    {
        parent::fill($sectionFeed);

        $this->StreetNetwork = StreetNetwork::create()
            ->fill($sectionFeed->street_network);
    }
}