<?php

$ctrl = new Nv2\Controller\Journey\JourneyPrecisionController($this->Request);
$ctrl->setTemplate($this->ModuleTemplate);
$ctrl->run();