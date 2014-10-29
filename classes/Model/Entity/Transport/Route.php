<?php

namespace Nv2\Model\Entity\Transport;

use Nv2\Model\Entity\Base\Entity;
use Nv2\Lib\Nv2\Service\NavitiaRequest;

class Route extends Entity
{
    public $name;
    public $id;
    public $line;

    private function __construct()
    {
        $this->name = null;
        $this->id = null;
        $this->line = null;
    }

    public static function create()
    {
        return new self();
    }
    
    public static function getAll()
    {
        // TODO
    }
    
    public static function getFromLine($lineId)
    {
        return self::getList(NavitiaRequest::create()
            ->api('coverage')
            ->resource('routes')
            ->with('lines', $lineId)
        );
    }
    
    public static function getFromStopArea($stopAreaId)
    {
        return self::getList(NavitiaRequest::create()
            ->api('coverage')
            ->resource('routes')
            ->with('stop_areas', $stopAreaId)
        );
    }
    
    private static function getList(NavitiaRequest $request)
    {
        $feed = $request->execute();
        $result = array();
        if (!$feed['hasError']) {
            $feed = json_decode($feed['content']);
            if ($feed != null && isset($feed->routes)) {
                foreach ($feed->routes as $route) {
                    $result[] = Route::create()
                        ->fill($route);
                }
            }
        }
        return $result;
    }

    public function fill($feed)
    {
        $this->name = $feed->name;
        $this->id = $feed->id;

        if (isset($feed->line)) {
            $lineObject = Line::create()
                ->fill($feed->line);
            $this->line = $lineObject;
        }

        return $this;
    }
}