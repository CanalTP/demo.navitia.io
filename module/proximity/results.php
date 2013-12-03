<?php

$ctrl = new Nv2\Controller\Proximity\ProximityResultsController($this->Request);
$ctrl->setTemplate($this->ModuleTemplate);
$ctrl->run();