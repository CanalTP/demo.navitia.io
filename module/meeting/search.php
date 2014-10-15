<?php

$ctrl = new Nv2\Controller\Meeting\MeetingSearchController($request);
$ctrl->setTemplate($template);
$ctrl->run();