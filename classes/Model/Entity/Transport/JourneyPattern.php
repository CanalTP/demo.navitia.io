<?php

namespace Nv2\Model\Entity\Transport;

use Nv2\Model\Entity\Base\Entity;

class JourneyPattern extends Entity
{
    public $Name;
    public $Uri;
    
    private function __construct()
    {
        $this->Name = null;
        $this->Uri = null;        
    }
    
    public static function create()
    {
        return new self();        
    }
    
    public function fill($feed)
    {
        if (isset($feed->name)) {
            $this->Name = $feed->name;
        }
        if (isset($feed->uri)) {
            $this->Uri = $feed->uri;
        }
        
        return $this;        
    }
}