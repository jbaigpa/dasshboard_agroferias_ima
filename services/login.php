<?php
session_start();

//include("verifyReCapcha.php");
//include("verifyHCapcha.php");

include("connection.php");

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    $message = htmlentities("Solo se admite el método POST");
    #response($message, 500);
}

$user = $_POST['user'];
$password = $_POST['apikeypublic'];
# -- $apikey = $_POST['apikey512'];

/*
if(DEFAULT_USER!=$user || DEFAULT_PASSWORD!=$password){
    exit('CREDENTIALS_ERROR');
}
*/



$myip = getIPAddress();
$agent =  $_SERVER['HTTP_USER_AGENT'];
$ismobile = 'desktop';
if(isMobile()){
	$ismobile = 'mobile';
}

#$hash = base64_encode(hash('sha512', $password . $secret, true));
#$hash = base64_encode($password);
//Verificar si el usuario existe
$sqlCheckUser = "select admin_id, level from admin_users where email='$user' and password='$password' limit 1;";
$resCheckUser = mysqli_query($con, $sqlCheckUser);

$rowCheckUser = $resCheckUser->fetch_assoc();

$numRows = $resCheckUser->num_rows;

$nivel = 0;

$userlogin = 0; 

if ($numRows == 1) {	
	$nivel = $rowCheckUser["level"];
	if($nivel > 50){
		$userlogin = 1;
		$nowdate = date('Y-m-d H:i:s');
		$elid = md5($rowCheckUser["admin_id"]);
        $sqlUpdateLog = "INSERT INTO `logs`(`user`,`type`, `descripcion`, `created_at`, `ip_ingreso`, `device`, `platform`) VALUES ('$user','admin_log','access_success','$nowdate','$myip','$ismobile','$agent')";
			if(mysqli_query($con, $sqlUpdateLog)){
				$out[1]="LOGGED";
			}
	}else{
		$nowdate = date('Y-m-d H:i:s');
		$elid = md5($rowCheckUser["admin_id"]);
        $sqlUpdateLog = "INSERT INTO `logs`(`user`,`type`, `descripcion`, `created_at`, `ip_ingreso`, `device`, `platform`) VALUES ('$user','admin_log','access_error_level','$nowdate','$myip','$ismobile','$agent')";
			if(mysqli_query($con, $sqlUpdateLog)){
				$out[1]="LOGGED";
			}
		exit("SOLOSUPER");
	}
}else{
	$nowdate = date('Y-m-d H:i:s');
	$elid = $user;
    $sqlUpdateLog = "INSERT INTO `logs`(`user`,`type`, `descripcion`, `created_at`, `ip_ingreso`, `device`, `platform`) VALUES ('$user','admin_log','access_error_password','$nowdate','$myip','$ismobile','$agent')";
	if(mysqli_query($con, $sqlUpdateLog)){
		$out[1]="LOGGED";
	}
	exit("CREDENTIALS_ERROR");
}

if($userlogin == 1){
//Crear la sessión
	$_SESSION['isLogged'] = true;
	$_SESSION['userid'] = $rowCheckUser["admin_id"];
	$_SESSION['level'] = $rowCheckUser["level"];
}
exit("OK");

function response($message, $code)
{
    header("HTTP/1.0 $code $message");
    print_r($message);
    exit;
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