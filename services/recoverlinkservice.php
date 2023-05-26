<?php
date_default_timezone_set('America/Panama');
include("connection.php");

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    $message = htmlentities("Solo se admite el método POST");
	exit();
}

$password = $_POST['apitoken'];
$passwordxt = $_POST['xttoken'];
$linked = $_POST['linked'];
$elid = $_POST['elid'];
$myip = getIPAddress();
$agent =  $_SERVER['HTTP_USER_AGENT'];
$ismobile = 'desktop';
if(isMobile()){
	$ismobile = 'mobile';
}
$out = [];

//Verificar si el usuario existe en la tabla de restauro
$sqlCheckUser = "select * from inadeh_users_restore upr where upr.user_id = '$elid' and upr.link = '$linked';";

$resCheckUser = mysqli_query($con, $sqlCheckUser);

$rowCheckUser = $resCheckUser->fetch_assoc();

$numRows = $resCheckUser->num_rows;

if ($numRows == 1) {
	$linkdate = $rowCheckUser['created_at'];
	$to_time = time();
	$from_time = strtotime($linkdate);
	$difftime = abs($to_time - $from_time) / 60;
	
	if($difftime > 7){
		$out[0]="EXPIRADO";
		$out[1]=$difftime;
	}else{
		$sqlCheckCedula = "select cedula_responsable from ".$tbl." t1 where md5(t1.id) = '$elid';";
		$resCheckCed = mysqli_query($con, $sqlCheckCedula);
		if ($resCheckCed){
			$rowCheckCed = $resCheckCed->fetch_assoc();
			$userCed = $rowCheckCed['cedula_responsable'];
			$sqlUpdateUser = "UPDATE ".$tbl." SET password='$password', password_xt='$passwordxt' WHERE md5(id)='$elid';";
			if (mysqli_query($con, $sqlUpdateUser)) {
				$out[0]="UPDATED";
				$nowdate = date('Y-m-d H:i:s');
				$sqlUpdateLog = "INSERT INTO `asistencia_inadeh_log`(`user_id`, `fecha`, `action`, `ip_ingreso`, `device`, `platform`) VALUES ('$userCed','$nowdate','Restore_pass','$myip','$ismobile','$agent')";
				if(mysqli_query($con, $sqlUpdateLog)){
					$out[1]="LOGGED";
				}
			} else {
				$out[0]="ERRORSQL";
				$out[1]=$sqlUpdateUser;
			}
		// END CHECKCEDULA
		}else{
			$out[0]="ERRORCHECKCED";
		} 
	}
}else{
	$out[0]="ERRORNUM";
}

header('Content-type:application/json;charset=utf-8');
echo json_encode($out);

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

