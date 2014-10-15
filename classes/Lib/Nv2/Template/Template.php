<?php

namespace Nv2\Lib\Nv2\Template;

class Template
{
    private $content;
    private $variableList;
    private $pagelayoutActive;

    public function __construct()
    {
        $this->pagelayoutActive = true;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setVariable($name, $value)
    {
        $this->variableList[$name] = $value;
    }

    public function setPagelayoutActive($active)
    {
        $this->pagelayoutActive = $active;
    }

    public function getPagelayoutActive()
    {
        return $this->pagelayoutActive;
    }

    public function fetch($file)
    {
        $template_file = TEMPLATE_DIR . '/' . $file;

        if (file_exists($template_file)) {
            ob_start();
            if (is_array($this->variableList)) {
                foreach ($this->variableList as $varName => $varValue) {
                    ${$varName} = $varValue;
                }
            }
            include($template_file);
            $this->content = ob_get_clean();
        } else {
            die('Template file "template/' . $file . '" not found!');
        }
    }
}
