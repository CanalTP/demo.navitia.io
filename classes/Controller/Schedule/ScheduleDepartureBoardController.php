<?php

namespace Nv2\Controller\Schedule;

use Nv2\Lib\Nv2\Controller\Controller;
use Nv2\Model\NavitiaApi\Schedule\DepartureBoards;
use Nv2\Model\Entity\Transport\Route;
use Nv2\Model\Entity\Transport\StopPoint;
use Nv2\Lib\Nv2\Http\Request;

/**
 * Contrôleur de la grille horaire à l'arrêt
 * @author Thomas Noury <thomas.noury@canaltp.fr>
 * @copyright 2012-2013 Canal TP
 */
class ScheduleDepartureBoardController extends Controller
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
        $departureBoard = $this->getDepartureBoard();
    
        // Création des informations résumées
        $boardSummary = $this->getBoardSummary();
    
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
    
        $this->template->setVariable('board_summary', $boardSummary);
        $this->template->setVariable('departure_board', $departureBoard);
        $this->template->setVariable('other_line_route_list', $otherLineList);
        $this->template->setVariable('other_stop_list', $otherStopList);
        $this->template->fetch('module/schedule/departure_board.php');
    }

    /**
     * @return array
     */
    private function getDepartureBoard()
    {
        return DepartureBoards::create()
            ->routeUri(urldecode($this->request->getParam(1)))
            ->stopPointUri(urldecode($this->request->getParam(2)))
            ->dateTime(urldecode($this->request->getParam(3)))
            ->getResult();
    }

    /**
     * Retourne un tableau associatif contenant les informations résumées de la grille horaire
     * @return array
     */
    private function getBoardSummary()
    {
        try {
            $datetime = new \DateTime($this->request->getParam(3));
        } catch (\Exception $e) {
            $datetime = new \DateTime();
        }

        return array(
            'line_uri' => urldecode($this->request->getParam(0)),
            'route_uri' => urldecode($this->request->getParam(1)),
            'stop_point_uri' => urldecode($this->request->getParam(2)),
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
    private function getOtherLineRouteList($stopAreaUri, $currentLineUri)
    {
        $routeList = Route::getList(null, $stopAreaUri);
        
        $finalRouteList = array();
        if (is_array($routeList)) {
            foreach ($routeList as $route) {
                if ($route->Line->Uri != $currentLineUri) {
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
    private function getOtherStopList($lineUri, $routeUri, $currentStopPointUri)
    {
        $stopList = StopPoint::getList($lineUri, $routeUri);
        
        $finalStopList = array();
        if (is_array($stopList)) {
            foreach ($stopList as $stop) {
                if ($stop->Uri != $currentStopPointUri) {
                    $finalStopList[] = $stop;
                }
            }
        }
        
        return $finalStopList;
    }
}