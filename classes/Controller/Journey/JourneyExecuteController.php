<?php

namespace Nv2\Controller\Journey;

use Nv2\Lib\Nv2\Controller\Controller;
use Nv2\Lib\Nv2\Config\Config;

class JourneyExecuteController extends Controller
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
        if (isset($vars['option']['date']) && isset($vars['option']['time'])) {
            $datetime = $this->getDateTime($vars['option']['date'], $vars['option']['time'], $vars['option']['time_type']);
        } else {
            $datetime = new \DateTime();
            $datetime = $datetime->format(Config::get('format', 'Date', 'Iso8861Short'));
        }

        // Si selection depuis précision (radio contient URI et Name)
        if (isset($vars['origin']['data'])) {
            $data = explode(';', $vars['origin']['data']);
            $vars['origin']['name'] = $data[0];
            $vars['origin']['uri'] = $data[1];
        }
        if (isset($vars['destination']['data'])) {
            $data = explode(';', $vars['destination']['data']);
            $vars['destination']['name'] = $data[0];
            $vars['destination']['uri'] = $data[1];
        }

        if (isset($vars['origin']['uri']) && isset($vars['destination']['uri'])
        && $vars['origin']['uri'] && $vars['destination']['uri']) {
            // Si URI défini : sélection depuis Firstletter ou Precision
            // --> Résultats
            $this->redirect('journey/results/'
                . urlencode($vars['origin']['name']) . '/'
                . urlencode($vars['origin']['uri']) . '/'
                . urlencode($vars['destination']['name']) . '/'
                . urlencode($vars['destination']['uri']) . '/'
                . $this->getClockwise($vars['option']['clockwise']) . '/'
                . urlencode($datetime)
            );
        } else if (isset($vars['origin']['coords']) && isset($vars['destination']['coords'])
        && $vars['origin']['coords'] && $vars['destination']['coords']) {
            // Si Coord définies : sélection depuis carte
            // --> Résultats
            $this->redirect('journey/results/'
                . '-' . '/'
                . urlencode('coord:' . $vars['origin']['coords']) . '/'
                . '-' . '/'
                . urlencode('coord:' . $vars['destination']['coords']) . '/'
                . $this->getClockwise($vars['option']['clockwise']) . '/'
                . urlencode($datetime)
            );
        } else if (isset($vars['origin']['name']) || isset($vars['destination']['name'])
        && $vars['origin']['name'] || $vars['destination']['name']) {
            // Points saisis par l'utilisateur
            // --> Demande de précision
            if (!isset($vars['origin']['name'])) $vars['origin']['name'] = '';
            if (!isset($vars['destination']['name'])) $vars['destination']['name'] = '';

            $this->redirect(
                'journey/precision/'
                . urlencode($vars['origin']['name']) . '/'
                . urlencode($vars['destination']['name']) . '/'
                . $this->getClockwise($vars['option']['clockwise']) . '/'
                . urlencode($datetime)
            );
        } else {
            // Aucun paramètre
            // --> Formulaire de recherche
            $this->redirect('journey/search');
        }
    }

    private function getDateTime($date, $time, $type)
    {
        switch ($type) {
            default:
            case 'french':
                $dateData = explode('/', $date);
                $timeData = explode(':', $time);
                $dateTime = new \DateTime();
                $dateTime->setDate($dateData[2], $dateData[1], $dateData[0]);
                $dateTime->setTime($timeData[0], $timeData[1], 0);
                break;
        }
        
        return $dateTime->format(Config::get('format', 'Date', 'Iso8861Full'));
    }

    private function getClockwise($clockwise)
    {
        if ($clockwise == 0 || $clockwise == 'arrival') {
            return 'arrival';
        } else {
            return 'departure';
        }
    }
}