<?php

namespace Nv2\Lib\Nv2\Controller;

use Nv2\Lib\Nv2\Http\Request;
use Nv2\Lib\Nv2\Template\Template;

class Controller
{
    protected $request;
    protected $template;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param Template $template
     */
    public function setTemplate(Template $template)
    {
        $this->template = $template;
    }

    /**
     * @param string $uri
     */
    protected function redirect($uri, $region = null)
    {
        $this->request->redirect($uri, $region);
    }
}
