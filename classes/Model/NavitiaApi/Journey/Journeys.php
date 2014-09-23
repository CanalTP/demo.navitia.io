<?php

namespace Nv2\Model\NavitiaApi\Journey;

use Nv2\Model\NavitiaApi\Base\NavitiaApi;
use Nv2\Model\Entity\Journey\Journey;
use Nv2\Lib\Nv2\Service\NavitiaRequest;

class Journeys extends NavitiaApi
{
    public $fromId;
    public $toId;
    public $datetime;
    public $datetimeRepresents;
    public $journeys;

    public $previousUriComponents;
    public $nextUriComponents;

    private $forbiddenUris;

    const ERROR_NULL = 'no_error';
    const ERROR_NAVITIA = 'error_navitia';
    const ERROR_DATE_OUT_OF_BOUNDS = 'error_date_out_of_bounds';
    const ERROR_NO_ORIGIN_POINT = 'error_no_origin_point';
    const ERROR_NO_DESTINATION_POINT = 'error_no_destination_point';
    const ERROR_NO_ORIGIN_NOR_DESTINATION_POINT = 'error_no_origin_nor_destination_point';
    const ERROR_NO_SOLUTION = 'error_no_solution';
    
    const DATETIME_REPRESENTS_DEPARTURE = 'departure';
    const DATETIME_REPRESENTS_ARRIVAL = 'arrival';

    private function __construct()
    {
        $datetime = new \DateTime();
        $this->datetime = $datetime->format('Ymd\THis');
        $this->datetimeRepresents = 'departure';
    }

    public static function create()
    {
        return new self();
    }

    public function setPointsIds($fromId, $toId)
    {
        $this->fromId = $fromId;
        $this->toId = $toId;
        return $this;
    }

    public function setForbiddenUris($uris)
    {
        if ($uris != null) {
            $this->forbiddenUris = $uris;
        }
        return $this;
    }

    public function setDatetimeRepresents($datetimeRepresents = self::DATETIME_REPRESENTS_DEPARTURE)
    {
        $this->datetimeRepresents = $datetimeRepresents;
        return $this;
    }

    public function setDateTime($datetimestring)
    {
        $this->datetime = $datetimestring;
        return $this;
    }

    public function fill($journeyListFeed)
    {
        $responseType = null;
        if (isset($journeyListFeed->error)) {
            $responseType = $journeyListFeed->error->id;
        }
        
        switch ($responseType) {
            case 'date_out_of_bounds': $response = self::ERROR_DATE_OUT_OF_BOUNDS; break;
            case 'no_origin_point': $response = self::ERROR_NO_ORIGIN_POINT; break;
            case 'no_destination_point': $response = self::ERROR_NO_DESTINATION_POINT; break;
            case 'no_origin_nor_destination_point': $response = self::ERROR_NO_ORIGIN_NOR_DESTINATION_POINT; break;
            case 'no_solution': $response = self::ERROR_NO_SOLUTION; break;
            default:
                if (isset($journeyListFeed->prev)) {
                    $this->previousUriComponents = $this->getUriStringComponents($journeyListFeed->prev);
                }
                if (isset($journeyListFeed->next)) {
                    $this->nextUriComponents = $this->getUriStringComponents($journeyListFeed->next);
                }
                if (isset($journeyListFeed->journeys)) {
                    foreach ($journeyListFeed->journeys as $journeyFeed) {
                        $journeyObject = Journey::create()
                            ->fill($journeyFeed);
                        $this->addJourney($journeyObject);
                    }
                    $response = Journeys::ERROR_NULL;
                }
                break;
        }
        
        return $response;
    }

    public function addJourney(Journey $journey)
    {
        $this->journeys[] = $journey;
    }

    public function getResult()
    {
        $hasError = false;
        $errorCode = Journeys::ERROR_NULL;

        $feed = NavitiaRequest::create()
            ->api('journeys')
            ->param('from', $this->fromId)
            ->param('to', $this->toId)
            ->param('datetime', $this->datetime)
            ->param('datetime_represents', $this->datetimeRepresents)
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