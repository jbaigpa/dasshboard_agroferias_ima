<?php

# ---- send mail report ---- 2022/09/06
# --- 
# ------------- JBENAVIDES (PISOFAREJULU) ---------

header('Access-Control-Allow-Origin: *');
#error_reporting(0);
date_default_timezone_set('America/Panama');
//
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '/mnt/snpc/inc/vendor/phpmailer/phpmailer/src/Exception.php';
require '/mnt/snpc/inc/vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '/mnt/snpc/inc/vendor/phpmailer/phpmailer/src/SMTP.php';

//Load Composer's autoloader
require '/mnt/snpc/inc/vendor/autoload.php';

$mail = new PHPMailer(true);

if(isset($_POST['alertatitulo'], $_POST['alertatxt'], $_POST['color'], $_POST['fecha'], $_POST['region'])){

echo sendMail($mail,$_POST['alertatitulo'],$_POST['alertatxt'],$_POST['color'],$_POST['fecha'],$_POST['region'],$_POST['insertid']);

}else{
	die("not allowed");
}


function sendMail($mail,$titulo,$alertatxt,$color,$fecha,$region,$insertid){

$lemail  = 'mailerr';
$elbody = '<center><img src="https://agroferias-ws.aig.gob.pa/agroferias_admin/img/sbanner_'.$color.'.jpg" /></center>';
$elbody .= '<h2>Se ha reportado una nueva ALERTA '.strtoupper($color).':<h2>';
$elbody .= '<h2>'.htmlentities($titulo).'</h2>';
$elbody .= '<p><strong>Descripci&oacute;n:</strong> '.htmlentities(strip_tags($alertatxt)).'</p>';
$elbody .= '<p><strong>Fecha y Hora:</strong> '.$fecha.'</p>';
$elbody .= '<p><strong>SMS:</strong>'.htmlentities($titulo).'</p>';
$elbody .= '<p><strong>Url:</strong>https://agroferias-ws.aig.gob.pa/alertas/alerta_'.$insertid.'.html</p>';
$elbody .= '<p><strong>Regi&oacute;n(es):</strong></p>'.$region;

try {
    //Server settings
    //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = '10.252.70.151';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'Admin';                     			//SMTP username
    $mail->Password   = 'Aig2019';                             //SMTP password
    $mail->Port       = 25;  
	$mail->SMTPAutoTLS = false;
	$mail->SMTPSecure = false;
	//TCP port to connect to; use 587 if you have 
																
	# -- $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         
	#-- Enable implicit TLS encryption set - SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS --

    //Recipients
    $mail->setFrom('no-reply@ima.gob.pa', 'IMA AGROFERIAS EMERGENCIAS');
    $mail->addAddress('juantopx@gmail.com', 'User Pruebas');     //Add a recipient
  
   $mail->addCC('eiglesias@aig.gob.pa');               //Name is optional
	$mail->addCC('jbenavides@aig.gob.pa');
	$mail->addCC('juan.vega@cwpanama.com');
	$mail->addCC('karina.guerra@tigo.com.pa'); 
	$mail->addCC('fernando.camargo@digicelgroup.com');
	$mail->addCC('lcarrasco-consultor@aig.gob.pa');
	
	/*   
   $mail->addReplyTo('info@example.com', 'Information');
    $mail->addCC('cc@example.com');
    $mail->addBCC('bcc@example.com');

    //Attachments
    $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
    $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
*/
    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Alerta desde IMA AGROFERIAS';
    $mail->Body    = $elbody;
    $mail->AltBody = '';

    $mail->send();
		$lemail  = 'mailok';
		return $lemail;
	} catch (Exception $e) {
		$lemail = 'mailerr: '.$mail->ErrorInfo;
		//echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
		return $lemail;
	}
	
	
}

?>