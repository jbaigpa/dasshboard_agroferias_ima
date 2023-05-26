<?php
date_default_timezone_set('America/Panama');
include('services/security.php');
include('services/connection.php');
$activeperiod[12] = "";
$activeperiod[24] = "";
$activeperiod[72] = "";
$activeperiod[168] = "";
$activeperiod[360] = "";
$activeperiod[720] = "";
$activeperiod[1440] = "";
$period = $_GET['period'];
#-- 72 (3d) -- 168(7d) -- 360(15d) - 720 (30d)
if($period == '12' || $period == '24' || $period == '72' || $period == '168' || $period == '360' || $period == '720' || $period == '1440'){
	$period = intval($period);
	$sqlGetReports = "SELECT t4.* from (SELECT t1.*, t2.id as repoid, t2.estatus as est1, t2.estatus_desc as estd1, t2.created_at as modificado FROM reportes as t1 LEFT JOIN reportes_historial as t2 on t1.id = t2.reporte_id WHERE t2.created_at IN ( SELECT MAX(t3.created_at) FROM reportes_historial as t3 GROUP BY t3.reporte_id ) OR t2.created_at IS NULL) as t4 WHERE t4.created_at > DATE_SUB(NOW(), INTERVAL ".$period." HOUR) ORDER BY t4.created_at DESC;";
	
	# echo $period;
	$activeperiod[$period] = "mybuttonactive";
}else{
	#-- $sqlGetReports = "SELECT * FROM `reportes` ORDER BY `reportes`.`created_at` DESC";
	# $sqlGetReports = "SELECT t1.id, t2.id as repoid, t1.incidente_desc, t1.provincia, t2.estatus, t2.estatus_desc, t1.created_at as created_at, t2.created_at as modificado, t1.geolat, t1.geolon, t1.telefono, t1. FROM reportes as t1 LEFT JOIN reportes_historial as t2 on t1.id = t2.reporte_id WHERE t2.created_at IN ( SELECT MAX(t3.created_at) FROM reportes_historial as t3 GROUP BY t3.reporte_id ) OR t2.created_at IS NULL ORDER BY t1.created_at DESC;";

	$sqlGetReports = "SELECT t4.* from (SELECT t1.*, t2.id as repoid, t2.estatus as est1, t2.estatus_desc as estd1, t2.created_at as modificado FROM reportes as t1 LEFT JOIN reportes_historial as t2 on t1.id = t2.reporte_id WHERE t2.created_at IN ( SELECT MAX(t3.created_at) FROM reportes_historial as t3 GROUP BY t3.reporte_id ) OR t2.created_at IS NULL) as t4 WHERE t4.created_at > DATE_SUB(NOW(), INTERVAL 12 HOUR) ORDER BY t4.created_at DESC;";
}

$resGetReports = mysqli_query($con, $sqlGetReports);
$numOfReports = mysqli_num_rows($resGetReports);

$estatus = array(0=>"No Leido",5=>"Verificando",10=>"Atendido",99=>"Eliminado/Cancelado");
$mycolor = array(0=>"#AAAAAA",5=>"#E8D206",10=>"#00FF00",99=>"#FF0000");


$islogin = 0;
if( $_SESSION['level'] >= 500){
	$islogin = 1;
}

if($islogin == 0){
	exit("Error Level");
}

		
			$geojson = array (
				'type'	=> 'FeatureCollection',
				'features'	=> array()
			);
			
			while ($rowGetReports = mysqli_fetch_assoc($resGetReports)) { 
		
			$myestatus = $rowGetReports['est1'];
			if($myestatus == ""){
				$myestatus = 0;
			}
		
		
				$properties = $rowGetReports;
				unset($properties['geolat']);
				unset($properties['geolon']);
				$properties['color'] = $mycolor[$myestatus];
				$feature = array(
					'type'	=> 'Feature',
					'geometry' => array(
						'type' => 'Point',
						'coordinates' => array(
								$rowGetReports['geolon'],
							    $rowGetReports['geolat']
								)
						),
					'properties' => $properties
				);
				
				array_push($geojson['features'], $feature);
			} // END WHILE

			//header('Content-type: application/json');
			$mapdata = json_encode($geojson, JSON_PRETTY_PRINT);
		
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Reportes - SINAPROC</title>

	<?php
		include("services/header.php");
		$active['map'] = "active";
	?>
	
<style>
	#mapa {
	  width: calc(100% - 250px);
	  height: 100vh;
	  overflow: hidden;
	  background: #000;
	  margin-left: 250px;
	}
	
	#mapa.maxMapa{
		width: calc(100% - 4.5rem);
		margin-left: 4.5rem;
	}
	
	#mapa .esri-popup__footer,#mapa .esri-popup__button.esri-popup__button--dock {
	  display: none;
	}
	
	.areaVerMas {
	  margin-top: 10px;
	  border-top: 1px solid #666;
	  padding: 8px 0;
	}
	.iconmapapop{
		height:32px;
		position: absolute;
		right: 10px;
		bottom: 5px;
	}
	
	.navbar-nav.navbar-nav-map {
	  position: absolute;
	  top: 10px;
	  right: 10px;
	  width: auto;
	  z-index: 9999;
	  text-align: center;
	}
	
	.navbar-nav.navbar-nav-map .nav-link {
	  color: #eee;
	  font-size: 18px;
	  background-color: rgba(0,0,0,0.6);
	  padding: 2px 8px;
	}
	
	.controlpanel {
	  position: absolute;
	  z-index: 9999;
	  right: 10px;
	  top: 60px;
	  width: 70px;
	}

	.controlpanel3 .mybutton {
	  display: inline-block;
	}
	.mybutton.mybuttonactive, .mybutton.mybuttonBMActive {
	  background-color: #aaa;
	  color: #222;
	}
	.mybutton:hover, .mybutton:focus {
	  background-color: rgba(75,75,75,0.7);
	}
	.mybutton {
	  font-size: 12px;
	  color: #fff;
	  cursor: pointer;
	  text-align: center;
	  padding: 4px;
	  margin: 5px;
	  border: 1px solid #999;
	  background-color: rgba(50,50,50,0.6);
	}
	#infopunto {
	  position: absolute;
	  bottom: 11px;
	  right: 10px;
	  z-index: 9999;
	  color: #eee;
	  height: 30px;
	  width: 200px;
	  text-align: right;
	  font-size: 12px;
	}
	
	.controlpanel3 {
	  position: absolute;
	  z-index: 9999;
	  right: 30%;
	  top: 2px;
	  width: auto;
	}

	.numofreports {
	  font-size: 13px;
	  font-weight: 300;
	  color: #eee;
	  text-align: right;
	  margin: 4px auto;
	}
	.numofreports.numofreportstitle{
	   font-size: 14px;
	   font-weight: 600;
	   color: #fff;
	   border-bottom:1px solid #ddd;
	}
	
	.numofreports img
	{
		height: 18px;
		width:18px;
	}
	
	.controlpanel2{
	  position: absolute;
	  right: 10px;
	  bottom: 60px;
	  z-index: 9999;
	  padding: 9px 12px;
	  background-color: rgba(60,60,60,0.7);
	  border: 0px solid #888;
	  width: auto;
	  height: auto;
	}
</style>

	<link rel="stylesheet" href="https://js.arcgis.com/4.24/esri/themes/dark/main.css" />
	

</head>

<body class="hold-transition sidebar-mini" id="mymapabody">
    <?php
		include("services/menu.php");
	?>
	<ul class="navbar-nav navbar-nav-map">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                
    </ul>
	<div class="controlpanel3">
		<a href="mapa.php?period=12" class="mybutton <?php echo $activeperiod[12]; ?>" id="mybtn12h">12 Horas</a>
		<a href="mapa.php?period=24" class="mybutton <?php echo $activeperiod[24]; ?>" id="mybtn24h">24 Horas</a>
		<a href="mapa.php?period=168" class="mybutton <?php echo $activeperiod[168]; ?>" id="mybtn7D">7 Días</a>
		<a href="mapa.php?period=360" class="mybutton <?php echo $activeperiod[360]; ?>" id="mybtn15D">15 Días</a>
		<a href="mapa.php?period=720" class="mybutton <?php echo $activeperiod[720]; ?>" id="mybtn30D">30 Días</a>
		<a href="mapa.php?period=1440" class="mybutton <?php echo $activeperiod[1440]; ?>" id="mybtn60D">60 Días</a>
	</div>
	<div class="controlpanel">
		<div class="mybutton mybuttonBaseMap mybuttonBMActive" id="mybtnBMDM">Dark Map</div>
		<div class="mybutton mybuttonBaseMap" id="mybtnBMTM">Topografía</div>
		<div class="mybutton mybuttonBaseMap" id="mybtnBMSTV">Calles</div>
		<div class="mybutton mybuttonBaseMap" id="mybtnBMSAT">Satélite</div>
		<div class="mybutton mybuttonBaseMap" id="mybtnBMTR">Terreno</div>
	</div>
	<div class="controlpanel2">
		<div class="numofreports numofreportstitle">Reportes: <?php echo $numOfReports; ?></div>
		<!--<div class="numofreports">Total: <strong></strong></div>-->
		<div class="numofreports">Atendidos: <strong class="repocount rcount_10"></strong> <img src="img/estatus_10.png"></div>
		<div class="numofreports">Verificando: <strong class="repocount rcount_5"></strong> <img src="img/estatus_5.png"></div>
		<div class="numofreports">Cancelados: <strong class="repocount rcount_99"></strong> <img src="img/estatus_99.png"></div>
		<div class="numofreports">No leídos: <strong class="repocount rcount_0"></strong> <img  src="img/estatus_0.png"></div>
	 </div>
	<div id="infopunto"></div>
	<div id="mapa">
    </div>
   

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="js/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="js/admin_lte/adminlte.min.js"></script>
	<script src="https://js.arcgis.com/4.24/"></script>
    <!--<script src="js/mapa.js"></script>-->
	
	<script>
	var map;
    var pointo = [];
	var estatusCount = {
			'0' : 0,
			'5' : 0,
			'10' : 0,
			'99' : 0,
		};
		
	var mapacluster = <?php echo $mapdata; ?>;
		// create a new blob from geojson featurecollection
		const blob = new Blob([JSON.stringify(mapacluster)], {
		  type: "application/json"
		});

		// URL reference to the blob
		const url = URL.createObjectURL(blob);
		// create new geojson layer using the blob url
		
		
	$(document).ready(function () {
		$("#mymainsidebar").on('resize change', function() {
			mapaAnchoToogle();
		});
		
		new ResizeObserver(mapaAnchoToogle).observe(mymainsidebar);
  
	});
	
    
       
       require([
	   "esri/config",
	   "esri/Map",
		"esri/layers/FeatureLayer",
        "esri/layers/GeoJSONLayer",
        "esri/widgets/Legend",
        "esri/widgets/Expand",
        "esri/widgets/Home",
	   "esri/views/MapView", 
	   "esri/Graphic", 
	   "esri/geometry/support/webMercatorUtils",
	    "esri/rest/support/Query",
      "esri/renderers/UniqueValueRenderer"
	   ], 
	   function(esriConfig, Map, FeatureLayer, GeoJSONLayer, Legend, Expand, Home ,MapView, Graphic, webMercatorUtils, Query,UniqueValueRenderer){
       esriConfig.apiKey = "AAPKb28a4dc7759d47eba5bc224f3ea16a631t5GLyDebmX7a9Up0h-ro0PytWu8nBLpEAOap3kLUlOYcF1KVehKzDkUWhuJgpzL";
       
	
		
		 // Configures clustering on the layer. A cluster radius
        // of 100px indicates an area comprising screen space 100px
        // in length from the center of the cluster

        const clusterConfig = {
          type: "cluster",
          clusterRadius: "50px",
          // {cluster_count} is an aggregate field containing
          // the number of features comprised by the cluster
          popupTemplate: {
            title: "Resumen",
            content: "Aquí existen {cluster_count} reportes.",
            fieldInfos: [{
              fieldName: "cluster_count",
              format: {
                places: 0,
                digitSeparator: true
              }
            }]
          },
          clusterMinSize: "20px",
          clusterMaxSize: "40px",
          labelingInfo: [{
            deconflictionStrategy: "none",
            labelExpressionInfo: {
              expression: "Text($feature.cluster_count, '#,###')"
            },
            symbol: {
              type: "text",
              color: "#004a5d",
              font: {
                weight: "bold",
                family: "Noto Sans",
                size: "12px"
              }
            },
            labelPlacement: "center-center",
          }]
        };


        const geojsonLayer = new GeoJSONLayer({
          title: "Reportes",
          url: url,
          copyright: "SINAPROC",

          featureReduction: clusterConfig,

          // popupTemplates can still be viewed on
          // individual features
          popupTemplate: {
            title: "{incidente_desc}",
            content: "{descripcion} - {estatus}",
            fieldInfos: [
              {
                fieldName: "created_at",
                format: {
                  dateFormat: "short-date-short-time"
                }
              }
            ]
          },
          renderer: {
            type: "simple",
            field: "color",
            symbol: {
              type: "simple-marker",
              size: 13,
			  color: "",
              outline: {
                color: "rgba(0, 0, 0, 1)",
                width: 1
              }
            }
          }
        });
		
		/************/
		
		 geojsonLayer.when(function() {
        // Assuming points:
        // geojsonLayer.renderer.type = 'simple-marker'
        // Look for all distinct color values, and create UniqueValueRenderer 
        const theField = 'color';
        if (geojsonLayer.getField(theField)) {
          const query = new Query();
          query.returnDistinctValues = true;
          query.returnGeometry = false;
          query.outFields = theField;
          geojsonLayer.queryFeatures(query).then(function(results) {
            const renderer = new UniqueValueRenderer({
              field: theField,
              defaultSymbol: {
                type: "simple-marker"
              },
              
            });
            results.features.forEach((element, index) => {
              console.log(`Unique value ${index}: ${element.attributes[theField]}`);
              renderer.addUniqueValueInfo({
                value: element.attributes[theField],
                label: `Features with ${element.attributes[theField]} color`,
                symbol: {
                  type: "simple-marker",
                  color: element.attributes[theField],
                  size: 13
                }
              });
            }); // end forEach 
            geojsonLayer.renderer = renderer;
          }); // end query
        } else {
          console.log(`No ${theField} field available.`);
        }
      }, function(error) {
        console.log("Something unexpected", error)
      });
		/******************/
		
		map = new Map({
          basemap: "dark-gray-vector",
		  layers:[geojsonLayer]
		  //Valid values are: "streets-vector" | "satellite" | "hybrid" | "topo-vector" | "gray-vector" | "dark-gray-vector" | "oceans" | "national-geographic" | "terrain" | "osm" | "dark-gray" | "gray" | "streets" | "streets-night-vector" | "streets-relief-vector" | "streets-navigation-vector" | "topo". The "terrain" and "dark-gray" options were added at v3.12. The "dark-gray-vector", "gray-vector", "streets-vector", "streets-night-vector", "streets-relief-vector", "streets-navigation-vector" and "topo-vector" options were added at v3.16.
          });
		  
		map.on("load",function(){
			map.on("mouse-move", showCoordinates);
		});
        const view = new MapView({
          map: map,
          center: [-80.2, 8.5], // longitude, latitude
          zoom: 7,
          container: "mapa" // Div element
        });
		
		
		 view.when(function(){
			//after map loads, connect to listen to mouse move & drag events
			view.on("pointer-move", showCoordinates);
			$(document).on("click","#mybtnBMTM",function(){
				changeBaseMap('topo-vector','mybtnBMTM');
			});
			$(document).on("click","#mybtnBMDM",function(){
				changeBaseMap('dark-gray-vector','mybtnBMDM');
			});
			$(document).on("click","#mybtnBMSTV",function(){
				changeBaseMap('streets-vector','mybtnBMSTV');
			});
			$(document).on("click","#mybtnBMTR",function(){
				changeBaseMap('terrain','mybtnBMTR');
			});
			$(document).on("click","#mybtnBMSAT",function(){
				changeBaseMap('hybrid','mybtnBMSAT');
			});
		  });
		
		
			//
		function showCoordinates(evt) {
			var point = view.toMap({x: evt.x, y: evt.y});
			//the map is in web mercator but display coordinates in geographic (lat, long)
			var mp = webMercatorUtils.webMercatorToGeographic(point);
			//display mouse coordinates
			$("#infopunto").html("Lon: "+mp.x.toFixed(3) + ", Lat: " + mp.y.toFixed(3));
		  }
		  //
		  
		 function changeBaseMap(base,rr){
			 //map.setBasemap(base);
			 var newBasemap = base;
			 view.map.basemap = newBasemap;
			 $('.mybutton').removeClass('mybuttonBMActive');
			 $('#'+rr).addClass('mybuttonBMActive');
		 }
		
      });
	  
	  function mapaAnchoToogle(){
		  var mapOpen = 0;
		  if($("#mymapabody").hasClass('sidebar-collapse')){
			  mapOpen = 1;
		  }
		  
		  $(".nav-item").removeClass("menu-is-opening");
		  $(".nav-item").removeClass("menu-open");
		  
		  if(mapOpen == 1){
			  $("#mapa").addClass('maxMapa');
			  
		  }else{
			  $("#mapa").removeClass('maxMapa');
		  }
	  }

	</script>
</body>

</html>
