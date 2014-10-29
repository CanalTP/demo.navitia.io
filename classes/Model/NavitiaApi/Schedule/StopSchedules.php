<?php

namespace Nv2\Model\NavitiaApi\Schedule;

use Nv2\Model\NavitiaApi\Base\NavitiaApi;
use Nv2\Model\Entity\Schedule\DepartureBoards\Board;
use Nv2\Lib\Nv2\Service\NavitiaRequest;

class StopSchedules extends NavitiaApi
{
    // request
    protected $stopPointId;
    protected $datetime;
    protected $coords;
    protected $distance;

    // response
    public $boardList;

    const ERROR_NULL = 'no_error';

    private function __construct()
    {
        $this->routeId = null;
        $this->stopPointId = null;
        $this->datetime = null;
        $this->coords = null;
        $this->distance = null;

        $this->boardList = null;
    }

    public static function create()
    {
        return new self();
    }

    public function stopPointId($id)
    {
        $this->stopPointId = $id;
        return $this;
    }

    public function routeId($id)
    {
        $this->routeId = $id;
        return $this;
    }

    public function dateTime($datetime)
    {
        $this->datetime = $datetime;
        return $this;
    }
    
    public function coords($coords)
    {
        $this->coords = $coords;
        return $this;        
    }
    
    public function distance($distance)
    {
        $this->distance = $distance;
        return $this;        
    }

    public function getResult()
    {
        if ($this->coords != null) {
            throw new \Exception('StopSchedules::getResult does not work with coords... yet!');
            /* $feed = NavitiaRequest::create()
                ->api('departure_boards')
                ->filter('stop_point', 'coord', NavitiaRequest::OPERATOR_DWITHIN, $this->coords->getString(','), $this->distance)
                ->param('from_datetime', $this->datetime)
                ->param('depth', 2)
                ->execute();*/
        } else {
            $feed = NavitiaRequest::create()
                ->api('coverage')
                ->resource('stop_schedules')
                ->with('stop_points', $this->stopPointId)
                ->param('from_datetime', $this->datetime)
                ->execute();
        }
        
        $feed = json_decode($feed['content']);
        
        if (isset($feed->stop_schedules)) {
            foreach ($feed->stop_schedules as $board) {
                $boardObject = Board::create()
                    ->fill($board);
                $this->addBoard($boardObject);
            }
        }

        return $this->boardList;
    }

    public function addBoard(Board $board)
    {
        $this->boardList[] = $board;
    }
}