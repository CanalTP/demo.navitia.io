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
    public $Code;
    public $Color;
    public $Name;
    public $Uri;
    public $PhysicalModeList;
    public $CommercialMode;
    public $Network;
    public $Routes;

    /**
     * Initialisation des données de l'objet
     */
    private function __construct()
    {
        $this->Code = null;
        $this->Color = null;
        $this->Name = null;
        $this->Uri = null;
        $this->PhysicalModeList = null;
        $this->CommercialMode = null;
        $this->Network = null;
    }

    /**
     * Retourne une instance de Line
     * @return \Nv2\Model\Entity\Transport\Line
     */
    public static function create()
    {
        return new self();
    }

    /**
     * Retourne une liste d'objets Line correspondant aux paramètres
     * @param string $networkUri
     * @param string $stopPointUri
     * @return multitype:\Nv2\Model\Entity\Transport\Line |NULL
     */
    public static function getList($networkUri='', $stopPointUri='')
    {
        $query = NavitiaRequest::create()->api('lines');
        if ($networkUri) {
            $query->filter('network', 'uri', '=', $networkUri);
        }
        if ($stopPointUri) {
            $query->filter('stop_point', 'uri', '=', $stopPointUri);
        }
        $feed = $query->execute();

        if (!$feed['hasError']) {
            $feed = json_decode($feed['content']);
            $list = array();

            if ($feed != null) {
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
            $this->Code = $feed->code;
        }
        //$this->Color = $feed->color;
        $this->Name = $feed->name;
        $this->Uri = $feed->uri;

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