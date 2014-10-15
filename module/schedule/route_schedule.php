<?php

$ctrl = new Nv2\Controller\Schedule\ScheduleRouteScheduleController($request);
$ctrl->setTemplate($template);
$ctrl->run();