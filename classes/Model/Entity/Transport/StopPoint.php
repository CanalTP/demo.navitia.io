<?php

namespace Nv2\Model\Entity\Transport;

use Nv2\Model\Entity\Base\Entity;
use Nv2\Model\Entity\Geo\Coord;
use Nv2\Model\Entity\Data\Comment;
use Nv2\Lib\Nv2\Service\NavitiaRequest;

class StopPoint extends Entity
{
    public $id;
    public $name;
    public $coord;
    public $stopArea;
    public $address;
    public $administrativeRegions;
    public $comments;

    private function __construct()
    {
        $this->id = null;
        $this->name = null;
        $this->coord = null;
        $this->stopArea = null;
        $this->address = null;
        $this->administrativeRegions = null;
        $this->comments = array();
    }

    public static function create()
    {
        return new self();
    }

    public static function getFromLine($lineId)
    {
        return self::getList(NavitiaRequest::create()
            ->api('coverage')
            ->resource('stop_points')
            ->with('lines', $lineId)
        );
    }

    public static function getFromRoute($routeId)
    {
        return self::getList(NavitiaRequest::create()
            ->api('coverage')
            ->resource('stop_points')
            ->with('routes', $routeId)
        );
    }

    public static function getFromStopArea($stopAreaId)
    {
        return self::getList(NavitiaRequest::create()
            ->api('coverage')
            ->resource('stop_points')
            ->with('stop_areas', $stopAreaId)
        );
    }

    public static function getProximityList(Coord $coords, $distance)
    {
        return self::getList(NavitiaRequest::create()
            ->api('coords')
            ->resource('places_nearby')
            ->with('coords', $coords->getLonLat())
            ->filter('distance', $distance)
            ->filter('type[]', 'stop_point')
        );
    }

    private static function getList(NavitiaRequest $request)
    {
        $feed = $request->execute();
        $result = array();
        if (!$feed['hasError']) {
            $feed = json_decode($feed['content']);
            if ($feed != null && isset($feed->stop_points)) {
                foreach ($feed->stop_points as $stopPoint) {
                    $result[] = StopPoint::create()
                        ->fill($stopPoint);
                }
            }
        }
        return $result;
    }

    public function fill($stopPointFeed)
    {
        $this->id = $stopPointFeed->id;
        $this->name = $stopPointFeed->name;

        if (isset($stopPointFeed->coord)) {
            $this->coord = Coord::create()
                ->fill($stopPointFeed->coord);
        }

        if (isset($stopPointFeed->stop_area)) {
            $this->stopArea = StopArea::create()
                ->fill($stopPointFeed->stop_area);
        }

        if (isset($stopPointFeed->comments)) {
            foreach ($stopPointFeed->comments as $comment) {
                $this->comments[] = Comment::create()
                    ->fill($comment);
            }
        }

        //$this->fillAdminName($stopPointFeed);
        //$this->fillAdminZipCode($stopPointFeed);

        return $this;
    }
}