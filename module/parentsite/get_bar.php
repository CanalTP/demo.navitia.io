<?php

$ctrl = new Nv2\Controller\ParentSite\ParentSiteController($this->Request);
$ctrl->setTemplate($this->ModuleTemplate);
$ctrl->run();