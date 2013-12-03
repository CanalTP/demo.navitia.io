<?php

$ctrl = new Nv2\Controller\Schedule\ScheduleStopExecuteController($this->Request);
$ctrl->setTemplate($this->ModuleTemplate);
$ctrl->run();