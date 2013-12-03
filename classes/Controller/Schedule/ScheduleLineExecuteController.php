<?php

namespace Nv2\Controller\Schedule;

use Nv2\Lib\Nv2\Controller\Controller;
use Nv2\Lib\Nv2\Http\Request;
use Nv2\Lib\Nv2\Config\Config;

/**
 * 
 * @author Thomas Noury <thomas.noury@canaltp.fr>
 * @copyright 2012-2013 Canal TP 
 */
class ScheduleLineExecuteController extends Controller
{
    /**
     * 
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }
    
    /**
     * 
     */
    public function run()
    {
        $this->dispatchSearchResult($this->request->getParams());
    }
    
    /**
     * 
     * @param array $vars
     */
    private function dispatchSearchResult($vars)
    {
        if (isset($vars['line_uri']) && $vars['line_uri']) {
            if (isset($vars['route_uri']) && $vars['route_uri']) {
                // Affichage de la grille horaire
                $datetime = new \DateTime();
                $datetime->setTime(4, 0, 0);
                $this->redirect('schedule/line/'
                    . urlencode($vars['line_uri']) . '/'
                    . urlencode($vars['route_uri']) . '/'
                    . urlencode($datetime->format(Config::get('format', 'Date', 'Iso8861Full'))));
            } else {
                // Choix du sens
                $this->redirect('schedule/select_direction/'
                    . urlencode($vars['line_uri']));
            }
        } else {
            // Choix de la ligne
            $this->redirect('schedule/select_line');
        }
    }
}