<?php

$ctrl = new Nv2\Controller\Home\SelectRegionController($request);
$ctrl->setTemplate($template);
$ctrl->run();