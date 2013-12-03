<?php

$ctrl = new Nv2\Controller\Proximity\ProximityExecuteController($this->Request);
$ctrl->setTemplate($this->ModuleTemplate);
$ctrl->run();