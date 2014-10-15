<?php

$ctrl = new Nv2\Controller\Proximity\ProximitySearchController($request);
$ctrl->setTemplate($template);
$ctrl->run();