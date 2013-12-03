<?php

$ctrl = new Nv2\Controller\Meeting\MeetingResultsController($this->Request);
$ctrl->setTemplate($this->ModuleTemplate);
$ctrl->run();