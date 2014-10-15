<?php

namespace Nv2\Model\Entity\Data;

use Nv2\Model\Entity\Base\Entity;

class Link extends Entity
{
    public $type;
    public $id;
    public $internal;
    public $rel;
    public $templated;
    
    private function __construct()
    {
        $this->type = null;
        $this->id = null;
        $this->internal = false;
        $this->rel = null;
        $this->templated = false;
    }
    
    public static function create()
    {
        return new self();
    }
    
    public function fill($feed)
    {
        $this->type = $feed->type;
        $this->id = $feed->id;
        
        if (isset($feed->rel)) {
            $this->rel = $feed->rel;
        }
        if (isset($feed->internal)) {
            $this->internal = $feed->internal;
        }
        if (isset($feed->templated)) {
            $this->templated = $feed->templated;
        }
        return $this;
    }
}
