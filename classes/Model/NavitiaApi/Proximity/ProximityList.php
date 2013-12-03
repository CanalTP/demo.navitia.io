<?php

namespace Nv2\Model\NavitiaApi\Proximity;

use Nv2\Model\NavitiaApi\Base\NavitiaApi;

class ProximityList extends NavitiaApi
{
    public $Lon;
    public $Lat;
    public $Distance;
    public $Filter;
    public $Limit;

    public $ItemList;

    private function __construct()
    {

    }

    public static function create()
    {
        return new self();
    }

    public function coords($lonLatCoords)
    {
        $data = explode(';', $lonLatCoords);
        $this->Lon = $data[0];
        $this->Lat = $data[1];

        return $this;
    }

    public function distance($distance)
    {
        $this->Distance = $distance;
        return $this;
    }

    public function limit($value)
    {
        $this->Limit = $value;
        return $this;
    }

    public function fill($feed)
    {
        $c = 0;
        if (isset($feed->proximitylist->items) && is_array($feed->proximitylist->items)) {
            foreach ($feed->proximitylist->items as $item) {
                if ($c >= $this->Limit) {
                    break;
                }
                $itemObject = ProximityListItem::create()
                    ->fill($item);
                $this->addItem($itemObject);
                $c++;
            }
        }
    }

    public function addItem($item)
    {
        $this->ItemList[] = $item;
    }

    public function getResultList()
    {
        $feed = NavitiaRequest::create()
            ->api('proximity_list')
            ->param('lon', $this->Lon)
            ->param('lat', $this->Lat)
            ->param('dist', $this->Distance)
            ->execute();

        $this->fill(json_decode($feed['content']));

        return array(
            'data' => $this,
        );
    }
}