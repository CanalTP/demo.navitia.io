<?php

$ctrl = new Nv2\Controller\Schedule\ScheduleCoordExecuteController($request);
$ctrl->setTemplate($template);
$ctrl->run();