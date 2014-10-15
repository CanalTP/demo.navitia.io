<?php

$ctrl = new Nv2\Controller\Home\HomeController($request);
$ctrl->setTemplate($template);
$ctrl->run();