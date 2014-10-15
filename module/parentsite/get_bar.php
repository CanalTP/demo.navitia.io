<?php

$ctrl = new Nv2\Controller\ParentSite\ParentSiteController($request);
$ctrl->setTemplate($template);
$ctrl->run();