<?php

namespace Nv2\Model\Entity\Transport;

use Nv2\Model\Entity\Base\Entity;
use Nv2\Model\Entity\Transport\FareTotal;

class Fare extends Entity
{
    public $found;
    public $total;
    public $links;
    
    private function __construct()
    {
        $this->found = false;
        $this->total = null;
        $this->links = null;
    }
    
    public static function create()
    {
        return new self();
    }
    
    public function fill($feed)
    {
        $this->found = $feed->found;
        $this->total = FareTotal::create()
            ->fill($feed->total);
    }
}