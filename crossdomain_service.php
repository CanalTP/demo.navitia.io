<?php

include('config/webservice.php');

$crossDomainUrl = $config['Url']['CrossDomainNavitia'];
$token = $config['Token'];
$crossDomainUrlData = explode('?url=', $crossDomainUrl);

$authorizedUrl = $crossDomainUrlData[1];
$requestedUrl = filter_input(INPUT_GET, 'url', FILTER_SANITIZE_URL);

if (strstr($requestedUrl, $authorizedUrl)) {
    if ($requestedUrl) {
        header('content-type: application/json; charset=UTF-8');
        $ch = curl_init($requestedUrl);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: ' . $token));
        curl_exec($ch);
        curl_close($ch);
    }
}