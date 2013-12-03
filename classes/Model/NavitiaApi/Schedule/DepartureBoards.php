<?php

namespace Nv2\Model\NavitiaApi\Schedule;

use Nv2\Model\NavitiaApi\Base\NavitiaApi;
use Nv2\Lib\Nv2\Service\NavitiaRequest;
use Nv2\Model\Entity\Schedule\DepartureBoards\Board;

class DepartureBoards extends NavitiaApi
{
    // request
    protected $routeUri;
    protected $stopPointUri;
    protected $datetime;
    protected $coords;
    protected $distance;

    // response
    public $BoardList;

    const ERROR_NULL = 'no_error';

    private function __construct()
    {
        $this->routeUri = null;
        $this->stopPointUri = null;
        $this->datetime = null;
        $this->coords = null;
        $this->distance = null;

        $this->BoardList = null;
    }

    public static function create()
    {
        return new self();
    }

    public function stopPointUri($uri)
    {
        $this->stopPointUri = $uri;
        return $this;
    }

    public function routeUri($uri)
    {
        $this->routeUri = $uri;
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
            $feed = NavitiaRequest::create()
                ->api('departure_boards')
                ->filter('stop_point', 'coord', NavitiaRequest::OPERATOR_DWITHIN, $this->coords->getString(','), $this->distance)
                ->param('from_datetime', $this->datetime)
                ->param('depth', 2)
                ->execute();
        } else {
            $feed = NavitiaRequest::create()
                ->api('departure_boards')
                ->filter('stop_point', 'uri', '=', $this->stopPointUri)
                ->filter('route', 'uri', '=', $this->routeUri)
                ->param('from_datetime', $this->datetime)
                ->param('depth', 2)
                ->execute();
        }
        
        $feed = json_decode($feed['content']);
        
        if (isset($feed->departure_boards)) {
            foreach ($feed->departure_boards as $board) {
                $boardObject = Board::create()
                    ->fill($board);
                $this->addBoard($boardObject);
            }
        }

        return $this->BoardList;
    }

    public function addBoard(Board $board)
    {
        $this->BoardList[] = $board;
    }
}