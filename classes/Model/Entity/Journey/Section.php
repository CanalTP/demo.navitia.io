<?php

namespace Nv2\Model\Entity\Journey;

use Nv2\Model\Entity\Base\Entity;
use Nv2\Model\Entity\Transport\StopPoint;
use Nv2\Model\Entity\Geo\Address;

class Section extends Entity
{
    const TYPE_UNDEFINED = 'UNDEFINED';
    const TYPE_TRANSFER = 'TRANSFER';
    const TYPE_WAITING = 'WAITING';
    const TYPE_STREET_NETWORK = 'STREET_NETWORK';
    const TYPE_PUBLIC_TRANSPORT = 'PUBLIC_TRANSPORT';

    public $Type;
    

    private function __construct()
    {

    }

    public static function create()
    {
        return new self();
    }

    public function fill($sectionFeed)
    {
        $this->Type = $sectionFeed->type;

        return $this;
    }
    
    private function getPointType($feed)
    {
        
    }
    
    private function fillPoint($feed)
    {
                
    }
}