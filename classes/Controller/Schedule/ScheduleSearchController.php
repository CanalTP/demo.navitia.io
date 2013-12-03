<?php

namespace Nv2\Controller\Schedule;

use Nv2\Lib\Nv2\Controller\Controller;
use Nv2\Lib\Nv2\Http\Request;
use Nv2\Model\Entity\Transport\Line;
use Nv2\Model\Entity\Transport\Network;
use Nv2\Lib\Nv2\Config\Config;

/**
 * Contrôleur de recherche horaire
 * @author Thomas Noury <thomas.noury@canaltp.fr>
 * @copyright 2012-2013 Canal TP 
 */
class ScheduleSearchController extends Controller
{
    /**
     * Initialisation du controleur
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }
    
    /**
     * Execution du controleur
     */
    public function run()
    {
        $currentDateTime = new \DateTime();
        $networkList = Network::getList();
        
        if (count($networkList) == 1) {
            // Si un seul réseau : on propose le choix des lignes du réseau
            $lineList = Line::getList();
            $this->template->setVariable('line_list', $lineList);
        } else {
            // Si plusieurs réseaux : on propose le choix du réseau
            $this->template->setVariable('network_list', $networkList);            
        }
        
        $this->template->setVariable('current_date_human', $currentDateTime->format(Config::get('format', 'Date', 'FrenchShort')));
        $this->template->fetch('module/schedule/search.php');
    }
}