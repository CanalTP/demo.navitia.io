<?php

$ctrl = new Nv2\Controller\Schedule\ScheduleLineExecuteController($request);
$ctrl->setTemplate($template);
$ctrl->run();