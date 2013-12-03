<?php

namespace Nv2\Controller\Schedule;

use Nv2\Lib\Nv2\Controller\Controller;
use Nv2\Lib\Nv2\Http\Request;
use Nv2\Model\Entity\Transport\JourneyPatternPoint;

/**
 * @author Thomas Noury <thomas.noury@canaltp.fr>
 * @copyright 2012-2013 Canal TP 
 */
class ScheduleSelectStopController extends Controller
{
    /**
     * Initialisation du contrôleur
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }
    
    /**
     * Execution du contrôleur
     */
    public function run()
    {
        $route_uri = $this->request->getParam(0);
        $type = $this->request->getParam(1);
        
        $stop_list = JourneyPatternPoint::getList($route_uri);
        
        $this->template->setVariable('stop_list', $stop_list);
        $this->template->setVariable('type', $type);
        $this->template->fetch('module/schedule/ajax/select_stop.php');
        $this->template->setPagelayoutActive(false);
    }
}