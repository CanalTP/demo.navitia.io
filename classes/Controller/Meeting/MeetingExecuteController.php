<?php

namespace Nv2\Controller\Meeting;

use Nv2\Lib\Nv2\Controller\Controller;
use Nv2\Lib\Nv2\Config\Config;

class MeetingExecuteController extends Controller
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
        if (isset($vars['first_from']['data'])) {
            $data = explode(';', $vars['first_from']['data']);
            $vars['first_from']['name'] = $data[0];
            $vars['first_from']['uri'] = $data[1];
        }
        if (isset($vars['second_from']['data'])) {
            $data = explode(';', $vars['second_from']['data']);
            $vars['second_from']['name'] = $data[0];
            $vars['second_from']['uri'] = $data[1];
        }
        if (isset($vars['third_from']['data'])) {
            $data = explode(';', $vars['third_from']['data']);
            $vars['third_from']['name'] = $data[0];
            $vars['third_from']['uri'] = $data[1];
        }

        if (isset($vars['first_from']['uri']) && isset($vars['second_from']['uri'])
        && $vars['first_from']['uri'] && $vars['second_from']['uri']) {
            // Si URI défini : sélection depuis Firstletter ou Precision
            // --> Résultats
            $this->redirect('meeting/results/'
                . urlencode($vars['first_from']['person']) . '/'
                . urlencode($vars['first_origin']['name']) . '/'
                . urlencode($vars['first_origin']['uri']) . '/'
                . urlencode($vars['second_origin']['person']) . '/'
                . urlencode($vars['second_origin']['name']) . '/'
                . urlencode($vars['second_origin']['uri']) . '/'
                . urlencode($vars['third_origin']['person']) . '/'
                . urlencode($vars['third_origin']['name']) . '/'
                . urlencode($vars['third_origin']['uri']) . '/'
                . $this->getClockwise($vars['option']['clockwise']) . '/'
                . urlencode($datetime)
            );
        } else if (isset($vars['first_from']['coords']) && isset($vars['second_from']['coords'])
        && $vars['first_origin']['coords'] && $vars['second_origin']['coords']) {
            // Si Coord définies : sélection depuis carte
            // --> Résultats
            $this->redirect('journey/results/'
                . '-' . '/'
                . '-' . '/'
                . urlencode('coord:' . $vars['first_origin']['coords']) . '/'
                . '-' . '/'
                . '-' . '/'
                . urlencode('coord:' . $vars['second_origin']['coords']) . '/'
                . '-' . '/'
                . '-' . '/'
                . urlencode('coord:' . $vars['third_origin']['coords']) . '/'
                . $this->getClockwise($vars['option']['clockwise']) . '/'
                . urlencode($datetime)
            );
        } else if (isset($vars['first_origin']['name']) || isset($vars['second_origin']['name'])
        && $vars['origin']['name'] || $vars['second_origin']['name']) {
            // Points saisis par l'utilisateur
            // --> Demande de précision
            if (!isset($vars['first_origin']['name'])) $vars['first_origin']['name'] = '';
            if (!isset($vars['second_origin']['name'])) $vars['second_origin']['name'] = '';
            if (!isset($vars['third_origin']['name'])) $vars['third_origin']['name'] = '';

            $this->redirect(
                'journey/precision/'
                . urlencode($vars['first_origin']['person']) . '/'
                . urlencode($vars['first_origin']['name']) . '/'
                . urlencode($vars['second_origin']['person']) . '/'
                . urlencode($vars['second_origin']['name']) . '/'
                . urlencode($vars['third_origin']['person']) . '/'
                . urlencode($vars['third_origin']['name']) . '/'
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