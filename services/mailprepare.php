<?php

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

function sendaMailo($mail,$mm,$dd,$nombre){
	//
	$elmail = $mm;
	$elmail = str_replace("gmai.com", "gmail.com",$elmail);
	$elmail = str_replace("hotmai.com", "hotmail.com",$elmail);
	
	/*
	Aqui $mail es $mailMethod y $ee es el correo a que se enviara
	*/
	global $headerMail;
	$contenido = '<CENTER><b>ENLACE DE RECUPERACION DE CONTRASEÑA</b>';
	$contenido .= '<br/><b>http://test.com?'.$dd.'</b>';
	$contenido .= '<br/><b>Este enlace solo dura 5 minutos</b></CENTER>';
	
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
		$mail->addAddress($mm,utf8_decode($nombre));     // Add a recipient
		
		
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

?>