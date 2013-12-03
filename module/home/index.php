<?php

$ctrl = new Nv2\Controller\Home\HomeController($this->Request);
$ctrl->setTemplate($this->ModuleTemplate);
$ctrl->run();