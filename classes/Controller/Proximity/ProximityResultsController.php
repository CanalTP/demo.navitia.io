<?php

namespace Nv2\Controller\Proximity;

use Nv2\Lib\Nv2\Controller\Controller;
use Nv2\Model\Entity\Transport\Line;
use Nv2\Model\Entity\Transport\StopArea;
use Nv2\Model\Entity\Transport\StopPoint;
use Nv2\Model\Entity\Geo\Coord;
use Nv2\Model\Entity\Geo\Poi;
use Nv2\Lib\Nv2\Service\NavitiaRequest;
use Nv2\Lib\Nv2\Service\ParallelServiceRequest;
use Nv2\Model\Entity\Transport\JourneyPatternPoint;

class ProximityResultsController extends Controller
{
    const PROXIMITY_DISTANCE = 250;
    
    public function __construct($request)
    {
        parent::__construct($request);
    }

    public function run()
    {
        $coords = Coord::createFromString(urldecode($this->request->getParam(1)));
        
        $lineList = $this->getProximityLines($coords);
        $linePointList = $this->getLinePoints($lineList);
        $stopAreaList = $this->getProximityPoints($coords);

        $this->template->setVariable('line_list', $lineList);
        $this->template->setVariable('line_point_list', $linePointList);        
        $this->template->setVariable('stop_area_list', $stopAreaList);
        $this->template->setVariable('search_place', $this->request->getParam(0));
        
        $this->template->fetch('module/proximity/results.php');
    }
    
    /**
     * Récupère les lignes à proximité du point de recherche
     * @param Coord $coords
     * @return array(Line)
     */
    private function getProximityLines(Coord $coords)
    {
        return Line::getProximityList($coords, self::PROXIMITY_DISTANCE);
    }

    /**
     * Récupère les points (pois, arrêts) à proximité de la recherche
     * @param Coord $coords
     * @return array
     */
    private function getProximityPoints(Coord $coords)
    {
        return array(
            'pois' => null, //Poi::getProximityList($coords, self::PROXIMITY_DISTANCE),
            'stop_points' => StopPoint::getProximityList($coords, self::PROXIMITY_DISTANCE),
            'stop_areas' => null,
        );
    }
    
    /**
     * Retourne la liste des points par ligne pour effectuer des tracés sur la carte
     * @param array $lines
     * @return array
     */
    private function getLinePoints($lines)
    {
        $routePointsFeeds = $this->getRoutePointsFeeds($lines);
        $pointList = $this->getRoutePoints($routePointsFeeds);
        
        return $pointList;
    }
    
    /**
     * Retourne la liste des flux issus de requêtes parallèles pour obtenir
     * les points pour chaque route de chaque ligne
     * @param array $lines
     * @return array
     */
    private function getRoutePointsFeeds($lines)
    {
        $multiRequest = ParallelServiceRequest::create();
        
        foreach ($lines as $line) {
            foreach ($line->Routes as $route) {
                $request = NavitiaRequest::create()
                    ->api('journey_pattern_points')
                    ->filter('route', 'uri', '=', $route->Uri)
                    ->param('depth', 2)
                    ->flag('line_uri', $line->Uri);
        
                $multiRequest->addRequest($request);
            }
        }
        
        return $multiRequest->retrieveAllFeedsContent();
    }
    
    /**
     * Retourne la liste des JourneyPatternPoints classés par Ligne
     * @param array
     * @return array
     */
    private function getRoutePoints($routePointsFeeds)
    {
        $pointList = array();
        
        foreach ($routePointsFeeds as $feed) {
            $feedContent = json_decode($feed['content']);
            foreach ($feedContent->journey_pattern_points as $point) {
                $jppObject = JourneyPatternPoint::create()
                    ->fill($point);
                
                $stopAreaUri = $jppObject->StopPoint->StopArea->Uri;
                
                $pointList[$feed['flags']['line_uri']][$stopAreaUri] = $jppObject;
            }
        }
        
        return $pointList;
    }
}