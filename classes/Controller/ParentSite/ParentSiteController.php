<?php

namespace Nv2\Controller\ParentSite;

use Nv2\Lib\Nv2\Controller\Controller;
use Nv2\Lib\Nv2\Http\Request;

class ParentSiteController extends Controller
{
    public function __construct(Request $request)
    {
        parent::__construct($request);   
    }
    
    public function run()
    {
        $content = $this->getBarHtmlCode();
        
        echo $content;
        
        $this->template->setPagelayoutActive(false);
    }
    
    private function getBarHtmlCode()
    {
        $code = $this->getNavitiaIoHtmlCode();
        
        return $this->extractBarFromPageCode($code);
    }
    
    private function getNavitiaIoHtmlCode()
    {
        $url = 'http://www.navitia.io';
        
        $ch = curl_init($url);
        ob_start();
        curl_exec($ch);
        curl_close($ch);
        $content = ob_get_contents();
        ob_clean();
        
        return $content;
    }
    
    private function extractBarFromPageCode($code)
    {
        $dom = new \DOMDocument();
        $root = @$dom->loadHtml($code);
        
        $barElement = $dom->getElementById('navbar');
        $code = $dom->saveHtml($barElement);
        
        $code = $this->replaceLinks($code);
        
        return $code;
    }
    
    private function replaceLinks($code)
    {
        $code = preg_replace('#href="(.+)"#isU', 'href="http://www.navitia.io/$1"', $code);

        return $code;
    }
}