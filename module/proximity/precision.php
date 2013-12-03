<?php

$ctrl = new Nv2\Controller\Proximity\ProximityPrecisionController($this->Request);
$ctrl->setTemplate($this->ModuleTemplate);
$ctrl->run();