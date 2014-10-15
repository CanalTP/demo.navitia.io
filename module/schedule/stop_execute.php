<?php

$ctrl = new Nv2\Controller\Schedule\ScheduleStopExecuteController($request);
$ctrl->setTemplate($template);
$ctrl->run();