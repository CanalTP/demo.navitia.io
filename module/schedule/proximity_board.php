<?php

$ctrl = new Nv2\Controller\Schedule\ScheduleProximityBoardController($this->Request);
$ctrl->setTemplate($this->ModuleTemplate);
$ctrl->run();