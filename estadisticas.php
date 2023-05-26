<?php
date_default_timezone_set('America/Panama');
include('services/security.php');
include('services/connection.php');

$sqlGetProvincias = "SELECT count(*) as cantidad_lugar, `lugar_compra` FROM `ventas` GROUP BY `lugar_compra`;";
$resGetProvincias = mysqli_query($con, $sqlGetProvincias);

$sqlGetTipo= "SELECT count(*) as cantidadtipo, `producto` FROM `ventas` GROUP BY `producto`;";
$resGetTipo= mysqli_query($con, $sqlGetTipo);

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
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="reportes.php" class="nav-link">Reportes</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="mapa.php" class="nav-link">Mapa</a>
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
					<div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                Ventas por Sitio
                            </div>
                        </div>
                        <div class="card-body">
                        <canvas id="myChart1" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block;" class="chartjs-render-monitor" width="678" height="375"></canvas>
						</div>
                    </div>
				  </div>
				  
				 <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                Ventas por Producto
                            </div>
                        </div>
                        <div class="card-body">
							<canvas id="myChart2" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block;" class="chartjs-render-monitor" width="678" height="375"></canvas>
						</div>
                    </div>
				  </div>
				  
				  </div>
				  
				  <div class="row">
					<div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                Ventas por Usuario
                            </div>
                        </div>
                        <div class="card-body">
							<canvas id="myChart3" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block;" class="chartjs-render-monitor" width="678" height="375"></canvas>
						</div>
                    </div>
				  </div>
				  
				 <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                Intentos Fallidos por Sitio
                            </div>
                        </div>
                        <div class="card-body">
                        <canvas id="myChart4" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block;" class="chartjs-render-monitor" width="678" height="375"></canvas>
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
   <?php while ($rowGetProvincias = mysqli_fetch_assoc($resGetProvincias)) {?>
	datos1.push(<?php echo $rowGetProvincias['cantidad_lugar']; ?>);
	labels1.push('<?php echo ucwords(strtolower($rowGetProvincias['lugar_compra'])); ?>');
	colors1.push(traecolor());
	<?php	}?>
	
	<?php while ($rowGetTipo = mysqli_fetch_assoc($resGetTipo)) {?>
	datos2.push(<?php echo $rowGetTipo['cantidadtipo']; ?>);
	labels2.push('<?php echo ucwords(strtolower($rowGetTipo['producto'])); ?>');
	colors2.push(traecolor());
	<?php	} /* ?>
	
	

	
	// Solo es 1 valor - los NO LEIDOS o que no aparecen en el historial
	datos3.push(<?php echo $rowGetNoLeidos['cantidadnoleido']; ?>);
	labels3.push('No Leídos');
	colors3.push("#AAAAAA");
	
	<?php while ($rowGetUserProv = mysqli_fetch_assoc($resGetUserProv)) {?>
	datos4.push(<?php echo $rowGetUserProv['cantuserprov']; ?>);
	labels4.push('<?php echo ucwords(strtolower($rowGetUserProv['provincia'])); ?>');
	colors4.push(traecolor())
	<?php	} */ ?>
	

	const data1 = {
  labels: labels1,
  datasets: [
    {
      label: 'Cantidad de Reportes',
      data: datos1,
      /*borderColor: '#000ff',*/
      backgroundColor: colors1,
      borderWidth: 2,
      borderRadius: 3,
      borderSkipped: false,
    }
  ]
};

const config1 = {
  type: 'bar',
  data: data1,
  options: {
    responsive: true,
    plugins: {
      legend: {
        position: 'top',
      },
      title: {
        display: false,
        text: ''
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
        position: 'top',
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
       label: 'Cantidad de Reportes',
      data: datos3,
      borderColor: '#666666',
      backgroundColor: colors3,
      borderWidth: 2,
      borderRadius: 3,
      borderSkipped: false,
    }
  ]
};
const config3 = {
  type: 'doughnut',
  data: data3,
  options: {
    responsive: true,
	
    plugins: {
      legend: {
		display: true,
        position: 'left',
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

function traecolor(){
	var res = "#FFFFFF";
    var color = Math.floor((Math.random()*1000000)+1);

    res = "#" + ("000000" + color.toString(16)).slice(-6);
	return res;
}

	</script>
</body>

</html>
