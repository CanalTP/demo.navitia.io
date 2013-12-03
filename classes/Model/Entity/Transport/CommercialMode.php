<?php

namespace Nv2\Model\Entity\Transport;

use Nv2\Model\Entity\Base\Entity;

class CommercialMode extends Entity
{
    public $Uri;
    public $Name;

    private function __construct()
    {
        $this->Uri = null;
        $this->Name = null;
    }

    public static function create()
    {
        return new self();
    }

    public static function getList()
    {
        $feed = NavitiaRequest::create()
            ->api('commercial_modes')
            ->execute();

        $feed = json_decode($feed['content']);

        $list = array();
        foreach ($feed->commercial_modes as $mode) {
            $list[] = self::create()
                ->fill($mode);
        }

        return $list;
    }

    public function fill($feed)
    {
        $this->Uri = $feed->uri;
        $this->Name = $feed->name;

        return $this;
    }
}