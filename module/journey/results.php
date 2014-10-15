<?php

$ctrl = new Nv2\Controller\Journey\JourneyResultsController($request);
$ctrl->setTemplate($template);
$ctrl->run();