<?php

namespace Nv2\Controller\Autocomplete;

use Nv2\Lib\Nv2\Controller\Controller;

class AutocompleteAjaxHtmlController extends Controller
{
    public function __construct($request)
    {
        parent::__construct($request);
    }
    
    public function run()
    {
        $this->template->setPagelayoutActive(false);
    
        if ($this->request->getParam(0) == 'container') {
            $this->template->fetch('module/autocomplete/ajax_html_container.php');
        } else {
            $this->template->fetch('module/autocomplete/ajax_html_item.php');
        }
    }
}