<?php

namespace Nv2\Controller\Schedule;

use Nv2\Lib\Nv2\Controller\Controller;
use Nv2\Model\NavitiaApi\Schedule\DepartureBoards;
use Nv2\Lib\Nv2\Http\Request;
use Nv2\Model\Entity\Geo\Coord;
use Nv2\Lib\Nv2\Config\Config;

/**
 * Contrôleur des grilles horaire à proximité
 * @author Thomas Noury <thomas.noury@canaltp.fr>
 * @copyright 2012-2013 Canal TP
 */
class ScheduleProximityBoardController extends Controller
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
        $coords = Coord::createFromString($this->request->getParam(0));
        $searchDate = urldecode($this->request->getParam(1));
        $now = new \DateTime();
        $now->setTime($now->format('H'), $now->format('i'), 0);
        $now = $now->format(Config::get('format', 'Date', 'Iso8861Full'));
        
        $departureBoards = $this->getDepartureBoards($coords, $searchDate);
        
        if (is_array($departureBoards)) {
            foreach ($departureBoards as $i => $board) {
                $departureBoards[$i]->updateProximityData($coords);
            }
        }
        
        $this->template->setVariable('current_coords', $coords);
        $this->template->setVariable('search_date', $searchDate);
        $this->template->setVariable('current_date', $now);
        $this->template->setVariable('departure_boards', $departureBoards);
        $this->template->fetch('module/schedule/proximity_board.php');
    }
    
    private function getDepartureBoards(Coord $coords, $date)
    {
        return DepartureBoards::create()
            ->coords($coords)
            ->distance(250)
            ->dateTime($date)
            ->getResult();      
    }
}