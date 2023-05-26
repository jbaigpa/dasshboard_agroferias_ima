<?php
session_start();

include("connection.php");

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    $message = htmlentities("Solo se admite el método POST");
    #response($message, 500);
}

define("DEFAULT_USER", 'descarga');
# 'InadeH.@siste22'
define("DEFAULT_PASSWORD", 'xvpw7L0b8NK6xKuN3Wf/Godeu/hXNzAIquERxgStsXv17hIW2bKr2MUR/EwxYHnPfvfCKHPwfgFkyitNt+GRRg==');

$user = $_POST['user'];
$password = $_POST['apikeypublic'];


if(DEFAULT_USER!=$user || DEFAULT_PASSWORD!=$password){
    exit('CREDENTIALS_ERROR');
}else{
	//Crear la sessión
	$_SESSION['isLogged'] = true;
	$_SESSION['userid'] = 'descarga';
	$_SESSION['nivel'] = '1';
	exit("OK");
}
function response($message, $code)
{
    header("HTTP/1.0 $code $message");
    print_r($message);
    exit;
}

