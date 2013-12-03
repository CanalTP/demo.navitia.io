<?php

namespace Nv2\Model\Entity\Transport;

use Nv2\Model\Entity\Base\Entity;
use Nv2\Lib\Nv2\Service\NavitiaRequest;

class Network extends Entity
{
    public $Uri;
    public $Name;

    private function __construct()
    {
        $this->Uri = null;
        $this->Name = null;
    }

    public static function create()
    {
        return new self();
    }
    
    public static function getList()
    {
        $query = NavitiaRequest::create()->api('networks');
        $feed = $query->execute();
        
        if (!$feed['hasError']) {
            $feed = json_decode($feed['content']);
            $list = array();
        
            if ($feed != null) {
                foreach ($feed->networks as $network) {
                    $list[] = self::create()
                    ->fill($network);
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
        $this->Uri = $feed->uri;
        $this->Name = $feed->name;

        return $this;
    }
}