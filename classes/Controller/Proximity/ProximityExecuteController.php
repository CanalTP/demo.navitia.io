<?php

namespace Nv2\Controller\Proximity;

use Nv2\Lib\Nv2\Controller\Controller;

class ProximityExecuteController extends Controller
{
    public function __construct($request)
    {
        parent::__construct($request);
    }

    public function run()
    {
        $this->dispatchSearchResult($this->request->getParams());
    }

    private function dispatchSearchResult($vars)
    {
        // Si selection depuis précision (radio contient URI et Name)
        if (isset($vars['point']['data'])) {
            $data = explode(';', $vars['point']['data']);
            $vars['point']['name'] = $data[0];
            $vars['point']['uri'] = $data[1];
        }
        
        if (isset($vars['point']['uri']) && $vars['point']['uri']) {
            // Si URI défini : sélection depuis Firstletter ou Precision
            // --> Résultats
            $this->redirect('proximity/results/'
                . urlencode($vars['point']['name']) . '/'
                . urlencode($vars['point']['coords'])
            );
        } else if (isset($vars['point']['coords']) && $vars['point']['coords']) {
            // Si Coord définies : sélection depuis carte
            // --> Résultats
            $this->redirect('proximity/results/'
                . '-' . '/'
                . urlencode($vars['point']['coords'])
            );
        } else if (isset($vars['point']['name']) && $vars['point']['name']) {
            // Points saisis par l'utilisateur
            // --> Demande de précision
            if (!isset($vars['point']['name'])) $vars['point']['name'] = '';
        
            $this->redirect(
                'proximity/precision/'
                . urlencode($vars['point']['name'])
            );
        } else {
            // Aucun paramètre
            // --> Formulaire de recherche
            $this->redirect('proximity/search');
        }
    }
}