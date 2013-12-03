<?php

$ctrl = new Nv2\Controller\Meeting\MeetingExecuteController($this->Request);
$ctrl->setTemplate($this->ModuleTemplate);
$ctrl->run();