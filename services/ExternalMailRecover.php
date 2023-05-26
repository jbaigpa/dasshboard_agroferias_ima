<?php

// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function

require "/home/automatizacion/phpmailer/PHPMailer.php"; 
require "/home/automatizacion/phpmailer/SMTP.php";
require "/home/automatizacion/phpmailer/Exception.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require '/home/automatizacion/vendor/autoload.php';


$headerMail = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>

<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <meta name="x-apple-disable-message-reformatting">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="telephone=no" name="format-detection">
    <title></title>
    <!--[if (mso 16)]>
    <style type="text/css">
    a {text-decoration: none;}
    </style>
    <![endif]-->
    <!--[if gte mso 9]><style>sup { font-size: 100% !important; }</style><![endif]-->
    <!--[if !mso]><!-- -->
    <link href="https://fonts.googleapis.com/css?family=Lato:400,400i,700,700i" rel="stylesheet">
    <!--<![endif]-->
</head>';

$mailMethod = new PHPMailer(true);

set_time_limit(0);
date_default_timezone_set('America/Bogota');

/**** DB *****/
$databasehost = '10.234.80.140';
$databasename = 'aig_vacunacion';
$databaseusername = 'vacunacion';
$databasepassword = '.Panama2020.';
$tbl = "usuarios_panavac";

$con = mysqli_connect($databasehost, $databaseusername, $databasepassword, $databasename) or die(mysqli_connect_error());

mysqli_set_charset($con, "utf8");

if ($con->connect_errno) {
    echo "Failed to connect to MySQL: " . $con->connect_error;
    exit();
}

/*****************/

$res["send"] = 0;
$res = sendaMailo($mailMethod,$_POST['email'],$_POST['id'],$_POST['nombre'],$_POST['link']);
if($res["send"] == 1){
	$res["insert"] = insertaloLink($con, $_POST['link'],$_POST['id']);
}

$result = $res;

header('Content-type:application/json;charset=utf-8');
echo json_encode($result);

function sendaMailo($mail,$mm,$dd,$nombre,$link){
	//
	$elmail = $mm;
	$elmail = str_replace("gmai.com", "gmail.com",$elmail);
	$elmail = str_replace("hotmai.com", "hotmail.com",$elmail);
	
	/*
	Aqui $mail es $mailMethod y $ee es el correo a que se enviara
	*/
	global $headerMail;
	$contenido = '<CENTER><b>Enlace de recuperacion de contrase&ntilde;a:</b>';
	$contenido .= '<br/><br/><b><a href="https://vacunas.panamasolidario.gob.pa/GestionPanaVac19/sso_panavac/RecoverLink.php?token='.$dd.'&apikey='.$link.'">HAGA CLICK AQUI</a></b>';
	$contenido .= '<br/><br/><b>Este enlace solo dura 5 minutos</b></CENTER>';
	
	$laMail = $headerMail.'
	<body style="background-color:#dddddd;padding:20px">
	<table style="width:480px; margin-left: auto; margin-right: auto; background: #ffffff; margin-bottom: 5px;" border="0" cellspacing="0" cellpadding="0"  align="center">
<tbody>

<tr>
<td style="background-color: #ffffff; text-align: center; vertical-align: top;"><img style="display: block; margin-left: auto; margin-right: auto;margin-top:5px;" src="https://vacunas.panamasolidario.gob.pa/vacunas_menu/aig_footer.jpeg" alt="GOBIERNO DE LA REPUBLICA DE PANAMA" width="auto" height="70" /></td>
</tr>
<tr>
<td style="height: auto;font-size: 14px;padding: 15px 25px;line-height: 1.5;">'.utf8_decode($contenido).'</td>
</tr>
<tr>
<td style="text-align:center;background-color: #0f4c81; height: 60px;font-size: 11px;color:#fff">Correo Seguro - Enviado desde la AIG</td>
</tr>
</tbody>
</table></body></html>
	';
	//

	try {
		//Server settings
		//$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
		$mail->isSMTP();                                            // Send using SMTP
		$mail->Host       = '10.252.70.151';                    // Set the SMTP server to send through
		$mail->SMTPAuth   = true;                                   // Enable SMTP authentication
		$mail->Username   = 'Admin';                     // SMTP username
		$mail->Password   = 'Aig2019';                               // SMTP password
		#$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         // Enable TLS encryption; PHPMailer::ENCRYPTION_SMTPS also accepted
		$mail->SMTPSecure = false;
		$mail->SMTPAutoTLS = false;
		$mail->Port       = 25;                                    // TCP port to connect to

		//Recipients
		$mail->setFrom('vacunas-noreply@panamasolidario.gob.pa', 'Vacunas Covid MINSA');
		$mail->addAddress($elmail,utf8_decode($nombre));     // Add a recipient
		
		
		// Content
		$mail->isHTML(true);// Set email format to HTML
		$mail->CharSet = 'UTF-8';
		$mail->Subject = '✐ Recuperacion Usuarios Panavac';
		$mail->Body    = utf8_encode($laMail);
		$mail->AltBody = $contenido;

		$mail->send();
		$res["send"] = 1;
		$res["mailerror"]="";
		
	} catch (Exception $e) {
		$res["send"] = 0;
		$res["mailerror"] = "El mensaje no se pudo enviar. Mailer Error: {$mail->ErrorInfo}";
	}
	return $res;
}// End sendaMailo


function insertaloLink($con, $link,$userid){
	
	$sqlInsertUser = "INSERT INTO `usuarios_panavac_restore`(`user_id`, `link`, `created_at`) VALUES ('$userid','$link',NOW());";

	if (mysqli_query($con, $sqlInsertUser)) {
        return("INSERTED");
    }else{
		#echo $sqlInsertUser;
		return('ERROR');
	}
	
}

?>