<?php

namespace Nv2\Controller\Proximity;

use Nv2\Lib\Nv2\Controller\Controller;
use Nv2\Model\Entity\Transport\Line;

class ProximitySearchController extends Controller
{
    public function __construct($request)
    {
        parent::__construct($request);
    }

    public function run()
    {
        $this->template->fetch('module/proximity/search.php');
    }
}