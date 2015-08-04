<?php

namespace Nv2\Model\Entity\Transport;

use Nv2\Model\Entity\Base\Entity;
use Nv2\Model\Entity\Geo\Coord;
use Nv2\Model\Entity\Data\Comment;
use Nv2\Model\Entity\Transport\Admin;
use Nv2\Lib\Nv2\Service\NavitiaRequest;

class StopArea extends Entity
{
    public $id;
    public $name;
    public $coord;
    public $administrativeRegions;
    public $comments;

    private function __construct()
    {
        $this->id = null;
        $this->name = null;
        $this->coord = null;
        $this->administrativeRegions = null;
        $this->comments = array();
    }

    public static function create()
    {
        return new self();
    }

    public static function getListFromLine($lineId)
    {
        return self::getList(NavitiaRequest::create()
            ->api('coverage')
            ->resource('stop_areas')
            ->with('lines', $lineId)
        );
    }

    public static function getProximityList(Coord $coords, $distance)
    {
        $feed = NavitiaRequest::create()
            ->api('stop_areas')
            ->filter('stop_point', 'coord', NavitiaRequest::OPERATOR_DWITHIN, $coords->lon . ',' . $coords->lat, $distance)
            ->execute();
        $list = array();
        if (!$feed['hasError']) {
            $feed = json_decode($feed['content']);

            if ($feed != null && isset($feed->stop_areas)) {
                foreach ($feed->stop_areas as $stopArea) {
                    $list[] = self::create()
                        ->fill($stopArea);
                }
            }
        }
        return $list;
    }

    private static function getList(NavitiaRequest $request)
    {
        $feed = $request->execute();
        $result = array();
        if (!$feed['hasError']) {
            $feed = json_decode($feed['content']);
            if ($feed != null && isset($feed->stop_areas)) {
                foreach ($feed->stop_area as $stopArea) {
                    $result[] = StopArea::create()
                        ->fill($stopArea);
                }
            }
        }
        return $result;
    }

    public function fill($stopAreaFeed)
    {
        $this->id = $stopAreaFeed->id;
        $this->name = $stopAreaFeed->name;
        $this->coord = Coord::create()
            ->fill($stopAreaFeed->coord);

        if (isset($stopAreaFeed->comments)) {
            foreach ($stopAreaFeed->comments as $comment) {
                $this->comments[] = Comment::create()
                    ->fill($comment);
            }
        }

        if (isset($stopAreaFeed->administrative_regions)) {
            foreach ($stopAreaFeed->administrative_regions as $admin) {
                $this->administrativeRegions[] = Admin::create()
                    ->fill($admin);
            }
        }

        return $this;
    }
}
