<?php

# ---- CrearReporteService---- 2022/10/10
# --- 
# ------------- JBENAVIDES (PISOFAREJULU) ---------

date_default_timezone_set('America/Panama');
include("connection.php");

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    $message = htmlentities("Solo se admite el método POST");
}


$out = 'invalid';


if(isset($_POST['keynote']) && isset($_POST['delta'])){
	
	if($_POST['keynote'] == 'sjdksjiUIdhywwdmnwhjUEHiE083890kws2989sdj8282'){
			
			$out = 'invalid';
			
			$out = CheckDatas($con,$_POST['delta']);
			
			mysqli_close($con);
			
			//************************
			
		}else{
			$out = "invalid";
		}
	}else{
		$out = "invalid";
	}


//header('Content-type: application/json');
//header('content-type:text/html;charset=utf-8');
echo json_encode($out,JSON_PRETTY_PRINT);

function CheckDatas($mysql,$dt){
	
	$nowdate = date('Y-m-d H:i:s');
	
	
	$sqlGetReports = "SELECT t1.id, t1.incidente_desc, t1.provincia, t1.created_at as creado, t2.created_at as modificado, t2.estatus, t2.estatus_desc FROM reportes as t1 LEFT JOIN reportes_historial as t2 on t1.id = t2.reporte_id WHERE t2.created_at IN ( SELECT MAX(t3.created_at) FROM reportes_historial as t3 GROUP BY t3.reporte_id ) OR t2.created_at IS NULL ORDER BY t1.created_at DESC;";
	$tmp = [];
	$fila = [];
	$fila['data'] = [];
	$cc = 0;
	if($salida = $mysql -> query($sqlGetReports)){
		
		while($row = mysqli_fetch_assoc($salida))
            {
                $tmp[$cc] = $row;
				$cc = $cc + 1;
            }
			
			// print_r($tmp);
			
			$ii = 0;
			
			while($ii < count($tmp))
            {
				
					//print_r($tmp[$ii]['id']);
					$fila['data'][$ii][0] = $tmp[$ii]['id'];
					$fila['data'][$ii][1] = $tmp[$ii]['incidente_desc'];
					$fila['data'][$ii][2] = $tmp[$ii]['provincia'];
					$fila['data'][$ii][3] = $tmp[$ii]['creado'];
					$fila['data'][$ii][4] = $tmp[$ii]['modificado'];
					if($tmp[$ii]['modificado'] == ""){
						$fila['data'][$ii][4] = "";
					}
					
					$estatus = array(0=>"No Leido",5=>"Verificando",10=>"Atendido",99=>"Eliminado/Cancelado");
					$myestatus = $tmp[$ii]['estatus'];
					
					if($myestatus == ""){
						$fila['data'][$ii][5] = "No leído";
						$myestatus = 0;
					}
					$fila['data'][$ii][5] = '<img style="height: 24px;width:24px" src="img/estatus_'.$myestatus.'.png"/> '.$estatus[$myestatus];
					
					$accion = '<a href="verReporte.php?id='.$tmp[$ii]['id'].'" class="btn btn-primary btn-sm" title="Ver"><i class="fas fa-eye"></i></a>';
					$fila['data'][$ii][6] = $accion;
				
				$ii++;
				
            }
			
			
			
			if($cc == 0){
				$fila = 'invalid';
			}
			
			
			
	
 	}else{
		$fila = 'invalid';
	}
	
	
    return $fila; 
			
}
//
function addZero($aa){
	$a = (int)$aa;
	$num_padded = sprintf("%02d", $a);
	return $num_padded; // 
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

function sendMail($mail,$d,$out){

$lemail  = 'mailerr';

try {
    //Server settings
    //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp-pulse.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'test@test.com';                     //SMTP username
    $mail->Password   = 'TBjXrRCBXttA';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('test@test.com', 'SINAPROC EMERGENCIAS');
    $mail->addAddress('juantopx@gmail.com', 'User Pruebas');     //Add a recipient
   /*
   $mail->addAddress('ellen@example.com');               //Name is optional
    $mail->addReplyTo('info@example.com', 'Information');
    $mail->addCC('cc@example.com');
    $mail->addBCC('bcc@example.com');

    //Attachments
    $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
    $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
*/
    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Mensaje enviado desde SINAPROC EMERGENCIAS';
    $mail->Body    = 'Hola '.$d["nombre"].', su reporte "'.$d["incidentetxt"].'" ha sido registrado con el id: <b>'.$out["insertId"].'</b>';
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
