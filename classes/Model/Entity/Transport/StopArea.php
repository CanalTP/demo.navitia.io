<?php

namespace Nv2\Model\Entity\Transport;

use Nv2\Model\Entity\Base\Entity;
use Nv2\Model\Entity\Geo\Coord;
use Nv2\Lib\Nv2\Service\NavitiaRequest;

class StopArea extends Entity
{
    public $Name;
    public $Uri;
    public $Coords;
    public $AdminName;
    public $AdminZipCode;

    private function __construct()
    {
        $this->Name = null;
        $this->Uri = null;
        $this->Coords = null;
    }

    public static function create()
    {
        return new self();
    }

    public static function getList($lineUri=null)
    {
        $query = NavitiaRequest::create()->api('stop_areas');
        if ($lineUri) {
            $query->filter('line', 'uri', '=', $lineUri);
        }
        $feed = $query->execute();

        if (!$feed['hasError']) {
            $feed = json_decode($feed['content']);
            $list = array();

            if ($feed != null) {
                foreach ($feed->stop_areas as $stopArea) {
                    $list[] = self::create()
                        ->fill($stopArea);
                }

                return $list;
            } else {
                return NULL;
            }
        } else {
            return NULL;
        }
    }
    
    public static function getProximityList(Coord $coords, $distance)
    {
        $feed = NavitiaRequest::create()
            ->api('stop_areas')
            ->filter('stop_point', 'coord', NavitiaRequest::OPERATOR_DWITHIN, $coords->Lon . ',' . $coords->Lat, $distance)
            ->execute();
    
        if (!$feed['hasError']) {
            $feed = json_decode($feed['content']);
            $list = array();
    
            if ($feed != null && isset($feed->stop_areas)) {
                foreach ($feed->stop_areas as $stopArea) {
                    $list[] = self::create()
                        ->fill($stopArea);
                }
    
                return $list;
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    public function fill($stopAreaFeed)
    {
        $this->Name = $stopAreaFeed->name;
        $this->Uri = $stopAreaFeed->uri;

        $this->Coords = Coord::create()
            ->fill($stopAreaFeed->coord);
        
        return $this;
    }
}