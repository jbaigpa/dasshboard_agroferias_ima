<?php
date_default_timezone_set('America/Panama');

include('connection.php');
$activeperiod[12] = "";
$activeperiod[24] = "";
$activeperiod[72] = "";
$activeperiod[168] = "";
$activeperiod[360] = "";
$activeperiod[720] = "";
$activeperiod[1440] = "";
$period = $_GET['period'];
#-- 72 (3d) -- 168(7d) -- 360(15d) - 720 (30d) - 1440 (60d)
if($period == '12' || $period == '24' || $period == '72' || $period == '168' || $period == '360' || $period == '720' || $period == '1440'){
	$period = intval($period);
	$sqlGetReports = "SELECT t1.id, t1.control, t1.cedula, t1.producto, t1.fecha_compra, t1.lugar_compra, t1.provincia, t1.distrito, t1.corregimiento, t1.vendedor, t1.geolon, t1.geolat, t1.supervisor, t1.device, t1.offline FROM `ventas` as t1  WHERE t1.fecha_registro > DATE_SUB(NOW(), INTERVAL ".$period." HOUR) GROUP BY t1.id ORDER BY t1.fecha_compra DESC;";
	
	# echo $period;
	$activeperiod[$period] = "mybuttonactive";
}else{
	$sqlGetReports = "SELECT t1.id, t1.control, t1.cedula, t1.producto, t1.fecha_compra, t1.lugar_compra, t1.provincia, t1.distrito, t1.corregimiento, t1.vendedor, t1.geolon, t1.geolat, t1.supervisor, t1.device, t1.offline FROM `ventas` as t1  WHERE t1.fecha_registro > DATE_SUB(NOW(), INTERVAL 720 HOUR) GROUP BY t1.id ORDER BY t1.fecha_compra DESC;";
	$activeperiod[720] = "mybuttonactive";
}


$resGetReports = mysqli_query($con, $sqlGetReports);
$numOfReports = mysqli_num_rows($resGetReports);

$estatus = array(0=>"No Leido",5=>"Verificando",10=>"Atendido",99=>"Eliminado/Cancelado");
$mycolor = array(0=>"#AAAAAA",5=>"#E8D206",10=>"#00FF00",99=>"#FF0000");


		
	$geojson = array (
		'type'	=> 'FeatureCollection',
		'features'	=> array()
	);
	
	while ($rowGetReports = mysqli_fetch_assoc($resGetReports)) { 

	$myestatus = $rowGetReports['offine'];
	


		$properties = $rowGetReports;
		unset($properties['geolat']);
		unset($properties['geolon']);
		$properties['color'] = "#FF0000"; //$mycolor[$myestatus];
		$GeoLon = $rowGetReports['geolon'];
		$GeoLat = $rowGetReports['geolat'];
		if($GeoLon == 0 || $GeoLat == 0){
			$GeoLon = -79.145;
			$GeoLat = 7.387;
		}
		$feature = array(
			'type'	=> 'Feature',
			'geometry' => array(
				'type' => 'Point',
				'coordinates' => array(
						$GeoLon,
						$GeoLat
						)
				),
			'properties' => $properties
		);
		
		array_push($geojson['features'], $feature);
	} // END WHILE

	header('Content-type: application/json');
	echo json_encode($geojson, JSON_PRETTY_PRINT);
		
?>