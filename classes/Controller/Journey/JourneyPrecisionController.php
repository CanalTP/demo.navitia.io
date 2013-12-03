<?php

namespace Nv2\Controller\Journey;

use Nv2\Lib\Nv2\Controller\Controller;
use Nv2\Lib\Nv2\Http\Request;
use Nv2\Model\Entity\Places\Place;

/**
 * 
 */
class JourneyPrecisionController extends Controller
{
    const ENTRY_POINT_ORIGIN = 1;
    const ENTRY_POINT_DESTINATION = 2;

    const RESPONSE_UNKNOWN_POINT = 1;
    const RESPONSE_ONE_SOLUTION = 2;
    const RESPONSE_MULTIPLE_SOLUTION = 3;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    /**
     * 
     */
    public function run()
    {
        $originPointResponse = $this->getEntryPointResponse(self::ENTRY_POINT_ORIGIN, $this->request->getParam(0));        
        $destinationPointResponse = $this->getEntryPointResponse(self::ENTRY_POINT_DESTINATION, $this->request->getParam(1));

        $this->template->setVariable('origin_point_response', $originPointResponse);
        $this->template->setVariable('destination_point_response', $destinationPointResponse);
        $this->template->setVariable('clockwise', $this->request->getParam(2));
        $this->template->setVariable('datetime', $this->request->getParam(3));

        $this->template->fetch('module/journey/precision.php');
    }

    /**
     * @param unknown $entryPointType
     * @param unknown $name
     * @return multitype:string Ambigous <NULL, unknown>
     */
    private function getEntryPointResponse($entryPointType, $name)
    {
        $placeList = $this->getEntryPointList($name);
        
        $placeCount = count($placeList);

        if ($placeCount == 0) {
            $responseType = self::RESPONSE_UNKNOWN_POINT;
        } else if ($placeCount == 1) {
            $responseType = self::RESPONSE_ONE_SOLUTION;
        } else {
            $responseType = self::RESPONSE_MULTIPLE_SOLUTION;
        }

        return array(
            'type' => $responseType,
            'entry' => urldecode($name),
            'pointList' => $placeList,
        );
    }

    /**
     * 
     * @param unknown $name
     * @return multitype:\Nv2\Model\NavitiaApi\Autocomplete\Autocomplete
     */
    private function getEntryPointList($name)
    {
        return Place::getList($name);
        /*
        return Autocomplete::create()
            ->name($name)
            ->depth(2)
            ->limit(15)
            ->getResultList();
        */
    }
}