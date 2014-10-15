<?php 

namespace Nv2\Model\Entity\Transport;

use Nv2\Model\Entity\Geo\Coord;
use Nv2\Model\Entity\Base\Entity;

class Admin extends Entity
{
    public $level;
    public $zipCode;
    public $id;
    public $coord;
    public $name;
    
    private function __construct()
    {
        
    }
    
    public static function create()
    {
        return new self();
    }
    
    public function fill($feed)
    {
        $this->level = $feed->level;
        $this->zipCode = $feed->zip_code;
        $this->id = $feed->id;
        $this->coord = Coord::create()
            ->fill($feed->coord);
        $this->name = $feed->name;
        
        return $this;        
    }
}