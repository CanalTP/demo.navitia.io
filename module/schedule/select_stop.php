<?php

$ctrl = new Nv2\Controller\Schedule\ScheduleSelectStopController($request);
$ctrl->setTemplate($template);
$ctrl->run();