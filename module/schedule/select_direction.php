<?php

$ctrl = new Nv2\Controller\Schedule\ScheduleSelectDirectionController($this->Request);
$ctrl->setTemplate($this->ModuleTemplate);
$ctrl->run();