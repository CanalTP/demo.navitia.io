<?php

namespace Nv2\Model\Entity\Journey;

use Nv2\Model\Entity\Base\Entity;
use Nv2\Model\Entity\Data\Link;

class Section extends Entity
{
    const TYPE_UNDEFINED = 'undefined';
    const TYPE_TRANSFER = 'transfer';
    const TYPE_WAITING = 'waiting';
    const TYPE_STREET_NETWORK = 'street_network';
    const TYPE_PUBLIC_TRANSPORT = 'public_transport';
    const TYPE_CROW_FLY = 'crow_fly';
    const TYPE_STAY_IN = 'stay_in';

    public $type;
    public $links;
    public $duration;

    private function __construct()
    {
        $this->type = null;
    }

    public static function create()
    {
        return new self();
    }

    public function fill($sectionFeed)
    {
        $this->type = $sectionFeed->type;
        $this->duration = $sectionFeed->duration;
        $this->fillLinks($sectionFeed->links);
        
        return $this;
    }
    
    public function fillLinks(array $linksFeed)
    {
        foreach ($linksFeed as $link) {
            $this->links[$link->type] = Link::create()
                ->fill($link);
        }
    }
}