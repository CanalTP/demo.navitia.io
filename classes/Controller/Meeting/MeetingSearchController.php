<?php

namespace Nv2\Controller\Meeting;

use Nv2\Lib\Nv2\Controller\Controller;
use Nv2\Lib\Nv2\Config\Config;

class MeetingSearchController extends Controller
{
    public function __construct($request)
    {
        parent::__construct($request);
    }

    public function run()
    {
        $currentDateTime = new \DateTime();

        $this->template->setVariable('current_date_human', $currentDateTime->format(Config::get('format', 'Date', 'FrenchShort')));
        $this->template->setVariable('current_time_human', $currentDateTime->format('H:i'));

        $this->template->fetch('module/meeting/search.php');
    }
}