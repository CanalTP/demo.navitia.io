<?php

namespace Nv2\Model\Entity\Journey;

use Nv2\Model\Entity\Transport\StopPoint;
use Nv2\Model\Entity\Transport\VehicleJourney;
use Nv2\Model\Entity\Transport\StopDateTime;

class SectionPublicTransport extends Section
{
    public $DepartureTime;
    public $ArrivalTime;
    public $Duration;
    public $Origin;
    public $Destination;
    public $TransportDisplayInfo;
    public $TransportUriInfo;
    public $IntermediateStopList;

    private function __construct()
    {
        $this->DepartureTime = null;
        $this->ArrivalTime = null;
        $this->Duration = null;
        $this->Origin = null;
        $this->Destination = null;
        $this->TransportDisplayInfo = null;
        $this->TransportUriInfo = null;
        $this->IntermediateStopList = null;
    }

    public static function create()
    {
        return new self();
    }

    public function fill($sectionFeed)
    {
        parent::fill($sectionFeed);

        $stop_time_count = count($sectionFeed->stop_date_times);
        $this->DepartureTime = new \DateTime($sectionFeed->stop_date_times[0]->departure_date_time);
        $this->ArrivalTime = new \DateTime($sectionFeed->stop_date_times[$stop_time_count - 1]->arrival_date_time);

        $this->Origin = StopPoint::create()
            ->fill($sectionFeed->origin->stop_point);
        $this->Destination = StopPoint::create()
            ->fill($sectionFeed->destination->stop_point);
        
        $this->fillTransportInfo($sectionFeed);

        $lastIndex = count($sectionFeed->stop_date_times) - 1;
        foreach ($sectionFeed->stop_date_times as $index => $stopTimeFeed) {
            if ($index != 0 && $index != $lastIndex) {
                $stopTimeObject = StopDateTime::create()
                    ->fill($stopTimeFeed);
                $this->addIntermediateStop($stopTimeObject);
            }
        }
    }

    public function addIntermediateStop(StopDateTime $stopTime)
    {
        $this->IntermediateStopList[] = $stopTime;
    }
    
    private function fillTransportInfo($feed)
    {
        $this->TransportDisplayInfo = array(
            'network' => $feed->pt_display_informations->network,
            'line_code' => $feed->pt_display_informations->code,
            'line_color' => $feed->pt_display_informations->color,
            'direction' => $feed->pt_display_informations->direction,
            'headsign' => $feed->pt_display_informations->headsign,
            'physical_mode' => $feed->pt_display_informations->physical_mode,
            'commercial_mode' => $feed->pt_display_informations->commercial_mode,
        );
        
        $this->TransportUriInfo = array(
            'network' => $feed->uris->network,
            'line' => $feed->uris->line,
            'route' => $feed->uris->route,
            'vehicle_journey' => $feed->uris->vehicle_journey,
            'physical_mode' => $feed->uris->physical_mode,
            'commercial_mode' => $feed->uris->commercial_mode,
        );
    }
    
}