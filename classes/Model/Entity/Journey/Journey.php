<?php

namespace Nv2\Model\Entity\Journey;

use Nv2\Model\Entity\Base\Entity;
use Nv2\Model\Entity\Journey\Section;
use Nv2\Model\Entity\Transport\Fare;

class Journey extends Entity
{
    const DESCR_UNKNOWN = 0;
    const DESCR_PUBLIC_TRANSPORT = 1;
    const DESCR_WALK = 2;
    const DESCR_BIKE = 3;
    const DESCR_CAR = 4;
    const DESCR_ON_DEMAND_VIRTUAL = 101;
    const DESCR_ON_DEMAND_FULL = 102;
    const DESCR_ON_DEMAND_PARTIAL = 103;
    
    public $description;
    public $fare;
    public $sections;
    public $tags;
    public $departureDateTime;
    public $requestedDateTime;
    public $duration;
    public $nbTransfers;
    public $arrivalDateTime;
    public $type;

    private function __construct()
    {
        $this->description = self::DESCR_UNKNOWN;
        $this->fare = null;
        $this->sections = null;
        $this->tags = null;
        $this->departureDateTime = null;
        $this->requestedDateTime = null;
        $this->duration = null;
        $this->nbTransfers = null;
        $this->arrivalDateTime = null;
        $this->type = null;
    }

    public static function create()
    {
        return new self();
    }

    public function addSection(Section $section)
    {
        $this->sections[] = $section;
    }

    public function fill($journeyFeed)
    {
        $this->description = $this->getDescription($journeyFeed);

        if ($this->description == self::DESCR_PUBLIC_TRANSPORT) {
            $this->departureDateTime = new \DateTime($journeyFeed->departure_date_time);
            $this->arrivalDateTime = new \DateTime($journeyFeed->arrival_date_time);
            $this->requestedDateTime = new \DateTime($journeyFeed->requested_date_time);
        }
        
        $this->fare = Fare::create()
            ->fill($journeyFeed->fare);

        $this->duration = $journeyFeed->duration;
        $this->type = $journeyFeed->type;
        
        if (isset($journeyFeed->nb_transfers)) {
            $this->nbTransfers = $journeyFeed->nb_transfers;
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

    private function getDescription($feed)
    {
        $type = self::DESCR_UNKNOWN;
        if (count($feed->sections) == 1 && $feed->sections[0]->type == 'STREET_NETWORK') {
            // Une seule section "STREET_NETWORK" -> itinéraire marche à pied
            $type = self::DESCR_WALK;
        } else {
            // Au moins une section "PUBLIC_TRANSPORT" => itinéraire TC
            foreach ($feed->sections as $section) {
                if ($section->type == 'public_transport') {
                    $type = self::DESCR_PUBLIC_TRANSPORT;
                    break;
                }
            }
        }
        return $type;
    }
}