<?php

namespace Nv2\Model\Entity\Transport;

use Nv2\Model\Entity\Base\Entity;
use Nv2\Lib\Nv2\Service\NavitiaRequest;
use Nv2\Lib\Nv2\Http\Request;

class PhysicalMode extends Entity
{
    public $Uri;
    public $Name;
    public $Checked;

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
            ->api('physical_modes')
            ->execute();

        if (!$feed['hasError']) {
            $feed = json_decode($feed['content']);
            $list = array();

            if ($feed != null) {
                foreach ($feed->physical_modes as $mode) {
                    $list[] = self::create()
                        ->fill($mode);
                }
                return $list;
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    public static function getUriList()
    {
        $list = array();
        $modeList = self::getList();
        foreach ($modeList as $mode) {
            $list[] = $mode->Uri;
        }
        return $list;
    }

    public function fill($feed, $checked=false)
    {
        $this->Uri = $feed->uri;
        $this->Name = $feed->name;
        $this->Checked = $checked;

        return $this;
    }
}