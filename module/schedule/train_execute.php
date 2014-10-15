<?php

$ctrl = new Nv2\Controller\Schedule\ScheduleTrainExecuteController($request);
$ctrl->setTemplate($template);
$ctrl->run();