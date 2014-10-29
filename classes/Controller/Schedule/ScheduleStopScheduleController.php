<?php

namespace Nv2\Controller\Schedule;

use Nv2\Lib\Nv2\Controller\Controller;
use Nv2\Model\NavitiaApi\Schedule\StopSchedules;
use Nv2\Model\Entity\Transport\Route;
use Nv2\Model\Entity\Transport\StopPoint;
use Nv2\Lib\Nv2\Http\Request;

/**
 * Contrôleur de la grille horaire à l'arrêt
 * @author Thomas Noury <thomas.noury@canaltp.fr>
 * @copyright 2012-2014 Canal TP
 */
class ScheduleStopScheduleController extends Controller
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
     * Execution du controleur
     */
    public function run()
    {
        // Récupération de la grille horaire
        $schedules = $this->getStopSchedules();
        
        //var_dump($schedules);
        //exit();
    
        // Création des informations résumées
        $summary = $this->getSummary();
    
        // Récupération des lignes qui passent pas l'arrêt
        $otherLineList = $this->getOtherLineRouteList(
            urldecode($this->request->getParam(2)),
            urldecode($this->request->getParam(0))
        );
        // Récupération des arrêts de la ligne
        $otherStopList = $this->getOtherStopList(
            urldecode($this->request->getParam(0)),
            urldecode($this->request->getParam(1)),
            urldecode($this->request->getParam(2))
        );
    
        $this->template->setVariable('summary', $summary);
        $this->template->setVariable('schedules', $schedules);
        $this->template->setVariable('other_line_route_list', $otherLineList);
        $this->template->setVariable('other_stop_list', $otherStopList);
        $this->template->fetch('module/schedule/stop_schedules.php');
    }

    /**
     * @return array
     */
    private function getStopSchedules()
    {
        return StopSchedules::create()
            ->routeId(urldecode($this->request->getParam(1)))
            ->stopPointId(urldecode($this->request->getParam(2)))
            ->dateTime(urldecode($this->request->getParam(3)))
            ->getResult();
    }

    /**
     * Retourne un tableau associatif contenant les informations résumées de la grille horaire
     * @return array
     */
    private function getSummary()
    {
        try {
            $datetime = new \DateTime($this->request->getParam(3));
        } catch (\Exception $e) {
            $datetime = new \DateTime();
        }

        return array(
            'line_id' => urldecode($this->request->getParam(0)),
            'route_id' => urldecode($this->request->getParam(1)),
            'stop_point_id' => urldecode($this->request->getParam(2)),
            'datetime' => urldecode($this->request->getParam(3)),
            'timestamp' => $datetime->getTimestamp(),
        );
    }

    /**
     * Retourne un tableau de Routes en fonction des uris 
     * @param string $stopAreaUri
     * @param string $currentLineUri
     * @return array
     */
    private function getOtherLineRouteList($stopAreaId, $currentLineId)
    {
        $routeList = Route::getFromStopArea($stopAreaId);
        
        $finalRouteList = array();
        if (is_array($routeList)) {
            foreach ($routeList as $route) {
                if ($route->line->id != $currentLineId) {
                    $finalRouteList[] = $route;
                }
            }
        }

        return $finalRouteList;
    }

    /**
     * Retourne un tableau d'objets StopPoint en fonction d'uri de Line, Route ou StopPoint
     * @param string $lineUri
     * @param string $routeUri
     * @param string $currentStopPointUri
     * @return array
     */
    private function getOtherStopList($lineId, $routeId, $currentStopPointId)
    {
        $stopList = StopPoint::getFromRoute($routeId);
        
        $finalStopList = array();
        if (is_array($stopList)) {
            foreach ($stopList as $stop) {
                if ($stop->id != $currentStopPointId) {
                    $finalStopList[] = $stop;
                }
            }
        }
        
        return $finalStopList;
    }
}