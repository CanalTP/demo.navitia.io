<?php

$ctrl = new Nv2\Controller\Schedule\ScheduleLineExecuteController($this->Request);
$ctrl->setTemplate($this->ModuleTemplate);
$ctrl->run();