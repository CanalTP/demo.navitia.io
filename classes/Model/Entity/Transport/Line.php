<?php

namespace Nv2\Model\Entity\Transport;

use Nv2\Model\Entity\Base\Entity;
use Nv2\Lib\Nv2\Service\NavitiaRequest;
use Nv2\Model\Entity\Geo\Coord;

/**
 * Classe représentant les lignes de transport en commun
 * @author Thomas Noury <thomas.noury@canaltp.fr
 * @copyright 2012-2013 Canal TP
 */
class Line extends Entity
{
    public $code;
    public $color;
    public $name;
    public $id;
    public $physicalModeList;
    public $commercialMode;
    public $network;
    public $routes;

    /**
     * Initialisation des données de l'objet
     */
    private function __construct()
    {
        $this->code = null;
        $this->color = null;
        $this->name = null;
        $this->id = null;
        $this->physicalModeList = null;
        $this->commercialMode = null;
        $this->network = null;
    }

    /**
     * Retourne une instance de Line
     * @return \Nv2\Model\Entity\Transport\Line
     */
    public static function create()
    {
        return new self();
    }
    
    public static function getAll()
    {
        return self::getList(NavitiaRequest::create()
            ->api('coverage')
            ->resource('lines')
        );
    }
    
    public static function getListFromNetwork($networkId)
    {
        return self::getList(NavitiaRequest::create()
            ->api('coverage')
            ->resource('lines')
            ->with('network', $networkId)
        );
    }

    private static function getList(NavitiaRequest $request)
    {
        $feed = $request->execute();
        $result = array();
        if (!$feed['hasError']) {
            $feed = json_decode($feed['content']);
            if ($feed != null && isset($feed->lines)) {
                foreach ($feed->lines as $line) {
                    $result[] = Line::create()
                        ->fill($line);
                }
            }
        }
        return $result;
    }
    
    public static function getProximityList(Coord $coords, $distance)
    {
        $feed = NavitiaRequest::create()
            ->api('lines')
            ->filter('stop_point', 'coord', NavitiaRequest::OPERATOR_DWITHIN, $coords->Lon . ',' . $coords->Lat, $distance)
            ->execute();
            
        if (!$feed['hasError']) {
            $feed = json_decode($feed['content']);
            $list = array();

            if ($feed != null && isset($feed->lines)) {
                foreach ($feed->lines as $line) {
                    $list[] = self::create()
                        ->fill($line);
                }

                return $list;
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    /**
     * Remplit l'objet Line avec les données du flux JSON
     * @param string $feed
     * @return \Nv2\Model\Entity\Transport\Line
     */
    public function fill($feed)
    {
        if (isset($feed->code)) {
            $this->code = $feed->code;
        }
        //$this->Color = $feed->color;
        $this->name = $feed->name;
        $this->id = $feed->id;

        if (isset($feed->routes)) {
            foreach ($feed->routes as $route) {
                $routeObject = Route::create()
                    ->fill($route);
                $this->addRoute($routeObject);
            }
        }
        
        if (isset($feed->physical_mode)) {
            foreach ($feed->physical_mode as $mode) {
                $modeObject = PhysicalMode::create()
                    ->fill($mode);
                $this->addPhysicalMode($modeObject);
            }
        }

        if (isset($feed->commercial_mode)) {
            $this->CommercialMode = CommercialMode::create()
                ->fill($feed->commercial_mode);
        }

        if (isset($feed->network)) {
            $this->Network = Network::create()
                ->fill($feed->network);
        }

        return $this;
    }

    /**
     * Ajoute un mode physique sur l'objet Line
     * @param PhysicalMode $mode
     */
    private function addPhysicalMode(PhysicalMode $mode)
    {
        $this->PhysicalModeList[] = $mode;
    }
    
    private function addRoute(Route $route)
    {
        $this->Routes[] = $route;
    }
}