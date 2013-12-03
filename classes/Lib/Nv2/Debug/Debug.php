<?php

namespace Nv2\Lib\Nv2\Debug;

class Debug
{
    const STATUS_NONE = 0;
    const STATUS_WEB = 1;
    const STATUS_RAW = 2;
    
    private static $serviceRequests;
    private static $serviceRequestsTotalTime = 0;
    
    public static function e($data)
    {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }
    
    public static function addServiceRequest($category, $request, $time)
    {
        $time = $time * 1000;
        self::$serviceRequests[] = array(
            'category' => $category,
            'request' => $request,
            'time' => $time,
        );
        self::$serviceRequestsTotalTime += $time;
    }
    
    public static function getServiceRequests()
    {
        return self::$serviceRequests;        
    }
    
    public static function getServiceRequestsTotalTime()
    {
        return self::$serviceRequestsTotalTime;
    }
}