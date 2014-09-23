<?php

namespace Nv2\Model\Entity\Geo;

use Nv2\Model\Entity\Base\Entity;
use Nv2\Lib\Nv2\Service\NavitiaRequest;

class Poi extends Entity
{
    public $name;
    public $id;
    public $coord;

    private function __construct()
    {
        $this->name = null;
        $this->id = null;
        $this->coord = null;
    }

    public static function create()
    {
        return new self();
    }
    
    public static function getList()
    {
        
    }
    
    public static function getProximityList(Coord $coords, $distance)
    {
        $feed = NavitiaRequest::create()
            ->api('pois')
            ->filter('stop_point', 'coord', NavitiaRequest::OPERATOR_DWITHIN, $coords->lon . ',' . $coords->lat, $distance)
            ->execute();
        
        if (!$feed['hasError']) {
            $feed = json_decode($feed['content']);
            $list = array();
        
            if ($feed != null && isset($feed->pois)) {
                foreach ($feed->pois as $poi) {
                    $list[] = self::create()
                        ->fill($poi);
                }
        
                return $list;
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    public function fill($feed)
    {
        $this->name = $feed->name;
        $this->id = $feed->uri;

        $this->coord = Coord::create()
            ->fill($feed->coord);

        return $this;
    }
}