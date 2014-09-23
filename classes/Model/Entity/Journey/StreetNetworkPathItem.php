<?php

namespace Nv2\Model\Entity\Journey;

use Nv2\Model\Entity\Base\Entity;

class StreetNetworkPathItem extends Entity
{
    const DIRECTION_CONTINUE = 'continue';
    const DIRECTION_TURN_LEFT = 'turn_left';
    const DIRECTION_TURN_RIGHT = 'turn_right';
    
    public $name;
    public $duration;
    public $length;
    public $direction;

    private function __construct()
    {
        $this->name = null;
        $this->duration = null;
        $this->length = null;
        $this->direction = null;
    }

    public static function create()
    {
        return new self();
    }

    public function fill($feed)
    {
        $this->name = $feed->name;
        $this->length = (int)$feed->length;
        $this->duration = $feed->duration;
        $this->direction = $this->getDirectionText($feed->direction);

        return $this;
    }
    
    public function getDirectionText($directionValue)
    {
        if ($directionValue < -10) {
            $text = self::DIRECTION_TURN_LEFT;
        } else if ($directionValue > 10) {
            $text = self::DIRECTION_TURN_RIGHT;
        } else {
            $text = self::DIRECTION_CONTINUE;
        }
        return $text;
    }
}