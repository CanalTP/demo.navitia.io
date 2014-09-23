<?php

namespace Nv2\Controller\Journey;

use Nv2\Lib\Nv2\Controller\Controller;
use Nv2\Lib\Nv2\Config\Config;

class JourneySearchController extends Controller
{
    public function __construct($request)
    {
        parent::__construct($request);
    }

    public function run()
    {
        $currentDateTime = new \DateTime();

        $this->template->setVariable('current_date_human', $currentDateTime->format('d/m/Y'));
        $this->template->setVariable('current_time_human', $currentDateTime->format('H:i'));

        $this->template->fetch('module/journey/search.php');
    }
    
    private function getMediaModeList()
    {
        $list = Config::get('mode', 'MediaModeList');
        $finalList = array();
        $requestedModes = $this->request->getParam('modes');
        
        foreach ($list as $modeName => $modeIdList) {
            if ($requestedModes == null || in_array($modeName, $requestedModes)) {
                $checked = true;
            } else {
                $checked = false;                
            }
            $finalList[] = array(
                'name' => $modeName,
                'checked' => $checked,
            );     
        }

        return $finalList;
    }
}