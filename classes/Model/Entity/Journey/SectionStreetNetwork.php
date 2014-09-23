<?php

namespace Nv2\Model\Entity\Journey;

use Nv2\Model\Entity\Journey\StreetNetworkPathItem;
use Nv2\Model\Entity\Journey\GeoJson;

class SectionStreetNetwork extends Section
{
    public $path;
    public $geojson;
    public $mode;

    private function __construct()
    {
        $this->path = array();
    }

    public static function create()
    {
        return new self();
    }

    public function fill($sectionFeed)
    {
        parent::fill($sectionFeed);
        
        $this->mode = $sectionFeed->mode;
        
        foreach ($sectionFeed->path as $path) {
            $this->path[] = StreetNetworkPathItem::create()
                ->fill($path);
        }
        $this->geojson = GeoJson::create()
            ->fill($sectionFeed->geojson);
    }
}