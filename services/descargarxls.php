<?php

use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

require_once '/home/automatizacion/phpExcel/vendor/autoload.php';

$helper = new Sample();
if ($helper->isCli()) {
    $helper->log('Solo desde Web Browser' . PHP_EOL);

    return;
}

include("connection.php");

if (isset($_POST['supercode'])) {
    if($_POST['supercode'] != "aeIouXYZ2022@Inadeh500R"){
		echo "NO LOGIN";
		exit;
	}else{
		// Create new Spreadsheet object
        $spreadsheet = new Spreadsheet();
		if (isset($_POST['cedula'])) {
			$type = 1;
			$post = $_POST['cedula'];
		}
		
		if (isset($_POST['fechaini']) && isset($_POST['fechaend'])) {
			$type = 2;
			$post = " `fecha_ingreso` BETWEEN '".$_POST['fechaini']."' AND '".$_POST['fechaend']."'";
		}
		
		if (isset($_POST['total'])) {
			$type = 100;
			$post = $_POST['total'] ;
		}
		
		ejecuta($spreadsheet,$con,$type,$post);
		exit;
	}
}else{
	$message = htmlentities("Solo se admite el método POST");
	echo $message;
	exit;
}

function ejecuta($spreadsheet,$con,$type,$post){
	if($type == 100){
	$sqlGetGob = "SELECT `id`, `cedula`, `fecha_ingreso`, `control`, `notificado`, `curso_cod`, `instructor_ced`, `geolat`, `geolon`, `typescan` FROM `track_asistencia`;";
	}
	
	if($type == 1){
		$sqlGetGob = "SELECT `id`, `cedula`, `fecha_ingreso`, `control`, `notificado`, `curso_cod`, `instructor_ced`, `geolat`, `geolon`, `typescan` FROM `track_asistencia` WHERE `cedula` = '".$post."' ;";
	}
	
	if($type == 2){
		// Debe ser el post Between
		$sqlGetGob = "SELECT `id`, `cedula`, `fecha_ingreso`, `control`, `notificado`, `curso_cod`, `instructor_ced`, `geolat`, `geolon`, `typescan` FROM `track_asistencia` WHERE ".$post.";";
		
	}
	
	if($resGetGob = mysqli_query($con, $sqlGetGob)){
		$cc = 0;
		while($rowGob = mysqli_fetch_assoc($resGetGob))
			{
				$res["data"][] = $rowGob;
				$cc = $cc + 1;
			}

		if ($cc > 0) {	
			$res["check"] = "OK";
			$res["msg"] = "200";
		}
	}else{
		$res["check"] = "ERROR";
		$res["msg"] = "100";
	}
	//

	if($res["check"] == "ERROR"){
		exit("100");
	}



	// Set document properties
	$spreadsheet->getProperties()->setCreator('AIG - JB')
		->setLastModifiedBy('AIG - JB')
		->setTitle('Office XLS Document')
		->setSubject('Office XLSX DOC')
		->setDescription('Generado Automaticamente')
		->setKeywords('office 2007 2022 openxml php')
		->setCategory('archivo exportado');

	$cols = array(
		array("col"=>"A", "name"=>"Asistente", "db"=>"cedula", "with"=>20),
		array("col"=>"B", "name"=>"Fecha", "db"=>"fecha_ingreso", "with"=>20),
		array("col"=>"C", "name"=>"Curso Codigo", "db"=>"curso_cod", "with"=>20),
		array("col"=>"D", "name"=>"Instructor", "db"=>"instructor_ced", "with"=>20),
		array("col"=>"E", "name"=>"Latitud", "db"=>"geolat", "with"=>20),
		array("col"=>"F", "name"=>"Longitud", "db"=>"geolon", "with"=>20)
	);


	// Encabezados
	foreach($cols as $ct){
	$spreadsheet->setActiveSheetIndex(0)
		->setCellValue($ct['col'].'1', $ct['name']);
	}

	$celhead = 2;
	foreach($res["data"] as $rr){
		foreach($cols as $cb){
			$spreadsheet->setActiveSheetIndex(0)
				->setCellValue($cb['col'].$celhead, $rr[$cb["db"]]);
		}
		$celhead = $celhead+1;
	}

	//
	foreach($cols as $ce){
		$spreadsheet->getActiveSheet()->getColumnDimension($ce['col'])->setWidth($ce['with']);
	}

	$spreadsheet->getActiveSheet()->getStyle('A1:Z999')
		->getAlignment()->setWrapText(true); 

	$spreadsheet->getActiveSheet()->getStyle('A1:F1')->getFill()
		->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
		->getStartColor()->setARGB('EEEEEE00');

	$spreadsheet->getActiveSheet()->getStyle('A1:F1')->getFont()->setBold(true);

	$spreadsheet->getActiveSheet()->getRowDimension('1')
		->setRowHeight(25);

	$spreadsheet->getActiveSheet()->getStyle('A1:Z999')
		->getAlignment()
		->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

	// Rename worksheet
	$spreadsheet->getActiveSheet()->setTitle('Asistencia');

	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$spreadsheet->setActiveSheetIndex(0);
	$fecha = date('YmdHis');
	$filename = "inadeh_asistencia_".$fecha.".xls";
	// Redirect output to a client’s web browser (Xls)
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'.$filename.'"');
	header('Cache-Control: max-age=0');
	// If you're serving to IE 9, then the following may be needed
	header('Cache-Control: max-age=1');

	// If you're serving to IE over SSL, then the following may be needed
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
	header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	header('Pragma: public'); // HTTP/1.0

	$writer = IOFactory::createWriter($spreadsheet, 'Xls');
	$writer->save('php://output');
}
