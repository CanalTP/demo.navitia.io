<?php

namespace Nv2\Controller\Journey;

use Nv2\Lib\Nv2\Controller\Controller;
use Nv2\Lib\Nv2\Config\Config;
use Nv2\Lib\Nv2\Service\IdConverter;

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
        $idConverter = new IdConverter();

        if (isset($vars['option']['date']) && isset($vars['option']['time'])) {
            $datetime = $this->getDateTime($vars['option']['date'], $vars['option']['time'], $vars['option']['time_type']);
            $formattedDatetime = $datetime;
        } else {
            $datetime = new \DateTime();
            $formattedDatetime = $datetime->format(Config::get('format', 'Date', 'Iso8861Short'));
        }

        // Si selection depuis précision (radio contient URI et Name)
        if (isset($vars['from']['data'])) {
            $data = explode(';', $vars['from']['data']);
            $vars['from']['name'] = $data[0];
            $vars['from']['id'] = $idConverter->encodeSlashes($data[1]);
        }
        if (isset($vars['to']['data'])) {
            $data = explode(';', $vars['to']['data']);
            $vars['to']['name'] = $data[0];
            $vars['to']['id'] = $idConverter->encodeSlashes($data[1]);
        }

        if (isset($vars['from']['id']) && isset($vars['to']['id'])
        && $vars['from']['id'] && $vars['to']['id']) {            
            // Si URI défini : sélection depuis Firstletter ou Precision
            // --> Résultats
            $this->redirect('journey/results/'
                . urlencode($vars['from']['name']) . '/'
                . urlencode($vars['from']['id']) . '/'
                . urlencode($vars['to']['name']) . '/'
                . urlencode($vars['to']['id']) . '/'
                . $vars['option']['datetime_represents'] . '/'
                . urlencode($formattedDatetime)
            );
        } else if (isset($vars['from']['coords']) && isset($vars['to']['coords'])
        && $vars['from']['coords'] && $vars['to']['coords']) {
            // Si Coord définies : sélection depuis carte
            // --> Résultats
            $this->redirect('journey/results/'
                . '-' . '/'
                . urlencode('coord:' . $vars['from']['coords']) . '/'
                . '-' . '/'
                . urlencode('coord:' . $vars['to']['coords']) . '/'
                . $vars['option']['datetime_represents'] . '/'
                . urlencode($formattedDatetime)
            );
        } else if (isset($vars['from']['name']) || isset($vars['to']['name'])
        && $vars['from']['name'] || $vars['to']['name']) {
            // Points saisis par l'utilisateur
            // --> Demande de précision
            if (!isset($vars['from']['name'])) {
                $vars['from']['name'] = '';
            }
            if (!isset($vars['to']['name'])) {
                $vars['to']['name'] = '';
            }

            $this->redirect(
                'journey/precision/'
                . urlencode($vars['from']['name']) . '/'
                . urlencode($vars['to']['name']) . '/'
                . $vars['option']['datetime_represents'] . '/'
                . urlencode($formattedDatetime)
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
}