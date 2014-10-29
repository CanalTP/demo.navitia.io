<?php

namespace Nv2\Model\Entity\Transport;

use Nv2\Model\Entity\Base\Entity;
use Nv2\Lib\Nv2\Service\NavitiaRequest;

class Network extends Entity
{
    public $id;
    public $name;

    private function __construct()
    {
        $this->id = null;
        $this->name = null;
    }

    public static function create()
    {
        return new self();
    }
    
    public static function getList()
    {
        $query = NavitiaRequest::create()
            ->api('coverage')
            ->resource('networks');
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
        $this->id = $feed->id;
        $this->name = $feed->name;

        return $this;
    }
}