<?php

$ctrl = new Nv2\Controller\Meeting\MeetingExecuteController($request);
$ctrl->setTemplate($template);
$ctrl->run();