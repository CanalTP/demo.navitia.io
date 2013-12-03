<?php

namespace Nv2\Model\Entity\Transport;

use Nv2\Model\Entity\Base\Entity;
use Nv2\Lib\Nv2\Service\NavitiaRequest;

class Route extends Entity
{
    public $Name;
    public $Uri;
    public $Line;

    private function __construct()
    {
        $this->Name = null;
        $this->Uri = null;
        $this->Line = null;
    }

    public static function create()
    {
        return new self();
    }

    public static function getList($lineUri, $stopAreaUri=null)
    {
        $feed = NavitiaRequest::create()
            ->api('routes')
            ->param('depth', 2); // depth=2 permet d'avoir les modes et réseaux des lignes

        if ($lineUri) {
            $feed = $feed->filter('line', 'uri', '=', $lineUri);
        }
        if ($stopAreaUri) {
            // A changer par stop_area
            $feed = $feed->filter('stop_point', 'uri', '=', $stopAreaUri);
        }
        $feed = $feed->execute();

        if (!$feed['hasError']) {
            $feed = json_decode($feed['content']);
            $list = array();

            if ($feed != null) {
                if (isset($feed->routes)) {
                    foreach ($feed->routes as $route) {
                        $list[] = self::create()
                            ->fill($route);
                    }
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
        $this->Name = $feed->name;
        $this->Uri = $feed->uri;

        if (isset($feed->line)) {
            $lineObject = Line::create()
                ->fill($feed->line);
            $this->Line = $lineObject;
        }

        return $this;
    }
}