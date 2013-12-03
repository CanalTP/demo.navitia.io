<?php

$ctrl = new Nv2\Controller\Schedule\ScheduleSelectStopController($this->Request);
$ctrl->setTemplate($this->ModuleTemplate);
$ctrl->run();