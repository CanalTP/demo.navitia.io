<?php

$ctrl = new Nv2\Controller\Schedule\ScheduleTrainExecuteController($this->Request);
$ctrl->setTemplate($this->ModuleTemplate);
$ctrl->run();