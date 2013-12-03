<?php

$ctrl = new Nv2\Controller\Schedule\ScheduleRouteScheduleController($this->Request);
$ctrl->setTemplate($this->ModuleTemplate);
$ctrl->run();