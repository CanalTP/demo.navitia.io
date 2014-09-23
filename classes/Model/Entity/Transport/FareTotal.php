<?php

namespace Nv2\Model\Entity\Transport;

use Nv2\Model\Entity\Base\Entity;

class FareTotal extends Entity
{
    public $currency;
    public $value;
    
    private function __construct()
    {
        $this->currency = null;
        $this->value = null;
    }
    
    public static function create()
    {
        return new self();
    }
    
    public function fill($feed)
    {
        $this->currency = $feed->currency;
        $this->value = $feed->value;
    }
}