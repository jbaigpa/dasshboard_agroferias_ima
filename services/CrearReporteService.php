<?php

# ---- CrearReporteService---- 2022/10/10
# --- 
# ------------- JBENAVIDES (PISOFAREJULU) ---------

date_default_timezone_set('America/Panama');
include("connection.php");

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    $message = htmlentities("Solo se admite el método POST");
}


$out['status'] = 0;


if(isset($_POST['datos']) && isset($_POST['token'])){
	
	if($_POST['token'] == 'oiweie2i0420ilekdwJJJKDKNWK99094904930402sdklskld1200OOIOOoooS45djjfu3i2di9293kdsjdk'){
			
			$out["login"] = 0;
			
			$out = CheckDatas($con,$_POST['datos']);
			
			mysqli_close($con);
			
			//************************
			
		}else{
			$out["ERROR"] = "NO POST";
		}
	}else{
		$out["ERROR"] = "CODEPASS";
	}


header('Content-type: application/json');
echo json_encode($out);

function CheckDatas($mysql,$d){
	
	$nowdate = date('Y-m-d H:i:s');
	//
	$email = $d['email'];
	$nombre = $d['nombre'];
	$cedula = $d['cedula'];
	$provincia = $d['provincia'];
	$distrito = $d['distrito'];
	$corregimiento = $d['corregimiento'];
	$sector = $d['sector'];
	$direccion = $d['direccion'];
	$referencia = $d['referencia'];
	$geolat = $d['geolat'];
	$geolon = $d['geolon'];
	$telefono = $d['telefono'];	
	$otrotelefono = $d['telefonos'];	
	$incidente_id = $d['incidente_id'];
    $incidente_desc = $d['incidentetxt'];	
	$descripcion = $d['descripcion'];	
	$fecha = $d['fecha'];
	$control = $d['control'];
	$estatus = $d['newstatus'];
	$estatus_desc = $d['newstatusdesc'];
	$userid = $d['userid'];
	
	$myip = getIPAddress();
	$agent =  $_SERVER['HTTP_USER_AGENT'];
	$ismobile = 'desktop';
	if(isMobile()){
		$ismobile = 'mobile';
	}
	
	$SQL = "SELECT t1.control FROM reportes as t1 where t1.control = '".$control."'";
	$cc = 0;
	
	$fila = array();
	$fila["insertId"] = 0;
	$fila["reportesdb"] = "reportes";
	if($salida = $mysql -> query($SQL)){
		
		while($row = mysqli_fetch_assoc($salida))
            {
                $fila = $row;
				$cc = $cc + 1;
            }
			
			if($cc == 0){
		
				$nowdate = date('Y-m-d H:i:s');
				$sqlInsertReport = "INSERT INTO `reportes`(`control`, `incidente_id`, `cedula`, `nombre`, `incidente_desc`, `descripcion`, `created_at`, `modify_at`, `geolat`, `geolon`, `provincia`, `distrito`, `corregimiento`, `sector`, `direccion`, `create_at_device`, `telefono`, `otros_telefonos`, `referencia`, `tipo_entrada`) VALUES ('$control', '$incidente_id', '$cedula', '$nombre', '$incidente_desc', '$descripcion', '$nowdate', '$nowdate', '$geolat', '$geolon', '$provincia', '$distrito', '$corregimiento', '$sector', '$direccion', '$fecha', '$telefono', '$otrotelefono', '$referencia', 'backend')";
				
				if(mysqli_query($mysql, $sqlInsertReport)){
					$fila["register"] = "12345";
					$register_log = "register_report";
					$fila["check"] = "success";
					$actionLog = "report_create";
					$fila["insertId"] = $mysql -> insert_id;
					$reportid = $fila["insertId"];
					
					//
					$insertdate = date('Y-m-d H:i:s');
					$sqlInsertUser = "INSERT INTO `reportes_historial`(`reporte_id`, `estatus`, `estatus_desc`, `created_by`, `created_at`) VALUES('$reportid', '$estatus','$estatus_desc','$userid','$insertdate');";

						if (mysqli_query($mysql, $sqlInsertUser)) {
							$nowdate = date('Y-m-d H:i:s');
							
							$fila["register_estatus"] = "12345";
						}else{
							$fila["register_estatus"] = "11111";
							$fila["register_sql"] = $sqlInsertUser;
						}
					//
				}

				if($fila["insertId"] == 0){
					$fila["register"] = "1111";
					$register_log = "register_report";
					$fila["check"] = "error";
					$file["msg"] = "Error en el proceso, consulte al administrador. 1111";
					$actionLog = "report_create_error_sql";
					$fila["insertId"] = 0;
					$fila["sql"] = $sqlInsertReport;
				}
			}else{
					$fila["register"] = "2222";
					$register_log = "register_report";
					$fila["check"] = "error";
					$file["msg"] = "Ya existe el registro";
					
			}

	
 	}else{
		$fila["register"] = "99999";
		$fila["check"] = "error";
		$fila["msg"] = 'No esta autorizado. Contactar Administrador. 9999';
		//$fila["sql"] = $SQL;
		$fila["insertId"] = 0;
	}
	

	//


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
    $mail->Username   = 'alertas@imsapanama.com';                     //SMTP username
    $mail->Password   = 'TBjXrRCBXttA';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('alertas@imsapanama.com', 'SINAPROC EMERGENCIAS');
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
