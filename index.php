<?php

define('VM_URL', 'local-vm.dev');

$url = 'http://' . VM_URL . '/' . rawurlencode($_GET['url']);

$curl_handle = curl_init();
if ($_GET) {
    $getvars = '';
    foreach ($_GET as $key => $val) {
        if ($key != 'url') {
            $getvars .= $key;
            if ($val) {
                $getvars .= '=' . $val;
            }
            $getvars .= '&';
        }
    }
    $getvars = substr($getvars, 0, strlen($getvars) - 1);
    $url .= '?' . $getvars;
}

curl_setopt($curl_handle, CURLOPT_URL, $url);
curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
curl_setopt($curl_handle, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
// curl_setopt($curl_handle, CURLOPT_USERAGENT, 'Mozilla/5.0 (iPhone; CPU iPhone OS 6_0 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10A5376e Safari/8536.25');
curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);

if ($_POST) {
    $postvars = '';
    foreach( $_POST as $key => $val){
        $postvars .= $key . '=' . $val . '&';
    }
    $postvars = substr($postvars, 0, strlen($postvars) - 1);
    curl_setopt($curl_handle, CURLOPT_POST, true);
    curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $postvars);
}

$buffer = curl_exec($curl_handle);
header('Content-Type: ' . curl_getinfo($curl_handle, CURLINFO_CONTENT_TYPE));
curl_close($curl_handle);

echo preg_replace(array(
    '/http:\/\/' . VM_URL . '\//'
), array(
    '/'
), $buffer);
