<?php

namespace Nv2\Model\Entity\Geo;

use Nv2\Model\Entity\Base\Entity;
use Nv2\Lib\Nv2\Service\NavitiaRequest;

class Poi extends Entity
{
    public $Name;
    public $Uri;
    public $Coord;

    private function __construct()
    {
        $this->Name = null;
        $this->Uri = null;
        $this->Coord = null;
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
            ->filter('stop_point', 'coord', NavitiaRequest::OPERATOR_DWITHIN, $coords->Lon . ',' . $coords->Lat, $distance)
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
        $this->Name = $feed->name;
        $this->Uri = $feed->uri;

        $this->Coord = Coord::create()
            ->fill($feed->coord);

        return $this;
    }
}