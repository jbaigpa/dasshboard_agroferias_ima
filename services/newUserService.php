<?php


include("connection.php");

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    $message = htmlentities("Solo se admite el método POST");
    # response($message, 500);
}

$nombre = $_POST['nombre'];
$password = $_POST['pleskapitoken'];
$fuente = $_POST['fuente'];
$telefono = $_POST['telefono'];
//$provincia = $_POST['provincia'];
$email = $_POST['email'];
$cedulaResponsable  = $_POST['cedulaResponsable'];
$nivel  = $_POST['nivel'];

$myip = getIPAddress();
$agent =  $_SERVER['HTTP_USER_AGENT'];
$ismobile = 'desktop';
if(isMobile()){
	$ismobile = 'mobile';
}

//Verificar si el usuario existe
$sqlCheckUser = "select admin_id from admin_users where cedula='$cedulaResponsable' limit 1;";
$resCheckUser = mysqli_query($con, $sqlCheckUser);

$rowCheckUser = $resCheckUser->fetch_assoc();

$numRows = $resCheckUser->num_rows;

if ($numRows > 0) {
    exit("EXISTS");
}

/*
$hash = base64_encode(hash('sha512', $password . $secret, true));
$pass2 = hash('sha512', $password);
*/

$editorid = $_SESSION['userid'];
//Insertar el usuario
$insertdate = date('Y-m-d H:i:s');
$sqlInsertUser = "INSERT INTO admin_users (name, password, institucion, level, telefono, email, active, cedula, created_at, updated_at) VALUES('$nombre', '$password','$fuente', $nivel, '$telefono', '$email', 1,'$cedulaResponsable','$insertdate','$insertdate');";

	if (mysqli_query($con, $sqlInsertUser)) {
		$nowdate = date('Y-m-d H:i:s');
		$elid = md5(mysqli_insert_id($con));
        $sqlUpdateLog = "INSERT INTO `logs`(`user`,`type`, `descripcion`, `created_at`, `ip_ingreso`, `device`, `platform`) VALUES ('$email','create_user_log','create_user_success','$nowdate','$myip','$ismobile','$agent')";
			if(mysqli_query($con, $sqlUpdateLog)){
				$out[1]="LOGGED";
			}
		exit("INSERTED");
    }else{
		
		
		$sqlUpdateLog2 = "INSERT INTO `logs`(`user`,`type`, `descripcion`, `created_at`, `ip_ingreso`, `device`, `platform`) VALUES ('$email','create_user_log','create_user_failed','$nowdate','$myip','$ismobile','$agent')";
			if(mysqli_query($con, $sqlUpdateLog2)){
				$out[1]="LOGGED";
			}
		exit('ERROR :: '.$sqlInsertUser);
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