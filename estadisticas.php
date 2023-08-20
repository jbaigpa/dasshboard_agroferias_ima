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

if(isset($_GET['period'])){
	$period = $_GET['period']; 
}else{
	$period = 1200000; //Todos
}
$activeperiod[$period] = "active";


$sqlGetProvincias = "SELECT count(*) as cantidad_lugar, `lugar_compra` FROM `ventas` WHERE fecha_registro > DATE_SUB(NOW(), INTERVAL ".$period." HOUR)  GROUP BY `lugar_compra`;";
$resGetProvincias = mysqli_query($con, $sqlGetProvincias);

$sqlGetTipo= "SELECT count(*) as cantidadtipo, `producto` FROM `ventas` WHERE fecha_registro > DATE_SUB(NOW(), INTERVAL ".$period." HOUR) GROUP BY `producto`;";
$resGetTipo= mysqli_query($con, $sqlGetTipo);

$sqlGetVentas= "SELECT count(*) as cantventas, t1.vendedor, t2.nombre FROM `ventas` as t1 join `operadores` as t2 on t1.vendedor = t2.cedula wHERE t1.fecha_registro > DATE_SUB(NOW(), INTERVAL ".$period." HOUR) GROUP BY t1.vendedor;";
$resGetVentas= mysqli_query($con, $sqlGetVentas);

$sqlGetFallido= "SELECT count(*) as cntfallidos, t1.user as user, t2.ubicacion as nombre FROM `intentos_fallidos` as t1 join `operadores` as t2 on t1.user = t2.cedula WHERE t1.created_at > DATE_SUB(NOW(), INTERVAL ".$period." HOUR) GROUP BY t2.ubicacion;";
$resGetFallido= mysqli_query($con, $sqlGetFallido);

$estatus = array(0=>"No Leido",5=>"Verificando",10=>"Atendido",99=>"Eliminado/Cancelado");
$mycolorestatus = array(0=>"#AAAAAA",5=>"#FFFF00",10=>"#00FF00",99=>"#FF0000");


$islogin = 0;
if($_SESSION['level'] > 50){
	$islogin = 1;
}

if($islogin == 0){
	exit("Error Level");
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	 <meta http-equiv="refresh" content="70">

    <title>Estadísticas - IMA AGROFERIAS</title>

	<?php
		include("services/header.php");
		$active['stats'] = "active";
	?>
	
	<style>
	.desactivaClass.even, .desactivaClass.odd {
	  background-color: #f99;
	  color: #eee;
	}
	
	.desactivaClass.even:hover, .desactivaClass.odd:hover {
	  background-color: #f44;
	  color: #fff;
	}
	.nav-item.active {
	  border: 1px solid #999;
	  background: #eee;
	}
	</style>


</head>

<body class="hold-transition sidebar-mini">
    <!-- Site wrapper -->
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
				<!-- 72 (3d) -- 168(7d) -- 360(15d) - 720 (30d) -->
                <li class="nav-item d-none d-sm-inline-block <?php echo $activeperiod[24]; ?>">
                    <a href="estadisticas.php?period=24" class="nav-link">24h</a>
                </li>
				<li class="nav-item d-none d-sm-inline-block <?php echo $activeperiod[72]; ?>">
                    <a href="estadisticas.php?period=72" class="nav-link">3d</a>
                </li>
				<li class="nav-item d-none d-sm-inline-block <?php echo $activeperiod[168]; ?>">
                    <a href="estadisticas.php?period=168" class="nav-link">7d</a>
                </li>
				<li class="nav-item d-none d-sm-inline-block <?php echo $activeperiod[360]; ?>">
                    <a href="estadisticas.php?period=360" class="nav-link">15d</a>
                </li>
				<li class="nav-item d-none d-sm-inline-block">
                    <a href="estadisticas.php?period=720" class="nav-link <?php echo $activeperiod[720]; ?>">30d</a>
                </li>
				<li class="nav-item d-none d-sm-inline-block">
                    <a href="estadisticas.php?period=1440" class="nav-link <?php echo $activeperiod[1440]; ?>">60d</a>
                </li>
				<li class="nav-item d-none d-sm-inline-block <?php echo $activeperiod[1200000]; ?>">
                    <a href="estadisticas.php" class="nav-link ">Todo</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="mapa_agroferias.php" class="nav-link">Mapa</a>
                </li>
            </ul>
        </nav>
		<?php
					include("services/menu.php");
				?>
       

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Estadísticas</h1>
                        </div>
                        
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">

                <div class="container-fluid">
					
					<div class="row">
					<div class="col-lg-3 col-6">
						<div class="small-box bg-warning">
							<div class="inner">
								<h3 id="productosvendidostotal"></h3>
								<p>Compradores</p>
							</div>
							<div class="icon">
								<i class="fas fa-shopping-cart"></i>
							</div>
							
						</div>
					</div>
					
					<div class="col-lg-3 col-6">
						<div class="small-box bg-danger">
							<div class="inner">
								<h3 id="intentosfallidostotal"></h3>
								<p>Intentos Fallidos</p>
							</div>
							<div class="icon">
								<i class="fas fa-ban"></i>
							</div>
							
						</div>
					</div>
					
					<div class="col-lg-3 col-6">
						<div class="small-box bg-success">
							<div class="inner">
								<h3 id="operadorestotal"></h3>
								<p>Operadores</p>
							</div>
							<div class="icon">
								<i class="fas fa-user"></i>
							</div>
							
						</div>
					</div>
					
				</div>
				
					<div class="row">
					<div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                Ventas por Sitio
                            </div>
                        </div>
                        <div class="card-body">
                        <canvas id="myChart1" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%; display: block;" class="chartjs-render-monitor" width="678" height="375"></canvas>
						</div>
                    </div>
				  </div>
				  
				 <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                Ventas por Producto
                            </div>
                        </div>
                        <div class="card-body">
							<canvas id="myChart2" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%; display: block;" class="chartjs-render-monitor" width="678" height="375"></canvas>
						</div>
                    </div>
				  </div>
				  
				  </div>
				  
				  <div class="row">
					<div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                Ventas por Operador
                            </div>
                        </div>
                        <div class="card-body">
							<canvas id="myChart3" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%; display: block;" class="chartjs-render-monitor" width="678" height="375"></canvas>
						</div>
                    </div>
				  </div>
				  
				 <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                Intentos Fallidos por Sitio
                            </div>
                        </div>
                        <div class="card-body">
                        <canvas id="myChart4" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%; display: block;" class="chartjs-render-monitor" width="678" height="375"></canvas>
						</div>
                    </div>
				  </div>
				  
				  </div>
				  
                </div>

            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <footer class="main-footer">
            <div class="d-none d-sm-block">
                <b>Version</b> 1.0.0
            </div>

        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <!--<script src="https://www.google.com/recaptcha/api.js?render=6LeiVcYaAAAAAJdD1cpDx2jP8PSGokBgnG9hKDob"></script>-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="js/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="js/admin_lte/adminlte.min.js"></script>
	
	
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js" integrity="sha512-ElRFoEQdI5Ht6kZvyzXhYG9NqjtkmlkfYk0wr6wHxU9JEHakS7UJZNeml5ALk+8IKlU6jDgMabC3vkumRokgJA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	
	<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0/dist/chartjs-plugin-datalabels.min.js"></script>
	
	
	<script>
	var datos1 = [];
	var labels1 = [];
	var colors1 = [];
	var datos2 = [];
	var labels2 = [];
	var colors2 = [];
	var datos3 = [];
	var labels3 = [];
	var colors3 = [];
	var datos4 = [];
	var labels4 = [];
	var colors4 = [];
	var datosFF = [];
	var labelsFF = [];
	var colorsFF = [];
	var usedColors = [];
	
   <?php while ($rowGetProvincias = mysqli_fetch_assoc($resGetProvincias)) {?>
	datos1.push(<?php echo $rowGetProvincias['cantidad_lugar']; ?>);
	labels1.push('<?php echo ucwords(strtolower($rowGetProvincias['lugar_compra'])); ?>');
	colors1.push(traecolor());
	<?php	}?>
	
	
	
	usedColors = [];
	<?php while ($rowGetTipo = mysqli_fetch_assoc($resGetTipo)) {?>
	datos2.push(<?php echo $rowGetTipo['cantidadtipo']; ?>);
	labels2.push('<?php echo ucwords(strtolower($rowGetTipo['producto'])); ?>');
	colors2.push(traecolor());
	<?php	} ?>
	
	usedColors = [];
	<?php while ($rowGetVentas = mysqli_fetch_assoc($resGetVentas)) {?>
	datos3.push(<?php echo $rowGetVentas['cantventas']; ?>);
	labels3.push('<?php echo ucwords(strtolower($rowGetVentas['nombre'])); ?>');
	colors3.push(traecolor());
	<?php	} ?>
	
	usedColors = [];
	<?php while ($rowGetFF = mysqli_fetch_assoc($resGetFallido)) {?>
	datosFF.push(<?php echo $rowGetFF['cntfallidos']; ?>);
	labelsFF.push('<?php echo ucwords(strtolower($rowGetFF['nombre'])); ?>');
	colorsFF.push(traecolor());
	<?php	} ?>
	
	
	Chart.register(ChartDataLabels);
	Chart.defaults.set('plugins.datalabels', {
			display: true,
			align: 'middle',
			backgroundColor: 'transparent',
			borderRadius: 100,
			textStrokeColor:'rgba(0,0,0,0.6)',
			textStrokeWidth: 5,
			color: '#fff',
			font: {
			  size: 12,
			},
			formatter: function(value, context) {
				return acnum(Math.abs(value));
			}
	});

	
	

	const data1 = {
  labels: labels1,
  datasets: [
    {
      label: 'Productos vendidos',
      data: datos1,
      /*borderColor: '#000ff',*/
      backgroundColor: colors1,
      borderWidth: 2,
      borderRadius: 3,
      borderSkipped: false,
    }
  ]
};

    // Calcula el total de los valores
    var total1 = data1.datasets[0].data.reduce((acc, val) => acc + val, 0);

    // Agrega el total a la leyenda
    //data1.datasets[0].label += " (Total: " + total1 + ")";
	$("#productosvendidostotal").html(acnum(total1));

const config1 = {
  type: 'bar',
  data: data1,
  options: {
    responsive: true,
    plugins: {
      legend: {
		  display: false,
        position: 'top',
      },
      title: {
        display: false,
        text: ''
      },
	  datalabels: {
        display: true,
        align: 'top',
        backgroundColor: 'transparent',
        borderRadius: 100,
        font: {
          size: 12,
        },
	  }
    }
  },
};


const ctx1 = document.getElementById('myChart1');
const myChart1 = new Chart(ctx1, config1);


const data2 = {
  labels: labels2,
  datasets: [
    {
       label: 'Cantidad de Reportes',
      data: datos2,
      /*borderColor: '#000ff',*/
      backgroundColor: colors2,
      borderWidth: 2,
      borderRadius: 3,
      borderSkipped: false,
    }
  ]
};
const config2 = {
  type: 'pie',
  data: data2,
  options: {
    responsive: true,
    plugins: {
      legend: {
		display: false,
        position: 'bottom',
      },
      title: {
        display: false,
        text: ''
      }
    }
  },
};

const ctx2 = document.getElementById('myChart2');
const myChart2 = new Chart(ctx2, config2);


const data3 = {
  labels: labels3,
  datasets: [
    {
      label: 'Ventas por Operador',
      data: datos3,
      borderColor: '#666666',
      backgroundColor: colors3,
      borderWidth: 2,
      borderRadius: 3,
      borderSkipped: false,
    }
  ]
};

 // Calcula el total de los valores
    var total3 = labels3.length;

	$("#operadorestotal").html(acnum(total3));
	
const config3 = {
  type: 'doughnut',
  data: data3,
  options: {
    responsive: true,
	
    plugins: {
      legend: {
		display: false,
        position: 'bottom',
      },
      title: {
        display: false,
        text: ''
      }
    }
  },
};

const ctx3 = document.getElementById('myChart3');
const myChart3 = new Chart(ctx3, config3);

/*********************/

const dataFF = {
  labels: labelsFF,
  datasets: [
    {
      label: 'Fallidos',
      data: datosFF,
      /*borderColor: '#000ff',*/
      backgroundColor: colorsFF,
      borderWidth: 2,
      borderRadius: 3,
      borderSkipped: false,
    }
  ]
};

    // Calcula el total de los valores
    var totalFF = dataFF.datasets[0].data.reduce((acc, val) => acc + val, 0);

    // Agrega el total a la leyenda
    //data1.datasets[0].label += " (Total: " + total1 + ")";
	$("#intentosfallidostotal").html(acnum(totalFF));

const configFF = {
  type: 'bar',
  data: dataFF,
  options: {
    responsive: true,
    plugins: {
      legend: {
        position: 'top',
		display: false,
      },
      title: {
        display: false,
        text: ''
      }
    }
  },
};


const ctxFF = document.getElementById('myChart4');
const myChartFF = new Chart(ctxFF, configFF);

/***************************/

const data4 = {
  labels: labels4,
  datasets: [
    {
       label: 'Cantidad de Usuarios',
      data: datos4,
      borderColor: '#ff0000',
      backgroundColor: "#FF7F55",
      borderWidth: 2,
      borderRadius: 3,
      borderSkipped: false,
	  fill: true,
	  cubicInterpolationMode: 'monotone',
    }
  ]
};
const config4 = {
  type: 'line',
  data: data4,
  options: {
    responsive: true,
	scales: {
	  x: {
		grid: {
		  display: false
		}
	  },
	  y: {
		grid: {
		  display: false
		}
	  }
	},
    plugins: {
      legend: {
		display: false,
        position: 'top',
      },
      title: {
        display: false,
        text: ''
      }
    }
  },
};

const ctx4 = document.getElementById('myChart4');
const myChart4 = new Chart(ctx4, config4);

function traecolor() {
  let color;
  do {
    color = "#" + Math.floor(Math.random()*16777215).toString(16);
  } while (usedColors.includes(color));
  usedColors.push(color);
  if (usedColors.length > 6) {
    usedColors.shift();
  }
  return color;
}



function traecolorArr(aa) {
  var colors = [
  "#FF5733",
  "#009999",
  "#FFC300",
  "#8B008B",
  "#00FF7F",
  "#FF1493",
  "#6495ED",
  "#FF8C00",
  "#9400D3",
  "#00FF00",
  "#FF00FF",
  "#00FFFF",
  "#FFD700"
  ]
  
  return colors[aa];
}

function acnum(number) {
  return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}


	</script>
</body>

</html>
