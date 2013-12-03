<?php

$ctrl = new Nv2\Controller\Home\SelectRegionController($this->Request);
$ctrl->setTemplate($this->ModuleTemplate);
$ctrl->run();