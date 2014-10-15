<?php

$ctrl = new Nv2\Controller\Journey\JourneyExecuteController($request);
$ctrl->setTemplate($template);
$ctrl->run();