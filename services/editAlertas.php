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


$titulo = $_POST['titulo'];
$descripcion = $_POST['descripcion'];
$urlopt = $_POST['urlopt'];
$admin_id = $_POST['admin_id'];
$regions = $_POST['regions'];
$siteKey = $_POST['siteKey'];
$imagen1  = $_POST['imagen1'];
$alerta_id  = $_POST['alerta_id'];
$duracion  = $_POST['duracion'];
$active  = $_POST['active'];
$color  = $_POST['color'];

if($siteKey == '6LeiVcYaAAFgsghshGGshuesueyYDYEKmSBbagakckdlslsJdjshjhdassad0odoOOodjdjjswheD1cpDx2jP8P00oSGokBgnG9hKDob' ){
	//
	$myip = getIPAddress();
	$agent =  $_SERVER['HTTP_USER_AGENT'];
	$ismobile = 'desktop';
	if(isMobile()){
		$ismobile = 'mobile';
	}
	//
	if($alerta_id > 0){
		//Verificar si el config existe
		$sqlCheckUser = "select * from `mensajes_alertas` where alerta_id = '$alerta_id'";
		$resCheckUser = mysqli_query($con, $sqlCheckUser);

		if(!$resCheckUser) {
		   //Mensaje de error
		   exit("ERROR");
		}   

		$numRows = $resCheckUser->num_rows;

		if ($numRows > 0) {
		   
			$nowdate = date('Y-m-d H:i:s');
			// $sqlUpdateUser = "UPDATE `mensajes_alertas` SET  `activo`='".$active."',`alerta_title`='".$titulo."',`alerta_desc`='".$descripcion."',`imagen`='".$imagen1."',`urlopt`='".$urlopt."',`duracion`='".$duracion."',`region`='".$regions."', `modify_at`='".$nowdate."', `modify_by`='".$admin_id."' WHERE `alerta_id` ='".$alerta_id."';";
			
			$sqlUpdateUser = "UPDATE `mensajes_alertas` SET  `activo`='".$active."',`alerta_title`='".$titulo."',`alerta_desc`='".$descripcion."',`imagen`='".$imagen1."',`urlopt`='".$urlopt."',`duracion`='".$duracion."',`region`='".$regions."', `modify_at`='".$nowdate."', `color`='".$color."' WHERE `alerta_id` ='".$alerta_id."';";
			
		$actionLog = "update_alerta";
			
			if (mysqli_query($con, $sqlUpdateUser)) {
				
				/*
				$sqlUpdateLog = "INSERT INTO `logs`(`user_id`, `fecha`, `action`, `ip_ingreso`, `device`, `platform`,`insert_update_by`) VALUES ('$admin_id','$nowdate','$actionLog','$myip','$ismobile','$agent','$admin_id')";
						if(mysqli_query($con, $sqlUpdateLog)){
							$out["log"]="LOGGED";
						}
				*/
				$out["check"]="success";
				$out["msg"]="Actualizado";	
				$out["createddate"] = $nowdate;
			} else {
					$out["sql"] = $sqlUpdateUser;
					$out["check"]="error";
					$out["msg"]="Error code 1433";
			}
		}else{
			 $out["check"]="error";
			 $out["msg"]="No existe config params!";
		} #-- ENDIF NUMROWS > 0
	}else{ #-- Entrada Nueva
			$nowdate = date('Y-m-d H:i:s');
			$sqlInsertAviso = "INSERT INTO `mensajes_alertas`(`alerta_title`, `alerta_desc`, `imagen`, `urlopt`, `duracion`, `region`, `enviado_push`, `enviado_sms`, `enviado_email`, `enviado_radiocell`, `created_by`,`created_at`, `modify_at`, `color`) VALUES ('".$titulo."','".$descripcion."','".$imagen1."','".$urlopt."','".$duracion."','".$regions."',0,0,0,0,'".$admin_id."','".$nowdate."','".$nowdate."','".$color."')";
			$actionLog = "insert_alerta";


			if (mysqli_query($con, $sqlInsertAviso)) {
				$elid = $id;
				$out["check"]="success";
				$out["msg"]="Insertado";	
				$out["createddate"] = $nowdate;
				$out["insertid"] = mysqli_insert_id($con);
			} else {
					$out["sql"] = $sqlInsertAviso;
					$out["check"]="error";
					$out["msg"]="Error code 1122";
			}
	}
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

