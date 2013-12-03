<?php

$ctrl = new Nv2\Controller\Schedule\ScheduleDepartureBoardController($this->Request);
$ctrl->setTemplate($this->ModuleTemplate);
$ctrl->run();