<?php

namespace Nv2\Controller\Schedule;

use Nv2\Lib\Nv2\Controller\Controller;
use Nv2\Model\NavitiaApi\Schedule\RouteSchedules;
use Nv2\Model\Entity\Transport\Route;
use Nv2\Lib\Nv2\Http\Request;

/**
 * Contrôleur de la grille horaire de route
 * @author Thomas Noury <thomas.noury@canaltp.fr>
 * @copyright 2012-2013 Canal TP
 */
class ScheduleRouteScheduleController extends Controller
{
    /**
     * Initialisation
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    /**
     * Execution du contrôleur
     */
    public function run()
    {
        $routeSchedule = $this->getRouteSchedule();
        
        // Création des informations résumées
        $boardSummary = $this->getBoardSummary($routeSchedule);
        
        $routeList = $this->getOtherLineRouteList(
            urldecode($this->request->getParam(0)),
            urldecode($this->request->getParam(1))
        );
        
        $this->template->setVariable('route_schedule', $routeSchedule);
        $this->template->setVariable('board_summary', $boardSummary);
        $this->template->setVariable('other_line_route_list', $routeList);
        $this->template->fetch('module/schedule/route_schedule.php');
    }

    /**
     * 
     * @param unknown $departureBoard
     * @return multitype:string NULL
     */
    private function getBoardSummary($departureBoard)
    {
        $datetime = new \DateTime($this->request->getParam(2));
        
        return array(
            'line_uri' => urldecode($this->request->getParam(0)),
            'route_uri' => urldecode($this->request->getParam(1)),
            'datetime' => urldecode($this->request->getParam(2)),
            'timestamp' => $datetime->getTimestamp(),
        );
    }
    
    /**
     * 
     * @return Ambigous <NULL, Result>
     */
    public function getRouteSchedule()
    {
        return RouteSchedules::create()
            ->routeUri(urldecode($this->request->getParam(1)))
            ->dateTime(urldecode($this->request->getParam(2)))
            ->getResult();
    }
    
    /**
     * 
     * @param unknown $currentLineUri
     * @param unknown $currentRouteUri
     * @return multitype:Ambigous <NULL, multitype:\Nv2\Model\Entity\Transport\Route >
     */
    private function getOtherLineRouteList($currentLineId, $currentRouteId)
    {
        $routeList = Route::getFromLine($currentLineId);
        $finalRouteList = array();
        if (is_array($routeList)) {
            foreach ($routeList as $route) {
                if ($route->id != $currentRouteId) {
                    $finalRouteList[] = $route;
                }
            }
        }
        
        return $finalRouteList;
    }
}