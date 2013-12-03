<?php

namespace Nv2\Controller\Journey;

use Nv2\Lib\Nv2\Controller\Controller;
use Nv2\Model\NavitiaApi\Journey\Journeys;
use Nv2\Lib\Nv2\Http\Request;
use Nv2\Model\Entity\Places\Place;
use Nv2\Model\Entity\Data\Uri;
use Nv2\Model\Entity\Geo\Address;
use Nv2\Lib\Nv2\Config\Config;

/**
 * 
 * @author tnoury
 *
 */
class JourneyResultsController extends Controller
{
    /**
     * 
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
        // Calcul de l'itinéraire
        $journeyResult = $this->getJourneyResult();

        // Création du résumé, nécessite un résultat, sinon les données sont récupérées de l'URL
        $journeySummary = $this->getJourneySummary($journeyResult);

        $roadMapTitle = $this->getRoadMapTitle($journeyResult, $journeySummary);
        
        $this->template->setVariable('roadMapTitle', $roadMapTitle);
        $this->template->setVariable('journeySummary', $journeySummary);
        $this->template->setVariable('journeyResult', $journeyResult);

        // Passage des données aux templates
        if ($this->request->getParam('outputType') == 'ajaxResult') {
        	$this->template->fetch('module/journey/ajax-results.php');
        	$this->template->setPagelayoutActive(false);
        } else {
        	$this->template->fetch('module/journey/results.php');
        }
    }

    /**
     * Récupération de la liste des solutions d'une recherche
     * @return multitype:\Nv2\Model\NavitiaApi\Journey\Journeys boolean string
     */
    private function getJourneyResult()
    {
        return Journeys::create()
            ->setPointsUri(
                urldecode($this->request->getParam(1)),
                urldecode($this->request->getParam(3)))
            ->setClockwise($this->request->getParam(4))
            ->setDateTime($this->request->getParam(5))
            ->setForbiddenUris($this->request->getParam('avoidUri'))
            ->getResult();
    }

    /**
     * Récupération des informations résumées de l'itinéraire recherché 
     * @param unknown $journeyResult
     * @return multitype:number string NULL
     */
    private function getJourneySummary($journeyResult)
    {        
        $datetime = new \DateTime($this->request->getParam(5));

        return array(
            'origin_name' => urldecode($this->request->getParam(0)),
            'origin_uri' => $this->request->getParam(1),
            'origin_coords' => null,
            'destination_name' => urldecode($this->request->getParam(2)),
            'destination_uri' => $this->request->getParam(3),
            'destination_coords' => null,
            'timestamp' => $datetime->getTimestamp(),
            'clockwise' => $this->request->getParam(4),
            'datetime' => $this->request->getParam(5),
            'nbchanges' => 0,
            'duration' => 0,
            'totalcost' => 0,
            'links' => $this->getJourneyLinks($journeyResult),
        );
    }
    
    private function getCoordFromUri($uri)
    {
        $type = Uri::create(urldecode($uri))
            ->getType();
        switch ($type) {
            case Place::OBJECT_TYPE_ADDRESS:
                return Address::getOne($uri)
                    ->getCoords();
                break;
        }   
    }
    
    private function getRoadMapTitle($journeyResults, $journeySummary)
    {
        $journeyOrigin = '';
        $journeyDestination = '';
        
        if ($journeySummary['origin_name'] != '-') {
            $journeyOrigin = $journeySummary['origin_name'];
            $journeyDestination = $journeySummary['destination_name'];
        } else {
            if (!$journeyResults['hasError']) {
                if (isset($journeyResults['data']->JourneyList[0]->SectionList[0]->Origin->Name)) {
                    $journeyOrigin = $journeyResults['data']
                        ->JourneyList[0]
                        ->SectionList[0]
                        ->Origin
                        ->Name;
                } else if (isset($journeyResults['data']->JourneyList[0]->SectionList[0]->StreetNetwork->PathItemList[0]->Name)) {
                    $journeyOrigin = $journeyResults['data']
                        ->JourneyList[0]
                        ->SectionList[0]
                        ->StreetNetwork
                        ->PathItemList[0]
                        ->Name;
                }
                $lastSectionIndex = count($journeyResults['data']->JourneyList[0]->SectionList) - 1;
                if (isset($journeyResults['data']->JourneyList[0]->SectionList[$lastSectionIndex]->Destination->Name)) {
                    $journeyDestination = $journeyResults['data']
                        ->JourneyList[0]
                        ->SectionList[$lastSectionIndex]
                        ->Destination
                        ->Name;
                } else {
                    $lastPathItemIndex = count($journeyResults['data']->JourneyList[0]->SectionList[$lastSectionIndex]->StreetNetwork->PathItemList) - 1;
                    if ($journeyResults['data']->JourneyList[0]->SectionList[$lastSectionIndex]->StreetNetwork->PathItemList[$lastPathItemIndex]->Name) {
                        $journeyDestination = $journeyResults['data']
                            ->JourneyList[0]
                            ->SectionList[$lastSectionIndex]
                            ->StreetNetwork
                            ->PathItemList[$lastPathItemIndex]
                            ->Name;
                    }
                }
            }
        }
        
        return array(
            'origin' => $journeyOrigin,
            'destination' => $journeyDestination,
        );
    }

    /**
     * 
     * @return multitype:string
     */
    private function getJourneyLinks($journeyResult)
    {        
    	return array(
     		'reverse_route' => 'journey/results/'
    			. $this->request->getParam('2') . '/'
				. $this->request->getParam('3') . '/'
				. $this->request->getParam('0') . '/'
				. $this->request->getParam('1') . '/'
				. $this->request->getParam('4') . '/'
				. $this->request->getParam('5'),
        	'modify_search' => 'journey/search/'
    			. $this->request->getParam('0') . '/'
				. $this->request->getParam('1') . '/'
				. $this->request->getParam('2') . '/'
				. $this->request->getParam('3') . '/'
				. $this->request->getParam('4') . '/'
				. $this->request->getParam('5'),
    	    'sooner' => $this->getSoonerLink($journeyResult),
    	    'later' => $this->getLaterLink($journeyResult),
    	);
    }
    
    private function getSoonerLink($journeyResult)
    {
        $datetime = new \DateTime($journeyResult['data']->PreviousUriComponents['datetime']);
        
        $url = 'journey/results/'
            . $this->request->getParam('0') . '/'
			. $this->request->getParam('1') . '/'
			. $this->request->getParam('2') . '/'
			. $this->request->getParam('3') . '/'
			. 'arrival/'
			. $datetime->format(Config::get('format', 'Date', 'Iso8861Full'));
        
        return $url;
    }
    
    private function getLaterLink($journeyResult)
    {
        $datetime = new \DateTime($journeyResult['data']->NextUriComponents['datetime']);
        
        $url = 'journey/results/'
            . $this->request->getParam('0') . '/'
            . $this->request->getParam('1') . '/'
            . $this->request->getParam('2') . '/'
            . $this->request->getParam('3') . '/'
            . 'departure/'
            . $datetime->format(Config::get('format', 'Date', 'Iso8861Full'));
        
        return $url;
    }
}