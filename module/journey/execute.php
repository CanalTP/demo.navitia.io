<?php

$ctrl = new Nv2\Controller\Journey\JourneyExecuteController($this->Request);
$ctrl->setTemplate($this->ModuleTemplate);
$ctrl->run();