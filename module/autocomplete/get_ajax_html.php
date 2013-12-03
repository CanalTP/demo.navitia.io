<?php

$ctrl = new Nv2\Controller\Autocomplete\AutocompleteAjaxHtmlController($this->Request);
$ctrl->setTemplate($this->ModuleTemplate);
$ctrl->run();