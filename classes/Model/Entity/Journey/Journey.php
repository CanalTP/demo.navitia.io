<?php

namespace Nv2\Model\Entity\Journey;

use Nv2\Model\Entity\Base\Entity;
use Nv2\Model\Entity\Journey\Section;

class Journey extends Entity
{
    const TYPE_UNKNOWN = 0;
    const TYPE_PUBLIC_TRANSPORT = 1;
    const TYPE_WALK = 2;
    const TYPE_BIKE = 3;
    const TYPE_CAR = 4;
    const TYPE_ON_DEMAND_VIRTUAL = 101;
    const TYPE_ON_DEMAND_FULL = 102;
    const TYPE_ON_DEMAND_PARTIAL = 103;

    public $Duration;
    public $NbTransfers;
    public $RequestedTimestamp;
    public $SectionList;
    public $DepartureTimestamp;
    public $ArrivalTimestamp;
    public $Type;

    private function __construct()
    {
        $this->Duration = null;
        $this->NbTransfers = null;
        $this->RequestedTimestamp = null;
        $this->SectionList = null;
        $this->DepartureTimestamp = null;
        $this->ArrivalTimestamp = null;
        $this->Type = self::TYPE_UNKNOWN;
    }

    public static function create()
    {
        return new self();
    }

    public function addSection(Section $section)
    {
        $this->SectionList[] = $section;
    }

    public function fill($journeyFeed)
    {
        $this->Type = $this->getType($journeyFeed);

        if ($this->Type == self::TYPE_PUBLIC_TRANSPORT) {
            $departureDateTime = new \DateTime($journeyFeed->departure_date_time);
            $arrivalDateTime = new \DateTime($journeyFeed->arrival_date_time);
            $this->DepartureTimestamp = $departureDateTime->getTimestamp();
            $this->ArrivalTimestamp = $arrivalDateTime->getTimestamp();
            $requestedDateTime = new \DateTime($journeyFeed->requested_date_time);
            $this->RequestedTimestamp = $requestedDateTime->getTimestamp();
        }

        $this->Duration = $journeyFeed->duration;
        if (isset($journeyFeed->nb_transfers)) {
            $this->NbTransfers = $journeyFeed->nb_transfers;
        }

        foreach ($journeyFeed->sections as $sectionFeed) {
            switch ($sectionFeed->type) {
                case Section::TYPE_STREET_NETWORK:
                    $sectionObject = SectionStreetNetwork::create();
                    break;
                case Section::TYPE_PUBLIC_TRANSPORT:
                    $sectionObject = SectionPublicTransport::create();
                    break;
                case Section::TYPE_TRANSFER:
                    $sectionObject = SectionTransfer::create();
                    break;
                case Section::TYPE_WAITING:
                    $sectionObject = SectionWaiting::create();
                    break;
                default:
                    $sectionObject = null;
                    break;
            }
            if ($sectionObject) {
                $sectionObject->fill($sectionFeed);
                $this->addSection($sectionObject);
            }
        }

        return $this;
    }

    private function getType($feed)
    {
        if (count($feed->sections) == 1 && $feed->sections[0]->type == 'STREET_NETWORK') {
            // Une seule section "STREET_NETWORK" -> itinéraire marche à pied
            return self::TYPE_WALK;
        } else {
            // Au moins une section "PUBLIC_TRANSPORT" => itinéraire TC
            foreach ($feed->sections as $section) {
                if ($section->type == 'PUBLIC_TRANSPORT') {
                    return self::TYPE_PUBLIC_TRANSPORT;
                    break;
                }
            }
        }
    }
}