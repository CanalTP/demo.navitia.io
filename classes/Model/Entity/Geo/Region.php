<?php

namespace Nv2\Model\Entity\Geo;

use Nv2\Model\Entity\Base\Entity;
use Nv2\Lib\Nv2\Service\NavitiaRequest;

class Region extends Entity
{
    public $productionDates;
    public $status;
    public $shape;
    public $regionId;

    private function __construct()
    {
        $this->initProductionDates();
        $this->status = null;
        $this->shape = null;
        $this->regionId = null;
    }

    public static function create()
    {
        return new self();
    }

    public static function getList()
    {
        $feed = NavitiaRequest::create()
            ->api('coverage')
            ->disableRegion()
            ->execute();

        $list = array();
        
        if (!$feed['hasError']) {
            $feed = json_decode($feed['content']);

            if ($feed != null) {
                foreach ($feed->regions as $region) {
                    $list[] = self::create()
                        ->fill($region);
                }
                
            }
        }
        return $list;
    }

    public function fill($feed)
    {
        try {
            $startProductionDate = new \DateTime($feed->start_production_date);
        } catch (\Exception $e) {
            $startProductionDate = null;
        }
        try {
            $endProductionDate = new \DateTime($feed->end_production_date);
        } catch (\Exception $e) {
            $endProductionDate = null;
        }
        $this->productionDates['start'] = $startProductionDate;
        $this->productionDates['end'] = $endProductionDate;

        $this->status = $feed->status;
        $this->shape = json_decode($feed->shape);
        $this->id = $feed->id;

        return $this;
    }

    private function initProductionDates()
    {
        $this->productionDates = array(
            'start' => null,
            'end' => null,
        );
    }
}
