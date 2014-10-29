<?php 

namespace Nv2\Model\Entity\Transport;

use Nv2\Model\Entity\Base\Entity;
use Nv2\Model\Entity\Transport\JourneyPattern;
use Nv2\Model\Entity\Transport\StopPoint;
use Nv2\Lib\Nv2\Service\NavitiaRequest;

class JourneyPatternPoint extends Entity
{
    public $stopPoint;
    public $id;
    
    private function __construct()
    {
        $this->stopPoint = null;
        $this->id = null;
    }
    
    public static function create()
    {
        return new self();        
    }
    
    public static function getFromRoute($routeId)
    {
        return self::getList(NavitiaRequest::create()
            ->api('coverage')
            ->resource('journey_pattern_points')
            ->with('routes', $routeId)
        );
    }
    
    private static function getList(NavitiaRequest $request)
    {
        $feed = $request->execute();
        $result = array();
        if (!$feed['hasError']) {
            $feed = json_decode($feed['content']);
            if ($feed != null && isset($feed->journey_pattern_points)) {
                foreach ($feed->journey_pattern_points as $journeyPatternPoint) {
                    $result[] = JourneyPatternPoint::create()
                        ->fill($journeyPatternPoint);
                }
            }
        }
        return $result;
    }
    
    public function fill($feed)
    {
        if (isset($feed->stop_point)) {
            $this->stopPoint = StopPoint::create()
                ->fill($feed->stop_point);
        }
        if (isset($feed->id)) {
            $this->id = $feed->id;
        }
        
        return $this;        
    }
}