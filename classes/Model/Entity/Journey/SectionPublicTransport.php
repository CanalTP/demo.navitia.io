<?php

namespace Nv2\Model\Entity\Journey;

use Nv2\Model\Entity\Transport\StopPoint;
use Nv2\Model\Entity\Transport\StopDateTime;

class SectionPublicTransport extends Section
{
    public $id;
    public $type;
    public $departureDateTime;
    public $arrivalDateTime;
    public $from;
    public $to;
    public $displayInformations;
    public $additionalInformations;
    public $stopDateTimes;

    private function __construct()
    {
        $this->id = null;
        $this->type = null;
        $this->departureDateTime = null;
        $this->arrivalDateTime = null;
        $this->duration = null;
        $this->from = null;
        $this->to = null;
        $this->displayInformations = null;
        $this->additionalInformations = null;
        $this->stopDateTimes = null;
    }

    public static function create()
    {
        return new self();
    }

    public function fill($sectionFeed)
    {
        parent::fill($sectionFeed);

        $this->id = $sectionFeed->id;
        
        $stop_time_count = count($sectionFeed->stop_date_times);
        $this->departureDateTime = new \DateTime($sectionFeed->stop_date_times[0]->departure_date_time);
        $this->arrivalDateTime = new \DateTime($sectionFeed->stop_date_times[$stop_time_count - 1]->arrival_date_time);
        
        if (isset($sectionFeed->from->stop_point)) {
            $this->from = StopPoint::create()
                ->fill($sectionFeed->from->stop_point);
        }
        if (isset($sectionFeed->to->stop_point)) {
            $this->to = StopPoint::create()
                ->fill($sectionFeed->to->stop_point);
        }
        
        $this->fillDisplayInformations($sectionFeed);

        $lastIndex = count($sectionFeed->stop_date_times) - 1;
        foreach ($sectionFeed->stop_date_times as $index => $stopTimeFeed) {
            if ($index != 0 && $index != $lastIndex) {
                $stopTimeObject = StopDateTime::create()
                    ->fill($stopTimeFeed);
                $this->addStopDateTime($stopTimeObject);
            }
        }
    }

    public function addStopDateTime(StopDateTime $stopTime)
    {
        $this->stopDateTimes[] = $stopTime;
    }
    
    private function fillDisplayInformations($feed)
    {
        $this->displayInformations = array(
            'direction' => $feed->display_informations->direction,
            'code' => $feed->display_informations->code,
            'description' => $feed->display_informations->description,
            'color' => $feed->display_informations->color,
            'physical_mode' => $feed->display_informations->physical_mode,
            'headsign' => $feed->display_informations->headsign,
            'commercial_mode' => $feed->display_informations->commercial_mode,
            'equipments' => '',
            'network' => $feed->display_informations->network,
            'label' => $feed->display_informations->label,
        );
    }
    
}