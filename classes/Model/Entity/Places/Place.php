<?php

namespace Nv2\Model\Entity\Places;

use Nv2\Model\Entity\Base\Entity;
use Nv2\Model\Entity\Transport\StopArea;
use Nv2\Model\Entity\Transport\StopPoint;
use Nv2\Model\Entity\Geo\Address;
use Nv2\Model\Entity\Geo\Poi;
use Nv2\Model\Entity\Transport\Admin;
use Nv2\Lib\Nv2\Service\NavitiaRequest;

class Place extends Entity
{
    const OBJECT_TYPE_ADDRESS = 'address';
    const OBJECT_TYPE_STOP_AREA = 'stop_area';
    const OBJECT_TYPE_STOP_POINT = 'stop_point';
    const OBJECT_TYPE_ADMIN = 'administrative_region';
    const OBJECT_TYPE_POI = 'poi';
    const OBJECT_TYPE_UNKNOWN = 'unknown';
    
    public $name;
    public $quality;
    public $id;
    public $object;
    public $objectType;
    public $adminName;
    public $adminZipCode;

    private function __construct()
    {
        $this->name = null;
        $this->quality = null;
        $this->id = null;
        $this->object = null;
        $this->objectType = null;
        $this->adminName = null;
        $this->adminZipCode = null;
    }

    public static function create()
    {
        return new self();
    }
    
    public static function getList($query)
    {
        $feed = NavitiaRequest::create()
            ->api('coverage')
            ->resource('places')
            ->param('q', $query)
            ->execute();
        $list = array();
        if (!$feed['hasError']) {
            $feed = json_decode($feed['content']);
            if ($feed != null) {
                foreach ($feed->places as $place) {
                    $list[] = self::create()
                        ->fill($place);
                }
            }
        }
        return $list;
    }

    public function fill($feed)
    {
        if (isset($feed->quality)) {
            $this->quality = $feed->quality;
        }
        if (isset($feed->id)) {
            $this->id = $feed->id;
        }
        if (isset($feed->name)) {
            $this->name = $feed->name;
        }
        
        $this->objectType = $this->getObjectType($feed);
        $this->fillObject($feed);
        
        return $this;
    }
    
    private function fillObject($feed)
    {        
        switch ($this->objectType) {
            case self::OBJECT_TYPE_STOP_AREA:
                $this->object = StopArea::create()
                    ->fill($feed->stop_area);
                break;
            case self::OBJECT_TYPE_STOP_POINT:
                $this->object = StopPoint::create()
                    ->fill($feed->stop_point);
                break;
            case self::OBJECT_TYPE_ADDRESS:
                $this->object = Address::create()
                    ->fill($feed->address);
                break;
            case self::OBJECT_TYPE_ADMIN:
                $this->object = Admin::create()
                    ->fill($feed->administrative_region);
                break;
            case self::OBJECT_TYPE_POI:
                $this->object = Poi::create()
                    ->fill($feed->poi);
            default:
                break;
        }
    }
    
    private function getObjectType($feed)
    {        
        return $feed->embedded_type;
    }
}