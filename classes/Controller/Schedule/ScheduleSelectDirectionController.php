<?php

namespace Nv2\Controller\Schedule;

use Nv2\Lib\Nv2\Controller\Controller;
use Nv2\Lib\Nv2\Http\Request;
use Nv2\Model\Entity\Transport\Route;

/**
 * @author Thomas Noury <thomas.noury@canaltp.fr>
 * @copyright 2012-2013 Canal TP 
 */
class ScheduleSelectDirectionController extends Controller
{
    /**
     * Initilisation du contrôleur
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
        $lineId = urldecode($this->request->getParam(0));
        $type = $this->request->getParam(1);
        
        $routes = Route::getFromLine($lineId);
        
        $this->template->setVariable('route_list', $routes);
        $this->template->setVariable('line_id', $lineId);
        $this->template->setVariable('type', $type);
        $this->template->fetch('module/schedule/ajax/select_direction.php');
        $this->template->setPagelayoutActive(false);
    }
}