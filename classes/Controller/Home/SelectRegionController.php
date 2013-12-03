<?php

namespace Nv2\Controller\Home;

use Nv2\Lib\Nv2\Controller\Controller;

class SelectRegionController extends Controller
{
    public function run()
    {
        $params = $this->request->getParams();        
        $this->redirect('journey/search', $params['region']);
    }
}