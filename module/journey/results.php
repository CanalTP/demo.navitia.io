<?php

$ctrl = new Nv2\Controller\Journey\JourneyResultsController($this->Request);
$ctrl->setTemplate($this->ModuleTemplate);
$ctrl->run();