<?php

namespace Nv2\Model\NavitiaApi\Journey;

use Nv2\Model\NavitiaApi\Base\NavitiaApi;
use Nv2\Model\Entity\Journey\Journey;
use Nv2\Lib\Nv2\Service\NavitiaRequest;

class Journeys extends NavitiaApi
{
    public $OriginUri;
    public $DestinationUri;
    public $WayPointUri;
    public $Datetime;
    public $Clockwise;
    public $JourneyList;

    public $PreviousUriComponents;
    public $NextUriComponents;

    private $forbiddenUris;

    const ERROR_NULL = 'no_error';
    const ERROR_NAVITIA = 'error_navitia';
    const ERROR_DATE_OUT_OF_BOUNDS = 'error_date_out_of_bounds';
    const ERROR_NO_ORIGIN_POINT = 'error_no_origin_point';
    const ERROR_NO_DESTINATION_POINT = 'error_no_destination_point';
    const ERROR_NO_ORIGIN_NOR_DESTINATION_POINT = 'error_no_origin_nor_destination_point';
    const ERROR_NO_SOLUTION = 'error_no_solution';

    private function __construct()
    {
        $datetime = new \DateTime();
        $this->Datetime = $datetime->format('Ymd\THis');
        $this->Clockwise = 'true';
    }

    public static function create()
    {
        return new self();
    }

    public function setPointsUri($originUri, $destinationUri, $wayPointUri='')
    {
        $this->OriginUri = $originUri;
        $this->DestinationUri = $destinationUri;
        $this->WayPointUri = $wayPointUri;

        return $this;
    }

    public function setForbiddenUris($uris)
    {
        if ($uris != null) {
            $this->forbiddenUris = $uris;
        }

        return $this;
    }

    public function setClockwise($clockwise)
    {
        $this->Clockwise = $clockwise == 'arrival' ? 'false' : 'true';

        return $this;
    }

    public function setDateTime($datetimestring)
    {
        $this->Datetime = $datetimestring;

        return $this;
    }

    public function fill($journeyListFeed)
    {
        $responseType = null;
        if (isset($journeyListFeed->reponse_type)) {
            $responseType = $journeyListFeed->response_type;
        }
        switch ($responseType) {
            case 'DATE_OUT_OF_BOUNDS': return self::ERROR_DATE_OUT_OF_BOUNDS; break;
            case 'NO_ORIGIN_POINT': return self::ERROR_NO_ORIGIN_POINT; break;
            case 'NO_DESTINATION_POINT': return self::ERROR_NO_DESTINATION_POINT; break;
            case 'NO_ORIGIN_NOR_DESTINATION_POINT': return self::ERROR_NO_ORIGIN_NOR_DESTINATION_POINT; break;
            case 'NO_SOLUTION': return self::ERROR_NO_SOLUTION; break;
            default:
                if (isset($journeyListFeed->prev)) {
                    $this->PreviousUriComponents = $this->getUriStringComponents($journeyListFeed->prev);
                }
                if (isset($journeyListFeed->next)) {
                    $this->NextUriComponents = $this->getUriStringComponents($journeyListFeed->next);
                }
                if (isset($journeyListFeed->journeys)) {
                    foreach ($journeyListFeed->journeys as $journeyFeed) {
                        $journeyObject = Journey::create()
                            ->fill($journeyFeed);
                        $this->addJourney($journeyObject);
                    }
                    return Journeys::ERROR_NULL;
                }
                break;
        }
    }

    public function addJourney(Journey $journey)
    {
        $this->JourneyList[] = $journey;
    }

    public function getResult()
    {
        $hasError = false;
        $errorCode = Journeys::ERROR_NULL;

        $feed = NavitiaRequest::create()
            ->api('journeys')
            ->param('origin', $this->OriginUri)
            ->param('destination', $this->DestinationUri)
            ->param('datetime', $this->Datetime)
            ->param('clockwise', $this->Clockwise)
            ->param('forbidden_uris', $this->forbiddenUris)
            ->execute();

        if (!$feed['hasError']) {
            $errorCode = $this->fill(json_decode($feed['content']));
            if ($errorCode != Journeys::ERROR_NULL) {
                $hasError = true;
            }
        } else {
            $errorCode = Journeys::ERROR_NAVITIA;
            $hasError = true;
        }

        return array(
            'hasError' => $hasError,
            'errorCode' => $errorCode,
            'data' => $this,
        );
    }

    private function getUriStringComponents($str)
    {
        $components = array();
        $data = explode('&', $str);
        foreach ($data as $param) {
            $p = explode('=', $param);
            $components[$p[0]] = $p[1];
        }

        return $components;
    }
}