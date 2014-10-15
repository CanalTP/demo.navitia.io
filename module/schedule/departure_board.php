<?php

$ctrl = new Nv2\Controller\Schedule\ScheduleDepartureBoardController($request);
$ctrl->setTemplate($template);
$ctrl->run();