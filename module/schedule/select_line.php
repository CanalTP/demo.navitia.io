<?php

$ctrl = new Nv2\Controller\Schedule\ScheduleSelectLineController($this->Request);
$ctrl->setTemplate($this->ModuleTemplate);
$ctrl->run();