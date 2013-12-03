<?php 

namespace Nv2\Model\Entity\Transport;

use Nv2\Model\Entity\Base\Entity;
use Nv2\Model\Entity\Transport\JourneyPattern;
use Nv2\Model\Entity\Transport\StopPoint;
use Nv2\Lib\Nv2\Service\NavitiaRequest;

class JourneyPatternPoint extends Entity
{
    public $JourneyPattern;
    public $StopPoint;
    public $Uri;
    public $Order;
    
    private function __construct()
    {
        $this->JourneyPattern = null;
        $this->StopPoint = null;
        $this->Uri = null;
        $this->Order = null;        
    }
    
    public static function create()
    {
        return new self();        
    }
    
    public static function getList($routeUri)
    {
        $query = NavitiaRequest::create()->api('journey_pattern_points');
        $query->filter('route', 'uri', '=', $routeUri);
        $query->param('depth', 2);
        $feed = $query->execute();
        
        if (!$feed['hasError']) {
            $feed = json_decode($feed['content']);
            $list = array();
        
            if ($feed != null && isset($feed->journey_pattern_points)) {
                foreach ($feed->journey_pattern_points as $journeyPatternPoint) {
                    $list[] = self::create()
                        ->fill($journeyPatternPoint);
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
        if (isset($feed->journey_pattern)) {
            $this->JourneyPattern = JourneyPattern::create()
                ->fill($feed->journey_pattern);
        }
        if (isset($feed->stop_point)) {
            $this->StopPoint = StopPoint::create()
                ->fill($feed->stop_point);
        }
        if (isset($feed->uri)) {
            $this->Uri = $feed->uri;
        }
        if (isset($feed->order)) {
            $this->Order = $feed->order;
        }
        
        return $this;        
    }
}