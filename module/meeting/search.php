<?php

$ctrl = new Nv2\Controller\Meeting\MeetingSearchController($this->Request);
$ctrl->setTemplate($this->ModuleTemplate);
$ctrl->run();