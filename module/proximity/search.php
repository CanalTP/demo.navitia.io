<?php

$ctrl = new Nv2\Controller\Proximity\ProximitySearchController($this->Request);
$ctrl->setTemplate($this->ModuleTemplate);
$ctrl->run();