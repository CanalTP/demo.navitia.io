<?php

$ctrl = new Nv2\Controller\Schedule\ScheduleProximityBoardController($request);
$ctrl->setTemplate($template);
$ctrl->run();