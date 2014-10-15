<?php

$ctrl = new Nv2\Controller\Journey\JourneySearchController($request);
$ctrl->setTemplate($template);
$ctrl->run();