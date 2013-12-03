<?php

$ctrl = new Nv2\Controller\Journey\JourneySearchController($this->Request);
$ctrl->setTemplate($this->ModuleTemplate);
$ctrl->run();