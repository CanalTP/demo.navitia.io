<?php

namespace Nv2\Model\Entity\Schedule\DepartureBoards;

use Nv2\Model\Entity\Base\Entity;

class BoardItem extends Entity
{
    public $Hour;
    public $Minutes;

    private function __construct()
    {
        $this->Hour = null;
        $this->Minutes = null;
    }

    public static function create()
    {
        return new self();
    }

    public function fill($itemFeed)
    {
        $this->Hour = $itemFeed->hour;
        foreach ($itemFeed->minutes as $minute) {
            $this->Minutes[] = $minute;
        }

        return $this;
    }
}