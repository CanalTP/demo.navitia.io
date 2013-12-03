<?php

namespace Nv2\Controller\Proximity;

use Nv2\Lib\Nv2\Controller\Controller;
use Nv2\Lib\Nv2\Config\Config;
use Nv2\Model\Entity\Places\Place;

class ProximityPrecisionController extends Controller
{
    const ENTRY_POINT_ADDRESS = 1;
    const ENTRY_POINT_SITE = 2;
    const ENTRY_POINT_STOP_AREA = 3;
    const ENTRY_POINT_CITY = 4;

    const RESPONSE_UNKNOWN_POINT = 1;
    const RESPONSE_ONE_SOLUTION = 2;
    const RESPONSE_MULTIPLE_SOLUTION = 3;

    public function __construct($request)
    {
        parent::__construct($request);
    }

    public function run()
    {
        $pointResponse = $this->getResponse($this->request->getParam(0));

        // Une seule solution : affichage des résultats
        if ($pointResponse['type'] == self::RESPONSE_ONE_SOLUTION) {
            $this->redirect('proximity/map/one_solution');
        }

        $this->template->setVariable('point_response', $pointResponse);

        $this->template->fetch('module/proximity/precision.php');
    }

    private function getResponse($name)
    {
        $pointCount = 0;

        $response = array(
            'type' => null,
            'entry' => urldecode($name),
            'pointList' => array()
        );

        if (Config::get('proximity', 'Precision', 'EntryPointSiteActive') == true) {
            $siteList = $this->getEntryPointList(self::ENTRY_POINT_SITE, $name);
            $pointCount += count($siteList['data']->ItemList);
            $response['pointList']['site'] = $siteList['data']->ItemList;
        }
        if (Config::get('proximity', 'Precision', 'EntryPointAddressActive') == true) {
            $addressList = $this->getEntryPointList(self::ENTRY_POINT_ADDRESS, $name);
            $pointCount += count($addressList['data']->ItemList);
            $response['pointList']['address'] = $addressList['data']->ItemList;
        }
        if (Config::get('proximity', 'Precision', 'EntryPointStopAreaActive') == true) {
            $stopAreaList = $this->getEntryPointList(self::ENTRY_POINT_STOP_AREA, $name);
            $pointCount += count($stopAreaList['data']->ItemList);
            $response['pointList']['stopArea'] = $stopAreaList['data']->ItemList;
        }
        if (Config::get('proximity', 'Precision', 'EntryPointCityActive') == true) {
            $cityList = $this->getEntryPointList(self::ENTRY_POINT_CITY, $name);
            $pointCount += count($cityList['data']->ItemList);
            $response['pointList']['city'] = $cityList['data']->ItemList;
        }

        if ($pointCount == 0) {
            $response['type'] = self::RESPONSE_UNKNOWN_POINT;
        } else if ($pointCount == 1) {
            $response['type'] = self::RESPONSE_ONE_SOLUTION;
        } else {
            $response['type'] = self::RESPONSE_MULTIPLE_SOLUTION;
        }

        return $response;
    }

    private function getEntryPointList($entryPointType, $name)
    {
        
    }
}