<?php

include('config/webservice.php');

$crossDomainUrl = $config['Url']['CrossDomainNavitia'];
$token = $config['Token'];
$crossDomainUrlData = explode('?url=', $crossDomainUrl);

$authorizedUrl = $crossDomainUrlData[1];

if (strstr($_GET['url'], $authorizedUrl)) {
    if (isset($_GET['url']) && $_GET['url']) {
        header('content-type: application/json; charset=UTF-8');
        $ch = curl_init($_GET['url']);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: ' . $token));
        curl_exec($ch);
        curl_close($ch);
    }
}