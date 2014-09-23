<?php

namespace Nv2\Model\Entity\Geo;

use Nv2\Model\Entity\Base\Entity;

//! Classe représentant un point sur le globe
class Coord extends Entity
{
    public $lat;
    public $lon;

    private function __construct()
    {
        $this->lat = 0;
        $this->lon = 0;
    }

    //! Retourne une instance de Coord
    /**
     * @return Coord
     */
    public static function create()
    {
        return new self();
    }

    //! Retourne une instance de Coord depuis une chaîne
    /**
     * @param string $coordString la chaîne constituée de la longitude et latitude
     * @return Coord
     */
    public static function createFromString($coordString)
    {
        $coordString = urldecode($coordString);
        if (strstr($coordString, ':')) {
            $data = explode(':', $coordString);
        } else if (strstr($coordString, ',')) {
            $data = explode(',', $coordString);
        } else {
            $data = explode(';', $coordString);
        }
        $object = new self();
        $object->lon = $data[0] + 0;
        $object->lat = $data[1] + 0;

        return $object;
    }
    
    public static function createFromFloats($lon, $lat)
    {
        $object = new self();
        $object->lon = $lon;
        $object->lat = $lat;
        
        return $object;
    }
    
    /**
     * @param string $separator
     * @return string
     */
    public function getString($separator=':')
    {
        return $this->lon . $separator . $this->lat;        
    }
    
    /**
     * Retourne la distance en mètres entre deux points
     * @param Coord $other
     * @return float distance en mètres
     */
    public function getDistanceFrom(Coord $other)
    {
        $lat1 = deg2rad($this->lat);
        $lat2 = deg2rad($other->lat);
        $lon1 = deg2rad($this->lon);
        $lon2 = deg2rad($other->lon);
        
        $distance = 2 * asin(
            sqrt(
                pow(sin(($lat1 - $lat2) / 2), 2)
              + cos($lat1)
              * cos($lat2)
              * pow(sin(($lon1 - $lon2) / 2), 2)
            )
        );
        
        return $distance * 6366000;
    }

    //! Retourne la chaîne uri navitia
    /**
     * @return string
     */
    public function getUri()
    {
        return 'coord:' . $this->lon . ':' . $this->lat;
    }

    //! Alimente les propriétés de l'objet à partir d'un flux JSON
    /**
     * @param string $feed le fragment de flux JSON où se trouve l'objet coord
     */
    public function fill($feed)
    {
        $this->lon = $feed->lon;
        $this->lat = $feed->lat;

        return $this;
    }
}