<?php

$ctrl = new Nv2\Controller\Schedule\ScheduleSearchController($request);
$ctrl->setTemplate($template);
$ctrl->run();