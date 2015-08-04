<?php

namespace Nv2\Controller\Schedule;

use Nv2\Lib\Nv2\Controller\Controller;
use Nv2\Lib\Nv2\Http\Request;
use Nv2\Model\Entity\Transport\Line;

/**
 * @author Thomas Noury <thomas.noury@canaltp.fr>
 * @copyright 2012-2013 Canal TP
 */
class ScheduleSelectLineController extends Controller
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
        $network_uri = urldecode($this->request->getParam(0));
        $type = $this->request->getParam(1);

        $line_list = Line::getListFromNetwork($network_uri);
        
        $this->template->setVariable('line_list', $line_list);
        $this->template->setVariable('type', $type);
        $this->template->fetch('module/schedule/ajax/select_line.php');
        $this->template->setPagelayoutActive(false);
    }
}
