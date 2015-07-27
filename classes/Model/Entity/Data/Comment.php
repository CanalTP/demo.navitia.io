<?php

namespace Nv2\Model\Entity\Data;

use Nv2\Model\Entity\Base\Entity;

class Comment extends Entity
{
    public $type;
    public $value;

    private function __construct()
    {
        $this->type = null;
        $this->value = null;
    }

    public static function create()
    {
        return new self();
    }

    public function fill($feed)
    {
        $this->type = $feed->type;
        $this->value = $feed->value;
        return $this;
    }
}
