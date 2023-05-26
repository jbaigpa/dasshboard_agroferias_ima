<?php


include("connection.php");

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    $message = htmlentities("Solo se admite el método POST");
    # response($message, 500);
}

$estatus = $_POST['estatus'];
$estatus_desc = $_POST['estatus_desc'];
$userid = $_POST['userid'];
$reportid = $_POST['id'];
$nivel  = $_POST['level'];

$myip = getIPAddress();
$agent =  $_SERVER['HTTP_USER_AGENT'];
$ismobile = 'desktop';
if(isMobile()){
	$ismobile = 'mobile';
}

$insertdate = date('Y-m-d H:i:s');
$sqlInsertUser = "INSERT INTO `reportes_historial`(`reporte_id`, `estatus`, `estatus_desc`, `created_by`, `created_at`) 
VALUES('$reportid', '$estatus','$estatus_desc','$userid','$insertdate');";

	if (mysqli_query($con, $sqlInsertUser)) {
		$nowdate = date('Y-m-d H:i:s');
		$elid = md5(mysqli_insert_id($con));
        
		exit("SAVED");
    }else{
		exit('ERROR');
	}

function getIPAddress() {  
    //whether ip is from the share internet  
     if(!empty($_SERVER['HTTP_CLIENT_IP'])) {  
                $ip = $_SERVER['HTTP_CLIENT_IP'];  
        }  
    //whether ip is from the proxy  
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {  
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];  
     }  
//whether ip is from the remote address  
    else{  
             $ip = $_SERVER['REMOTE_ADDR'];  
     }  
     return $ip;  
}  

function isMobile() {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}