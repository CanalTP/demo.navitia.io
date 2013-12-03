<?php 

namespace Nv2\Lib\Nv2\Service;

use Nv2\Lib\Nv2\Debug\Debug;
use Nv2\Lib\Nv2\Service\ServiceRequest;

class ParallelServiceRequest
{
    private $curlMultiHandle;
    private $curlHandles;
    private $curlHandleIndex;
    private $requests;
    
    private function __construct()
    {
        $this->curlMultiHandle = curl_multi_init();
        $this->curlHandles = null;
        $this->curlHandleIndex = 0;
    }
    
    public static function create()
    {
        return new self();
    }
    
    public function addRequest(ServiceRequest $request)
    {
        $url = $request->getUrl();
        $this->requests[] = $request;
        $this->curlHandles[$this->curlHandleIndex] = curl_init($url);
        
        if (isset($_GET['debug']) && $_GET['debug'] == 1) {
            Debug::addServiceRequest('', $url, 0);
        }
        if (isset($_GET['debug']) && $_GET['debug'] == 2) {
            echo '<a href="' . $url . '">' . $url . '</a><br />';
        }
        
        curl_setopt($this->curlHandles[$this->curlHandleIndex], CURLOPT_RETURNTRANSFER, 1);
        curl_multi_add_handle($this->curlMultiHandle, $this->curlHandles[$this->curlHandleIndex]);
        $this->curlHandleIndex++;
        return $this;
    }
    
    public function retrieveAllFeedsContent()
    {
        $this->execute();
        
        $feedContent = array();
        foreach ($this->curlHandles as $handleIndex => $handle) {
            $info = curl_getinfo($this->curlHandles[$handleIndex]);
            $content = curl_multi_getcontent($this->curlHandles[$handleIndex]);
            $hasError = false;
            $errorCode = 0;
            
            if ($info['http_code'] >= 400) {
                $hasError = true;
                $errorCode = $info['http_code'];
                $content = null;
            }
            
            if (!$content && $info['http_code'] < 400) {
                $hasError = true;
                $errorCode = 901;
                $content = null;
            }
            
            $feedContent[] = array(
                'content' => $content,
                'hasError' => $hasError,
                'errorCode' => $errorCode,
                'flags' => $this->requests[$handleIndex]->getFlags(),
            );
            curl_multi_remove_handle($this->curlMultiHandle, $this->curlHandles[$handleIndex]);
            curl_close($this->curlHandles[$handleIndex]);
        }
        curl_multi_close($this->curlMultiHandle);
        
        return $feedContent;
    }
    
    private function execute()
    {
        $runningHandles = null;
        
        do {
            $returnValue = curl_multi_exec($this->curlMultiHandle, $runningHandles);
        } while ($returnValue == CURLM_CALL_MULTI_PERFORM);
        
        while ($runningHandles && $returnValue == CURLM_OK) {
            $numberReady = curl_multi_select($this->curlMultiHandle);
            if ($numberReady != -1) {
                do {
                    $returnValue = curl_multi_exec($this->curlMultiHandle, $runningHandles);
                } while ($returnValue == CURLM_CALL_MULTI_PERFORM);
            }
        }
    }
}