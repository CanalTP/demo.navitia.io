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
class ScheduleStopExecuteController extends Controller
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
     * Traite le formulaire de recherche horaire
     */
    private function dispatchSearchResult($vars)
    {        
        if (isset($vars['line_schedule_submit'])) {
            $this->dispatchLineSearchResult($vars);
        } else if (isset($vars['stop_schedule_submit'])) {
            $this->dispatchStopSearchResult($vars);
        }
    }
    
    /**
     * Traite le formulaire de recherche horaire à l'arrêt
     * Effectue une redirection vers la grille horaire à l'arrêt
     */
    private function dispatchStopSearchResult($vars)
    {        
        if (isset($vars['line_id']) && $vars['line_id']) {
            if (isset($vars['route_id']) && $vars['route_id']) {
                if (isset($vars['stop_point_id']) && $vars['stop_point_id']) {
                    // Affichage de la grille horaire
                    $datetime = new \DateTime();
                    $datetime->setTime(4, 0, 0);
                    $this->redirect('schedule/departure_board/'
                        . urlencode($vars['line_id']) . '/'
                        . urlencode($vars['route_id']) . '/'
                        . urlencode($vars['stop_point_id']) . '/'
                        . urlencode($datetime->format(Config::get('format', 'Date', 'Iso8861Full'))));
                } else {
                    // Choix de l'arrêt
                    $this->redirect('schedule/select_stop/'
                        . urlencode($vars['line_id']) . '/'
                        . urlencode($vars['route_id']));
                }
            } else {
                // Choix du sens
                $this->redirect('schedule/select_direction/'
                    . urlencode($vars['line_id']));
            }
        } else {
            // Choix de la ligne
            $this->redirect('schedule/select_line');
        }
    }
    
    /**
     * Traite le formulaire de recherche horaire de ligne
     * Effectue une redirection vers la grille horaire de ligne
     */
    private function dispatchLineSearchResult($vars)
    {
        if (isset($vars['line_id']) && $vars['line_id']) {
            if (isset($vars['route_id']) && $vars['route_id']) {
                // Affichage de la grille horaire
                $datetime = new \DateTime();
                $datetime->setTime(4, 0, 0);
                $this->redirect('schedule/line/'
                    . urlencode($vars['line_id']) . '/'
                    . urlencode($vars['route_id']) . '/'
                    . urlencode($datetime->format(Config::get('format', 'Date', 'Iso8861Full'))));
            } else {
                // Choix du sens
                $this->redirect('schedule/select_direction/'
                    . urlencode($vars['line_id']));
            }
        } else {
            // Choix de la ligne
            $this->redirect('schedule/select_line');
        }        
    }
}