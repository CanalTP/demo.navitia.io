<?php

$ctrl = new Nv2\Controller\Autocomplete\AutocompleteAjaxHtmlController($request);
$ctrl->setTemplate($template);
$ctrl->run();