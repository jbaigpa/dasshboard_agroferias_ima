<?php
set_time_limit(0);
date_default_timezone_set('America/Bogota');

include("verifyHCapcha.php");
include("connection.php");


if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    $message = htmlentities("Solo se admite el método POST");
    #response($message, 500);
}

$user = $_POST['user'];
$apikey = $_POST['apikeyxyz'];

//Verificar si el usuario existe
$sqlCheckUser = "select id, nivel,email,nombre,telefono from ".$tbl." where cedula_responsable='$user' limit 1;";
$resCheckUser = mysqli_query($con, $sqlCheckUser);
#echo $sqlCheckUser;
$rowCheckUser = $resCheckUser->fetch_assoc();

$numRows = $resCheckUser->num_rows;

$nivel = 0;

$userlogin = 0; 

if ($numRows == 1) {	
	$nivel = $rowCheckUser["nivel"];
	$email = $rowCheckUser["email"];
	$elId = md5($rowCheckUser["id"]);
	$nombre = $rowCheckUser["nombre"];
	$nombre = str_replace(' ', '%20', $nombre);
	$linkrandom = generateRandomString(20);
	$sendurl ="http://10.234.80.135/ExternalMailRecover_INADEH.php";
	//$sendurl = $sendurlA."email=".urlencode($email)."&id=".urlencode($elId)."&nombre=".urlencode($nombre)."&link=".$linkrandom;
	if($nivel == 1000){
		$sendres = mycurl($sendurl,$email,$elId,$nombre,$linkrandom);
		exit("OK|".$email."|SUPER|".$sendres);
	}else{
		$sendres = mycurl($sendurl,$email,$elId,$nombre,$linkrandom);
		exit("OK|".$email."|SOLOSUPER|".$sendres);
	}
}else{
	exit("CREDENTIALS_ERROR");
}



function response($message, $code)
{
    header("HTTP/1.0 $code $message");
    print_r($message);
    exit;
}

function mycurl($url,$email,$elId,$nombre,$link){
	// set post fields
$post = [
		'nombre' => $nombre,
		'id' => $elId,
		'link'   => $link,
		'email'   => $email
	];
	$api_url = $url;
	$port = 80;
	$ch = curl_init( );
	curl_setopt ( $ch, CURLOPT_URL, $api_url );
	curl_setopt ( $ch, CURLOPT_PORT, $port );
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	// Allowing cUrl funtions 20 second to execute
	curl_setopt ( $ch, CURLOPT_TIMEOUT, 5 );
	// Waiting 20 seconds while trying to connect
	curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, 5 );                                 
	$response_string = curl_exec( $ch );

	return $response_string;
}

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}