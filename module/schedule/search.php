<?php

$ctrl = new Nv2\Controller\Schedule\ScheduleSearchController($this->Request);
$ctrl->setTemplate($this->ModuleTemplate);
$ctrl->run();