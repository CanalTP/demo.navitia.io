<?php

namespace Nv2\Model\Entity\Geo;

use Nv2\Model\Entity\Base\Entity;
use Nv2\Lib\Nv2\Service\NavitiaRequest;

class Region extends Entity
{
    public $ProductionDates;
    public $Status;
    public $Shape;
    public $RegionId;

    private function __construct()
    {
        $this->initProductionDates();
        $this->Status = null;
        $this->Shape = null;
        $this->RegionId = null;
    }

    public static function create()
    {
        return new self();
    }

    public static function getList()
    {
        $feed = NavitiaRequest::create()
            ->api('coverage')
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
        $this->ProductionDates['start'] = $startProductionDate;
        $this->ProductionDates['end'] = $endProductionDate;

        $this->Status = $feed->status;
        $this->Shape = json_decode($feed->shape);
        $this->Id = $feed->id;

        return $this;
    }

    private function initProductionDates()
    {
        $this->ProductionDates = array(
            'start' => null,
            'end' => null,
        );
    }
}