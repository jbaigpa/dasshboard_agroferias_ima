<?php
#include("verifyReCapcha.php");
include("connection.php");

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    $message = htmlentities("Solo se admite el método POST");
   # response($message, 500);
}

$elid = $_POST['middletoken'];


//
$myip = getIPAddress();
$agent =  $_SERVER['HTTP_USER_AGENT'];
$ismobile = 'desktop';
if(isMobile()){
	$ismobile = 'mobile';
}
//

//Verificar si el usuario existe
$sqlCheckUser = "select * from users c where md5(c.cedula) = '$elid';";
$resCheckUser = mysqli_query($con, $sqlCheckUser);

if(!$resCheckUser) {
   //Mensaje de error
   exit("ERROR:".$sqlCheckUser);
}   


$rowCheckUser = $resCheckUser->fetch_assoc();

$numRows = $resCheckUser->num_rows;

if ($numRows == 0) {
    exit("ERROR: NO EXISTE");
}

if($numRows == 1){
    $sqlUpdateUser = "UPDATE users SET active=1 WHERE md5(cedula)='$elid';";
	$actionLog = "disable_user_app";
	$actionRes = "disable_user_app_ok";
} 

$nowdate = date('Y-m-d H:i:s');
if (mysqli_query($con, $sqlUpdateUser)) {
	$cedulaResponsable = $rowCheckUser['cedula'];
	$InsertUpdateId = $_SESSION['userid'];
    $sqlUpdateLog = "INSERT INTO `logs`(`user`, `created_at`, `type`, `description`, `ip_ingreso`, `device`, `platform`,`insert_update_by`) VALUES ('$cedulaResponsable','$nowdate','$actionLog','$actionRes','$myip','$ismobile','$agent','$InsertUpdateId')";
			if(mysqli_query($con, $sqlUpdateLog)){
				$out[1]="LOGGED";
			}
	exit("DELETED");	
} else {
    exit("ERROR: UPDATE".$sqlUpdateUser);
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