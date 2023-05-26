<?php
include("connection.php");

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    $message = htmlentities("Solo se admite el método POST");
   # response($message, 500);
}

$nombre = $_POST['nombre'];
$password = $_POST['myppkey'];
$fuente = $_POST['institucion'];
$telefono = $_POST['telefono'];
$provincia = $_POST['provincia'];
$email = $_POST['email'];
$cedula  = $_POST['myapitoken'];
$nivel  = $_POST['level'];

$id = $_POST['admin_id'];

//
$myip = getIPAddress();
$agent =  $_SERVER['HTTP_USER_AGENT'];
$ismobile = 'desktop';
if(isMobile()){
	$ismobile = 'mobile';
}
//

//Verificar si el usuario existe
$sqlCheckUser = "select * from `admin_users` where md5(admin_id) = '$id'";
$resCheckUser = mysqli_query($con, $sqlCheckUser);

if(!$resCheckUser) {
   //Mensaje de error
   exit("ERROR");
}   

$numRows = $resCheckUser->num_rows;

if ($numRows == 0) {
    exit("EXISTS");
}

if(!$password){
    $sqlUpdateUser = "UPDATE `admin_users` SET  name='$nombre', institucion='$fuente', telefono='$telefono', email='$email', level=$nivel,cedula='$cedula' WHERE md5(admin_id)='$id';";
	$actionLog = "Update_user";
} else {
	/*
    $secret = "V4Ku+Sh0&03@wW0";
    $hash = base64_encode(hash('sha512', $password . $secret, true));
	$hash2 = hash('sha512', $password);
	*/
	
    $sqlUpdateUser = "UPDATE `admin_users` SET password='$password', name='$nombre', institucion='$fuente', telefono='$telefono', email='$email', level=$nivel,cedula='$cedula' WHERE md5(admin_id)='$id';";
	$actionLog = "Update_user_password";
}

$nowdate = date('Y-m-d H:i:s');
if (mysqli_query($con, $sqlUpdateUser)) {
	$elid = $id;
	$InsertUpdateId = $_SESSION['userid'];
    $sqlUpdateLog = "INSERT INTO `logs`(`user_id`, `fecha`, `action`, `ip_ingreso`, `device`, `platform`,`insert_update_by`) VALUES ('$cedula','$nowdate','$actionLog','$myip','$ismobile','$agent','$InsertUpdateId')";
			if(mysqli_query($con, $sqlUpdateLog)){
				$out[1]="LOGGED";
			}
	exit("UPDATED");	
} else {
    exit("ERROR"); //.$sqlUpdateUser);
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