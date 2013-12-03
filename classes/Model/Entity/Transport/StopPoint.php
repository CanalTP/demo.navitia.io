<?php

namespace Nv2\Model\Entity\Transport;

use Nv2\Model\Entity\Base\Entity;
use Nv2\Model\Entity\Geo\Coord;
use Nv2\Lib\Nv2\Service\NavitiaRequest;

class StopPoint extends Entity
{
    public $Uri;
    public $Name;
    public $Coord;
    public $StopArea;
    public $Distance;
    public $AdminName;
    public $AdminZipCode;

    private function __construct()
    {
        $this->Uri = null;
        $this->Name = null;
        $this->Coord = null;
        $this->StopArea = null;
        $this->Distance = null;
        $this->AdminName = null;
        $this->AdminZipCode = null;
    }

    public static function create()
    {
        return new self();
    }

    public static function getList($lineUri=null, $routeUri=null, $stopAreaUri=null)
    {
        $query = NavitiaRequest::create()->api('stop_points');
        if ($lineUri) {
            $query->filter('line', 'uri', '=', $lineUri);
        }
        if ($routeUri) {
            $query->filter('route', 'uri', '=', $routeUri);
        }
        if ($stopAreaUri) {
            $query->filter('stop_area', 'uri', '=', $stopAreaUri);
        }
        $query->param('depth', 2);
        $feed = $query->execute();

        if (!$feed['hasError']) {
            $feed = json_decode($feed['content']);
            $list = array();

            if ($feed != null && isset($feed->stop_points)) {
                foreach ($feed->stop_points as $stopPoint) {
                    $list[] = StopPoint::create()
                        ->fill($stopPoint);
                }

                return $list;
            } else {
                return null;
            }
        } else {
            return null;
        }
    }
    
    public static function getProximityList(Coord $coords, $distance)
    {
        $feed = NavitiaRequest::create()
            ->api('stop_points')
            ->filter('stop_point', 'coord', NavitiaRequest::OPERATOR_DWITHIN, $coords->Lon . ',' . $coords->Lat, $distance)
            ->execute();
    
        if (!$feed['hasError']) {
            $feed = json_decode($feed['content']);
            $list = array();
    
            if ($feed != null && isset($feed->stop_points)) {
                foreach ($feed->stop_points as $stopPoint) {
                    $list[] = self::create()
                        ->fill($stopPoint);
                }
    
                return $list;
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    public function fill($stopPointFeed)
    {
        $this->Uri = $stopPointFeed->uri;
        $this->Name = $stopPointFeed->name;

        if (isset($stopPointFeed->coord)) {
            $this->Coord = Coord::create()
                ->fill($stopPointFeed->coord);
        }
        
        if (isset($stopPointFeed->stop_area)) {
            $this->StopArea = StopArea::create()
                ->fill($stopPointFeed->stop_area);
        }
        
        $this->fillAdminName($stopPointFeed);
        $this->fillAdminZipCode($stopPointFeed);

        return $this;
    }
    
    private function fillAdminName($feed)
    {        
        if (isset($feed->admins) && is_array($feed->admins)) {
            foreach ($feed->admins as $admin) {
                if (isset($admin->name) && $admin->name != '') {
                    $this->AdminName = $admin->name;
                }
            }
        }
    }
    
    private function fillAdminZipCode($feed)
    {
        if (isset($feed->admins) && is_array($feed->admins)) {
            foreach ($feed->admins as $admin) {
                if (isset($admin->zip_code) && $admin->zip_code != '') {
                    $this->AdminZipCode = $admin->zip_code;
                }
            }
        }
    }
}