<?php

namespace Nv2\Model\Entity\Places;

use Nv2\Model\Entity\Base\Entity;
use Nv2\Model\Entity\Transport\StopArea;
use Nv2\Model\Entity\Transport\StopPoint;
use Nv2\Model\Entity\Geo\Address;
use Nv2\Model\Entity\Transport\Admin;
use Nv2\Lib\Nv2\Service\NavitiaRequest;

class Place extends Entity
{
    const OBJECT_TYPE_ADDRESS = 'address';
    const OBJECT_TYPE_STOP_AREA = 'stop_area';
    const OBJECT_TYPE_STOP_POINT = 'stop_point';
    const OBJECT_TYPE_ADMIN = 'admin';
    const OBJECT_TYPE_POI = 'poi';
    const OBJECT_TYPE_UNKNOWN = 'unknown';
    
    public $Name;
    public $FullName;
    public $Quality;
    public $Uri;
    public $Object;
    public $ObjectType;
    public $AdminName;
    public $AdminZipCode;

    private function __construct()
    {
        $this->Name = null;
        $this->FullName = null;
        $this->Quality = null;
        $this->Uri = null;
        $this->Object = null;
        $this->ObjectType = null;
        $this->AdminName = null;
        $this->AdminZipCode = null;
    }

    public static function create()
    {
        return new self();
    }
    
    public static function getList($query)
    {
        $feed = NavitiaRequest::create()
            ->api('places')
            ->param('q', $query)
            ->execute();
        
        if (!$feed['hasError']) {
            $feed = json_decode($feed['content']);
            $list = array();
        
            if ($feed != null) {
                foreach ($feed->places as $place) {
                    $list[] = self::create()
                    ->fill($place);
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
        if (isset($feed->quality)) {
            $this->Quality = $feed->quality;
        }
        if (isset($feed->uri)) {
            $this->Uri = $feed->uri;
        }
        if (isset($feed->name)) {
            $this->FullName = $feed->name;
        }
        
        $this->ObjectType = $this->getObjectType($feed);

        $this->fillObject($feed);
        $this->fillName($feed);
        $this->fillAdminName($feed);
        $this->fillAdminZipCode($feed);
        
        return $this;
    }
    
    private function fillName($feed)
    {        
        switch ($this->ObjectType) {
            case self::OBJECT_TYPE_ADDRESS:
            case self::OBJECT_TYPE_ADMIN:
            case self::OBJECT_TYPE_POI:
            case self::OBJECT_TYPE_STOP_AREA:
            case self::OBJECT_TYPE_STOP_POINT:
                $this->Name = $this->Object->Name;
                break;
            default:
                $this->Name = $feed->name;
                break;           
        }  
    }
    
    private function fillObject($feed)
    {
        switch ($this->ObjectType) {
            case self::OBJECT_TYPE_STOP_AREA:
                $this->Object = StopArea::create()
                ->fill($feed->stop_area);
                break;
            case self::OBJECT_TYPE_STOP_POINT:
                $this->Object = StopPoint::create()
                ->fill($feed->stop_point);
                break;
            case self::OBJECT_TYPE_ADDRESS:
                $this->Object = Address::create()
                ->fill($feed->address);
                break;
            case self::OBJECT_TYPE_ADMIN:
                if (isset($feed->admins[0])) {
                    $this->Object = Admin::create()
                    ->fill($feed->admins[0]);
                }
                break;
            default:
                break;
        }
    }
    
    private function fillAdminName($feed)
    {
        $adminList = $this->getAdminList($feed, $this->ObjectType);
        
        if (is_array($adminList)) {
            foreach ($adminList as $admin) {
                if (isset($admin->name) && $admin->name != '') {
                    $this->AdminName = $admin->name;
                }
            }
        }
    }
    
    private function fillAdminZipCode($feed)
    {
        $adminList = $this->getAdminList($feed, $this->ObjectType);
        
        if (is_array($adminList)) {
            foreach ($adminList as $admin) {
                if (isset($admin->zip_code) && $admin->zip_code != '') {
                    $this->AdminZipCode = $admin->zip_code;
                }
            }
        }
    }
    
    private function getAdminList($feed, $type)
    {
        switch ($type) {
            case self::OBJECT_TYPE_ADDRESS:
                if (isset($feed->address->administrative_regions)) {
                    return $feed->address->administrative_regions;
                }
                break;
            case self::OBJECT_TYPE_ADMIN:
                if (isset($feed->administrative_region)) {
                    return $feed->administrative_region;
                }
                break;
            case self::OBJECT_TYPE_POI:
                if (isset($feed->poi->administrative_regions)) {
                    return $feed->poi->administrative_regions;
                }
                break;
            case self::OBJECT_TYPE_STOP_AREA:
                if (isset($feed->stop_area->administrative_regions)) {
                    return $feed->stop_area->administrative_regions;
                }
                break;
            case self::OBJECT_TYPE_STOP_POINT:
                if (isset($feed->stop_point->administrative_regions)) {
                    return $feed->stop_point->administrative_regions;
                }
                break;
        }
        return null;
    }
    
    private function getObjectType($feed)
    {        
        if (isset($feed->address)) {
            return self::OBJECT_TYPE_ADDRESS;   
        } else if (isset($feed->stop_area)) {
            return self::OBJECT_TYPE_STOP_AREA;
        } else if (isset($feed->stop_point)) {
            return self::OBJECT_TYPE_STOP_POINT;            
        } else if (isset($feed->poi)) {
            return self::OBJECT_TYPE_POI;            
        } else if (isset($feed->administrative_region)) {
            return self::OBJECT_TYPE_ADMIN;            
        } else {
            return self::OBJECT_TYPE_UNKNOWN;            
        }
    }
}