<?php

$ctrl = new Nv2\Controller\Schedule\ScheduleCoordExecuteController($this->Request);
$ctrl->setTemplate($this->ModuleTemplate);
$ctrl->run();