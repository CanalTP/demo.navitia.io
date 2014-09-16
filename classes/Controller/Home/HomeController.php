<?php

namespace Nv2\Controller\Home;

use Nv2\Lib\Nv2\Controller\Controller;
use Nv2\Model\Entity\Geo\Region;
use Nv2\Lib\Nv2\Data\FileData;

class HomeController extends Controller
{
    public function run()
    {
        $regionList = $this->getRegionList();
        $sortedRegionList = $this->order($regionList);
        $this->template->setVariable('region_list', $sortedRegionList);
        $this->template->fetch('module/home/index.php');
    }

    private function getRegionList()
    {
        return Region::getList();
    }
    
    private function order($regionList)
    {
        $data = FileData::get('region_coords', 'coords');
        $finalList = array();
        if (is_array($regionList)) {
            foreach ($regionList as $region) {
                if (isset($data[$region->Id])) {
                    $hide = false;
                    if (isset($data[$region->Id]['hide']) && $data[$region->Id]['hide']) {
                        $hide = true;
                    }
                    if (!$hide) {
                        $finalList[$data[$region->Id]['order']] = $region;
                    }
                }
            }
        }
        
        ksort($finalList);
        return $finalList;
    }
}