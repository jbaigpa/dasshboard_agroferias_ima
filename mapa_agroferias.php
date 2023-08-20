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
$activeperiod[1200000] = "";
$period = $_GET['period'];
#-- 72 (3d) -- 168(7d) -- 360(15d) - 720 (30d)

if($period == '12' || $period == '24' || $period == '72' || $period == '168' || $period == '360' || $period == '720' || $period == '1440'|| $period == '1200000'){
	$period = intval($period);
	#$sqlGetReports = "SELECT t1.id, t1.control, t1.cedula, t1.producto, t1.fecha_compra, t1.lugar_compra, t1.provincia, t1.distrito, t1.corregimiento, t1.vendedor, t1.geolon, t1.geolat, t1.supervisor, t1.device, t1.offline FROM `ventas` as t1  WHERE t1.fecha_registro > DATE_SUB(NOW(), INTERVAL ".$period." HOUR) GROUP BY t1.id ORDER BY t1.fecha_compra DESC;";
	$sqlGetReports = "SELECT t1.id FROM `ventas` as t1  WHERE t1.fecha_registro > DATE_SUB(NOW(), INTERVAL ".$period." HOUR) GROUP BY t1.id;";
	
	$sqlGetFF = "SELECT t1.id FROM `intentos_fallidos` as t1  WHERE t1.created_at > DATE_SUB(NOW(), INTERVAL ".$period." HOUR) GROUP BY t1.id;";
	
	$activeperiod[$period] = "mybuttonactive";
}else{
	$period = 720;
	$sqlGetReports = "SELECT t1.id FROM `ventas` as t1  WHERE t1.fecha_registro > DATE_SUB(NOW(), INTERVAL ".$period." HOUR) GROUP BY t1.id;";
	
	$sqlGetFF = "SELECT t1.id FROM `intentos_fallidos` as t1  WHERE t1.created_at > DATE_SUB(NOW(), INTERVAL ".$period." HOUR) GROUP BY t1.id;";
	
	$activeperiod[720] = "mybuttonactive";
	
	
}


$resGetReports = mysqli_query($con, $sqlGetReports);
$numOfReports = mysqli_num_rows($resGetReports);
$resGetFF = mysqli_query($con, $sqlGetFF);
$numOfFF = mysqli_num_rows($resGetFF);


$estatus = array(0=>"No Leido",5=>"Verificando",10=>"Atendido",99=>"Eliminado/Cancelado");
$mycolor = array(0=>"#AAAAAA",5=>"#E8D206",10=>"#00FF00",99=>"#FF0000");


$islogin = 0;
if( $_SESSION['level'] >= 500){
	$islogin = 1;
}

if($islogin == 0){
	exit("Error Level");
}

	/*	
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
	*/	
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Ventas Agroferias Mapa</title>

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
	  transition: .5s ease all;
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
	  width: 90px;
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
	  font-size: 28px;
	  font-weight: 600;
	  color: #eee;
	  text-align: right;
	}
	
	.numofreports img
	{
		height: 18px;
		width:18px;
	}
	
	.controlpanel2{
	  position: absolute;
	  left: 260px;
	  bottom: 25px;
	  z-index: 9999;
	  padding: 9px 20px;
	  background-color: rgba(250,180,40,0.9);
	  border: 0px solid #888;
	  width: auto;
	  height: auto;
	  display:none;
	}
	
	.controlpanel2 .numofreports {
	  color: #000;
	 
	}
	
	.controlpanel4{
	  position: absolute;
	  left: 440px;
	  bottom: 25px;
	  z-index: 9999;
	  padding: 9px 20px;
	  background-color: rgba(255,60,60,0.8);
	  border: 0px solid #888;
	  width: auto;
	  height: auto;
	  display:none;
	}
	
	.controlpanel2.maxMapa{
		left: 90px;
	}
	
	.controlpanel4.maxMapa{
		left: 270px;
	}

	.popuimg {
		margin: 10px auto;
		width: 70px;
		display: block;
		height: 70px;
		border: 1px solid #fff;
		cursor: pointer;
	}

	.esri-feature-content .areaVerMas a {
		color: #fff !important;
		font-weight: 700;
	}

	.imgpupiarea {
		display: grid;
		grid-auto-columns: auto;
		grid-template-columns: 33.3% 33.3% 33.3%;
	}
	
	.miniX {
	  font-size: 14px;
	  font-weight: 500;
	  text-align: right;
	  text-transform: uppercase;
	}
	
	.loader {
	  position: absolute;
	  z-index: 9998;
	  left: 250px;
	  right: 0px;
	  background: rgba(0,0,0,0.5);
	  padding: 0px;
	  display:none;
	  width: calc(100% - 250px);
	  height:100vh;
	}
	
	.loader img {
		position:absolute;
		height:90px;
	    width:90px;
		padding:14px;
		background: rgba(255,255,255,0.5);
		z-index: 9998;
		transform: translate(-50%,-50%);
	    top:50%;
		left:50%;
	}
	
	.controlpanel2, .controlpanel4 {
	  width: 165px;
	  overflow: hidden;
	  transition: 1s ease all;
	}
	
	.controlpanel2 .fa, .controlpanel4 .fa {
	  position: absolute;
	  font-size: 44px;
	  left: 8px;
	  bottom: 8px;
	  color: rgba(0,0,0,0.25);
	  z-index: -1;
	  transform: rotate(-20deg);
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
	
	<div class="loader">
		<div class="loadimg">
			<img src="img/preloader2.gif">
		</div>
	</div>
	<div class="controlpanel3">
		
		<a href="mapa_agroferias.php?period=24" class="mybutton <?php echo $activeperiod[24]; ?>" id="mybtn24h">24 Horas</a>
		<a href="mapa_agroferias.php?period=168" class="mybutton <?php echo $activeperiod[168]; ?>" id="mybtn7D">7 Días</a>
		<a href="mapa_agroferias.php?period=360" class="mybutton <?php echo $activeperiod[360]; ?>" id="mybtn15D">15 Días</a>
		<a href="mapa_agroferias.php?period=720" class="mybutton <?php echo $activeperiod[720]; ?>" id="mybtn30D">30 Días</a>
		<a href="mapa_agroferias.php?period=1440" class="mybutton <?php echo $activeperiod[1440]; ?>" id="mybtn60D">60 Días</a>
		<a href="mapa_agroferias.php?period=1200000" class="mybutton <?php echo $activeperiod[1200000]; ?>" id="mybtntodoD">Todo</a>
	</div>
	<div class="controlpanel">
		<div class="mybutton mybuttonBaseMap mybuttonBMActive" id="mybtnBMDM">Dark Map</div>
		<div class="mybutton mybuttonBaseMap" id="mybtnBMTM">Topografía</div>
		<div class="mybutton mybuttonBaseMap" id="mybtnBMSTV">Calles</div>
		<div class="mybutton mybuttonBaseMap" id="mybtnBMSAT">Satélite</div>
		<div class="mybutton mybuttonBaseMap" id="mybtnBMTR">Terreno</div>
	</div>
	<div class="controlpanel2">
		<i class="fa fa-shopping-cart"></i>
		<div class="numofreports numofreportstitle"><div class="miniX">Compradores</div>
		<?php echo number_format($numOfReports, 0, '.', ','); ?>
		</div>
	 </div>
	 
	 <div class="controlpanel4">
		<i class="fa fa-ban"></i>
		<div class="numofreports numofreportstitle"><div class="miniX">Intentos Fallidos</div>
		<?php echo number_format($numOfFF, 0, '.', ','); ?>
		</div>
	 </div>
	<div id="infopunto"></div>
	<div id="mapa">
    </div>
   <div id="infoDiv" class="esri-widget">
     <!-- <button id="cluster" class="esri-button">Disable Clustering</button>-->
      <div id="legendDiv"></div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="js/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="js/admin_lte/adminlte.min.js"></script>
	<script src="https://js.arcgis.com/4.27/"></script>
    <!--<script src="js/mapa.js"></script>-->
	
	<script>
	var map;
	var period = <?php echo $period; ?>;
    var pointo = [];
	var estatusCount = {
			'0' : 0,
			'5' : 0,
			'10' : 0,
			'99' : 0,
		};
		
	/*
	var mapacluster = <?php echo $mapdata; ?>;
		// create a new blob from geojson featurecollection
		const blob = new Blob([JSON.stringify(mapacluster)], {
		  type: "application/json"
		});
	*/
		// URL reference to the blob
		const url = "http://agroferias-ws.aig.gob.pa/agroferias_admin/services/mapdata01.php?period="+period;//URL.createObjectURL(blob);
		// create new geojson layer using the blob url
		
		
	$(document).ready(function () {
		$("#mymainsidebar").on('resize change', function() {
			mapaAnchoToogle();
		});
		
		new ResizeObserver(mapaAnchoToogle).observe(mymainsidebar);
		$(".controlpanel2").show();
		$(".controlpanel4").show();
  
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
	   "esri/geometry/support/webMercatorUtils",
	   "esri/Graphic",
		"esri/geometry/Extent",
		"esri/symbols/TextSymbol"
		], 
	   function(esriConfig,Map, FeatureLayer, GeoJSONLayer, Legend, Expand, Home ,MapView, webMercatorUtils,Graphic,Extent,TextSymbol){
       esriConfig.apiKey = "AAPKb28a4dc7759d47eba5bc224f3ea16a631t5GLyDebmX7a9Up0h-ro0PytWu8nBLpEAOap3kLUlOYcF1KVehKzDkUWhuJgpzL";
       
	
		
		 // Configures clustering on the layer. A cluster radius
        // of 100px indicates an area comprising screen space 100px
        // in length from the center of the cluster

        const clusterConfig = {
          type: "cluster",
          clusterRadius: "25px",
          // {cluster_count} is an aggregate field containing
          // the number of features comprised by the cluster
          popupTemplate: {
            title: "Ventas en Agroferias",
            content: "En esta zona se dieron {cluster_count} ventas.",
            fieldInfos: [{
              fieldName: "cluster_count",
              format: {
                places: 0,
                digitSeparator: true
              }
            }]
          },
          clusterMinSize: "20px",
          clusterMaxSize: "50px",
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
                size: "13px"
              },
			  haloColor: "#FFFFFF",
              haloSize: 1
            },
            labelPlacement: "center-center",
          }],
		  symbol: {
            type: "simple-marker",
            style: "circle",
            color: "#FFD400",
            outline: {
              color: "rgba(255, 212, 0, 0.5)",
              width: 6
            }
          },
        };

		const mycontent = "ID: {id}<br>Fecha: {fecha_compra}<br>Cédula: {cedula}<br>Producto: {producto}<br>Usuario: {vendedor}<br>Device: {device}<br>Supervisor: {supervisor}";
		$(".loader").show();
        const geojsonLayer = new GeoJSONLayer({
          title: "Ventas Agroferias",
          url: url,
          copyright: "IMA",

          featureReduction: clusterConfig,

          // popupTemplates can still be viewed on
          // individual features
          popupTemplate: {
            title: "{lugar_compra}",
            content: mycontent,
            fieldInfos: [
              {
                fieldName: "fecha_compra",
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
              size: 12,
			  color: "#FFFF00",
              outline: {
                color: "rgba(0, 0, 0, 0.8)",
                width: 2
              }
            }
          }
        });
		
		
		/******************/
		
		map = new Map({
          basemap: "streets-night-vector",
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
		
		view.ui.add(
          new Home({
            view: view
          }),
          "top-left"
        );

        const legend = new Legend({
          view: view,
          container: "legendDiv"
        });
		
		const infoDiv = document.getElementById("infoDiv");
        view.ui.add(
          new Expand({
            view: view,
            content: infoDiv,
            expandIcon: "list-bullet",
            expanded: false
          }),
          "top-left"
        );
		
		RectanGulo(-79.145,7.387,0.5,0.5);
		
        const toggleButton = document.getElementById("cluster");
		
		 view.when(function(){
			//after map loads, connect to listen to mouse move & drag events
			view.on("pointer-move", showCoordinates);
			$(document).on("click","#mybtnBMTM",function(){
				changeBaseMap('topo-vector','mybtnBMTM');
			});
			$(document).on("click","#mybtnBMDM",function(){
				changeBaseMap('streets-night-vector','mybtnBMDM');
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
			console.log("Mapas Base Cargados");
		  });
		
		view.whenLayerView(geojsonLayer).then(function(layerView) {
			// La capa se ha cargado completamente.
			console.log("La capa GeoJSON se ha cargado.");
			$(".loader").hide();
			// Puedes realizar acciones adicionales aquí.
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
		 
		 function RectanGulo(xx,yy,ww,hh){
			 // Coordenadas del centro del rectángulo
			  var x = xx;//-118.2437;
			  var y = yy;//34.0522;

			  // Tamaño del rectángulo (ancho y alto en unidades de coordenadas)
			  var width = ww;//0.1;
			  var height = hh;//0.05;

			  // Calcular las coordenadas del vértice superior izquierdo y del vértice inferior derecho
			  var xmin = x - (width / 2);
			  var ymin = y - (height / 2);
			  var xmax = x + (width / 2);
			  var ymax = y + (height / 2);

			  // Crear la geometría del rectángulo utilizando Extent
			  var extent = new Extent({
				xmin: xmin,
				ymin: ymin,
				xmax: xmax,
				ymax: ymax,
				spatialReference: view.spatialReference
			  });

			  // Símbolo del rectángulo (color, borde, etc.)
			  var symbol = {
				type: "simple-fill", // Tipo de símbolo: relleno
				color: [255, 255, 255, 0], // Color del relleno en formato RGBA (rojo, verde, azul, transparencia)
				outline: {
				  color: [255, 255, 255,0.7], // Color del borde en formato RGBA (rojo, verde, azul, opacidad)
				  width: 1 // Ancho del borde en píxeles
				}
			  };

			  // Crear el gráfico para representar el rectángulo
			  var rectangleGraphic = new Graphic({
				geometry: extent,
				symbol: symbol
			  });

			   // Agregar el texto al rectángulo
				  var textSymbol = new TextSymbol({
					text: "NO GPS",
					color: "#999999", // Color del texto
					haloColor: "white", // Color del halo que rodea el texto
					haloSize: "0px", // Tamaño del halo
					font: { // Estilo de fuente
					  size: 10, // Tamaño de la fuente en puntos
					  weight: "bold" // Peso de la fuente (normal, bold, etc.)
					}
				  });
				  
				  // Calcular la posición del texto dentro del rectángulo
				  var textPosition = extent.center.clone();
				  textPosition.y = extent.ymax - 0.08; 

				  // Crear el gráfico para representar el texto
				  var textGraphic = new Graphic({
					geometry: textPosition,
					symbol: textSymbol
				  });

				  // Agregar el gráfico del rectángulo y el gráfico del texto al mapa
				  view.graphics.addMany([rectangleGraphic, textGraphic]);
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
			  $(".controlpanel2").addClass('maxMapa');
			  $(".controlpanel4").addClass('maxMapa');
			  
		  }else{
			  $("#mapa").removeClass('maxMapa');
			  $(".controlpanel2").removeClass('maxMapa');
			  $(".controlpanel4").removeClass('maxMapa');
			  
		  }
	  }

	</script>
</body>

</html>
