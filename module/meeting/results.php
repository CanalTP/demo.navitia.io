<?php

$ctrl = new Nv2\Controller\Meeting\MeetingResultsController($request);
$ctrl->setTemplate($template);
$ctrl->run();