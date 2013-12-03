<?php 

namespace Nv2\Model\Entity\Transport;

use Nv2\Model\Entity\Geo\Coord;
use Nv2\Model\Entity\Base\Entity;

class Admin extends Entity
{
    public $Level;
    public $ZipCode;
    public $Uri;
    public $Coord;
    public $Name;
    
    private function __construct()
    {
        
    }
    
    public static function create()
    {
        return new self();
    }
    
    public function fill($feed)
    {
        $this->Level = $feed->level;
        $this->ZipCode = $feed->zip_code;
        $this->Uri = $feed->uri;
        $this->Coord = Coord::create()
            ->fill($feed->coord);
        $this->Name = $feed->name;
        
        return $this;        
    }
}