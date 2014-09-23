<?php

namespace Nv2\Lib\Nv2\Service;

use Nv2\Lib\Nv2\Core\Module;
use Nv2\Lib\Nv2\Config\Config;

class NavitiaRequest extends ServiceRequest
{
    const OPERATOR_DWITHIN = 'DWITHIN';

    protected $regionName;
    protected $apiName;
    protected $resource;
    protected $filterList;

    protected function __construct()
    {
        parent::__construct();
        $this->regionName = Module::$sRequest->getRegionName() . '/';
        $this->serviceUrl = Config::get('webservice', 'Url', 'Navitia');
        $this->authorizationKey = Config::get('webservice', 'Token');
        $this->filterList = null;
    }

    public static function create()
    {
        return new self();
    }

    public function disableRegion()
    {
        $this->regionName = '';
        return $this;
    }

    public function api($name)
    {
        $this->apiName = $name;
        return $this;
    }

    public function filter($object_name, $object_attribute, $operator, $value, $dwithin_distance=50)
    {
        if ($operator == self::OPERATOR_DWITHIN) {
            $this->filterList[] = urlencode($object_name . '.' . $object_attribute . ' DWITHIN(' . $value . ',' . $dwithin_distance . ')');
        } else {
            $this->filterList[] = $object_name . '.' . $object_attribute . $operator . $value;
        }
        return $this;
    }

    public function execute()
    {
        $url = $this->getUrl();

        return $this->retrieveFeedContent($url);
    }

    public function getUrl()
    {
        $this->addParamsFromFilters();

        $url = $this->serviceUrl . $this->apiName;
        $c = 0;

        if (count($this->params) > 0) {
            foreach ($this->params as $param) {
                if ($c == 0) {
                    $sep = '?';
                } else {
                    $sep = '&';
                }
                $url .= $sep . $param['name'] . '=' . $param['value'];
                $c++;
            }
        }

        return $url;
    }

    private function addParamsFromFilters()
    {
        if ($this->filterList != null) {
            $filterString = implode(urlencode(' AND '), $this->filterList);
            parent::param('filter', $filterString);
        }
    }
}