<?php

header('Access-Control-Allow-Origin: *');
#error_reporting(0);
date_default_timezone_set('America/Panama');

include("connection.php");

$out = [];
$out["check"]="error";
$out["msg"]="Error code 000";


if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    $out["msg"] = htmlentities("Solo se admite el método POST");
   # response($message, 500);
}

$nombre = $_POST['nombre'];
$type = $_POST['type'];
$param = $_POST['param'];
$admin_id = $_POST['admin_id'];
$config_id = $_POST['config_id'];
$siteKey = $_POST['siteKey'];
$action  = $_POST['action'];
$active  = $_POST['active'];

if($siteKey == '6LeiVcYaAAFgsghshGGshuesueyYDYEKmSBbagakckdlslsJdjshjhdassad0odoOOodjdjjswheD1cpDx2jP8P00oSGokBgnG9hKDob' ){
	//
	$myip = getIPAddress();
	$agent =  $_SERVER['HTTP_USER_AGENT'];
	$ismobile = 'desktop';
	if(isMobile()){
		$ismobile = 'mobile';
	}
	//

	//Verificar si el config existe
	$sqlCheckUser = "select * from `config` where config_id = '$config_id'";
	$resCheckUser = mysqli_query($con, $sqlCheckUser);

	if(!$resCheckUser) {
	   //Mensaje de error
	   exit("ERROR");
	}   

	$numRows = $resCheckUser->num_rows;

	if ($numRows > 0) {
	   
		$nowdate = date('Y-m-d H:i:s');
		$sqlUpdateUser = "UPDATE `config` SET `name`='$nombre',`modified_at`='$nowdate', `type`='$type', `parameters`='$param', `active`='$active', `modify_by`='$admin_id' WHERE `config_id` ='$config_id';";
		$actionLog = "Update_user_password";


		if (mysqli_query($con, $sqlUpdateUser)) {
			$elid = $id;
			$InsertUpdateId = $_SESSION['userid'];
			$sqlUpdateLog = "INSERT INTO `logs`(`user_id`, `fecha`, `action`, `ip_ingreso`, `device`, `platform`,`insert_update_by`) VALUES ('$admin_id','$nowdate','$actionLog','$myip','$ismobile','$agent','$admin_id')";
					if(mysqli_query($con, $sqlUpdateLog)){
						$out["log"]="LOGGED";
					}
			$out["check"]="success";
			$out["msg"]="Actualizado";	
		} else {
				$out["sql"] = $sqlUpdateUser;
				$out["check"]="error";
				$out["msg"]="Error code 1122";
		}
	}else{
		 $out["check"]="error";
         $out["msg"]="No existe config params!";
	} #-- ENDIF NUMROWS > 0
}else{
	$out["check"]="error";
	$out["msg"]="Error code 020";
} #-- ENDIF SITEKEY


header('Content-type: application/json');
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