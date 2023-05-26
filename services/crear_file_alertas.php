<?php
	if(isset($_POST['insertid'])){
	$insertid = $_POST['insertid'];
	// Obtener el contenido del archivo del cuerpo de la solicitud POST
	$post = genHtml($_POST['alertatitulo'],$_POST['alertatxt'],$_POST['color'],$_POST['fecha'],$_POST['region']);
	
	$html = '<!DOCTYPE html>
	<html lang="es">

	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<title>IMA AGROFERIAS - Alerta</title>
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

		<link rel="icon" type="image/png" href="img/favicon.png">

		<style>
			body,html{
				margin:0px;
				font-family: Helvetica, Arial, Arimo, Sans, Ubuntu, OpenSans, Serif;
				background-color:#000;
			}	
			
			.mainpage {
			  width: 100%;
			  max-width: 700px;
			  margin: 20px auto;
			  border: 2px solid #eee;
			  padding: 0;
			  border-radius: 10px;
			  overflow: hidden;
			  background-color: #fff;
			}
			
			.zone {
			  padding: 20px 12px;
			}
			
			.divhead-verde{
				background-color:#31BA14;
			}
			
			.divhead-amarilla{
				background-color:#FECE00;
			}
			
			.divhead-roja{
				background-color:#ff0000;
			}
			
		</style>

	</head>

	<body>
		<div class="mainpage">
			'.$post.'
		</div>
	</body>
	</html>
	';

	// Crear el archivo con el contenido especificado
	$archivo = fopen("/mnt/snpc/public_html/alertas/alerta_".$insertid.".html", "w") or die("No se pudo crear el archivo");
	fwrite($archivo, $html);
	fclose($archivo);

	// Enviar una respuesta al cliente indicando que el archivo se creó correctamente
	http_response_code(200);
	echo "El archivo se creó correctamente";

	}else{
		die("Error Allowed");
	}

function genHtml($titulo,$alertatxt,$color,$fecha,$region){

	$elbody = '<div style="text-align:center" class="divhead divhead-'.$color.'"><img src="https://agroferias-ws.aig.gob.pa/agroferias_admin/img/sbanner_'.$color.'.jpg" /></div>';
	$elbody .= '<div class="zone"><h2>Se ha reportado una nueva ALERTA '.strtoupper($color).':<h2>';
	$elbody .= '<h2>'.$titulo.'</h2>';
	$elbody .= '<p><strong>Descripcion:</strong> '.strip_tags($alertatxt).'</p>';
	$elbody .= '<p><strong>Fecha y Hora:</strong> '.$fecha.'</p>';
	$elbody .= '<p><strong>Regi&oacute;n(es):</strong> '.$region.'</p></div>';

	return $elbody;
}

?>