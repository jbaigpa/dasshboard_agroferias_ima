<?php 

error_reporting(0);
set_time_limit(0);
date_default_timezone_set('America/Panama');

define("RECAPTCHA_V3_SECRET_KEY", '6LeiVcYaAAAAAGY0xIw4o2LOrCkH9nwRcvrYxd2K');

$_token = $_POST['_token'];
$action = $_POST['action'];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('secret' => RECAPTCHA_V3_SECRET_KEY, 'response' => $_token)));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);
$arrResponse = json_decode($response, true);



if ($arrResponse["success"] == '1' && $arrResponse["action"] == $action && $arrResponse["score"] >= 0.5) {
    
} else {
    exit("ERROR_CAPCHA");
}
