<?php
date_default_timezone_set('America/Panama');
include('services/security.php');
include('services/connection.php');

if(isset($_GET["dia"])){
	$hoy = date('Y-m-d');
	$ventasmenu = 'ventasdia';
	$titledia = "Hoy";
	$urlservice = "services/reportesService.php?dia=1";
	$sqlGetReports = "SELECT t1.id, t1.cedula, t1.producto, t1.lugar_compra, t1.fecha_compra, t1.precio, t1.vendedor, t1.device, t2.nombre as vendedor_nombre, t1.supervisor FROM ventas as t1 JOIN operadores as t2 on t1.vendedor = t2.cedula WHERE t1.fecha_compra = '$hoy' ORDER BY t1.fecha_compra DESC;";
}else{
	#total
	$ventasmenu = 'ventastotal';
	$titledia = "Total";
	$urlservice = "services/reportesService.php";
	$sqlGetReports = "SELECT t1.id, t1.cedula, t1.producto, t1.lugar_compra, t1.fecha_compra, t1.precio, t1.vendedor, t1.device, t2.nombre as vendedor_nombre, t1.supervisor FROM ventas as t1 JOIN operadores as t2 on t1.vendedor = t2.cedula ORDER BY t1.fecha_compra DESC;";

}
$resGetReports = mysqli_query($con, $sqlGetReports);
$numOfReports = mysqli_num_rows($resGetReports);

$ventatotal = 0;
while ($rowGetReports = mysqli_fetch_assoc($resGetReports)) { 
	$ventatotal = $ventatotal + $rowGetReports['precio'];
}

$resGetReports = mysqli_query($con, $sqlGetReports);

$estatus = array(0=>"No Leido",5=>"Verificando",10=>"Atendido",99=>"Eliminado/Cancelado");

$islogin = 0;
if( $_SESSION['level'] >= 500){
	$islogin = 1;
}elseif($_SESSION['level'] == 90){
	header('Location: lista_alertas.php');
	die();
}elseif($_SESSION['level'] == 100){
	header('Location: estadisticas.php');
	die();
}elseif($_SESSION['level'] == 300){
	header('Location: lista_alertas.php');
	die();
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

    <title>Reportes - AgroFerias IMA</title>

	<?php
		include("services/header.php");
		$active[$ventasmenu] = "active";
	?>
	<style>
		.wrapper .card-body {
		  padding: 0.7rem 1rem;
		}
	</style>

</head>

<body class="hold-transition sidebar-mini">
	<div class="superloader">
		<div class="superloader-load">
			<img src="img/loader.gif" />
		</div>
	</div>
    <!-- Site wrapper -->
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
				
				<li class="nav-item" id="areaexport">
                    
                </li>
                <!--
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="mapa.php" class="nav-link">Mapa de Situación</a>
                </li>
				-->
				<!--<li class="nav-item d-none d-sm-inline-block">
                    <a href="CrearReporte.php" class="nav-link"><i class="fa fa-plus"></i> Agregar Reporte Manualmente</a>
                </li>-->
            </ul>
        </nav>
		<?php
					include("services/menu.php");
				?>
       

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
             

            <!-- Main content -->
            <section class="content">

                <div class="container-fluid">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                Ventas <?php echo $titledia; ?>: <span id="numofrepo"><?php echo $numOfReports." compradores | Ingresos: B/.".number_format($ventatotal, 2, '.', ',').")"; ?></span>
                            </div>
                        </div>
						<div id="filtros"></div>
                        <div class="card-body">
                            <div class="dataTables_wrapper dt-bootstrap4">
                                <div class="table-responsive">
                                    <table id="reportes" class="table table-hover table-condensed table-striped">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                               	<th>Cedula</th>
												<th>Lugar</th>
												<th>Fecha Compra</th>
                                                <th>Usuario</th>
												<th>Device</th>
												<th>Producto</th>
												<th>Precio</th>
												<th>Supervisor</th>
												<th>Accion</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
											<?php  while ($rowGetReports = mysqli_fetch_assoc($resGetReports)) { 
											
											?>
                                                <tr>
                                                    <td><?php echo $rowGetReports['id'] ?></td>
                                                    <td><?php echo $rowGetReports['cedula'] ?></td>
													<td><?php echo $rowGetReports['lugar_compra'] ?></td>
													<td><?php echo $rowGetReports['fecha_compra'] ?></td>
													<td><?php echo $rowGetReports['vendedor_nombre']. " (".$rowGetReports['vendedor'].")" ?></td>
													<td><?php echo $rowGetReports['device'] ?></td>
                                                    <td><?php echo ucwords(strtolower($rowGetReports['producto'])); ?></td>
													<td><?php echo "B/.".number_format($rowGetReports['precio'], 2, '.', ','); ?></td> 
													<td><?php echo $rowGetReports['supervisor']; ?></td> 
                                                    <td>
													<a href="verReporte.php?id=<?php echo $rowGetReports['id']; ?>" class="btn btn-primary btn-sm" title="Ver"><i class="fas fa-eye"></i></a>
													</td>
												</tr>
                                            <?php } ?>
                                        </tbody>
										<tfoot>
											<tr>
												 <th>ID</th>
                                               	<th>Cedula</th>
												<th>Lugar</th>
												<th>Fecha Compra</th>
                                                <th>Usuario</th>
												<th>Device</th>
												<th>Producto</th>
												<th>Precio</th>
												<th>Supervisor</th>
												<th>Accion</th>
											</tr>
										</tfoot>
                                    </table>
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
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
	<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
	
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
	
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="js/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="js/admin_lte/adminlte.min.js"></script>
   <!-- <script src="js/reportes.js"></script>-->
	
	<script>
	var delta = 12; // Horas default
	var mytabla;
	var ciclos = -1;
	var ciclosrunning =0;
	$(document).ready(function () {
    
	creatableau();
	
	//refrescaData(delta);
	
	ciclos = setInterval(function () {
		if(ciclosrunning == 0){
			refrescaData(delta);
			console.log("Refrescando...")
		}
		
		}, 90000);
	
});

function refrescaData(delta){
	$(".superloader").fadeIn();
	var form_data = {'keynote':'sjdksjiUIdhywwdmnwhjUEHiE083890kws2989sdj8282','delta':delta};
	ciclosrunning = 1;
	$.ajax({
		url: '<?php echo $urlservice; ?>',
	    method:"POST",
		data: form_data,
		dataType: 'json',
	   beforeSend : function()
	   {
		//$("#preview").fadeOut();
	   },
	   success: function(res){
		   ciclosrunning = 0;
		   //console.log(res);
			$(".superloader").fadeOut();
			if(res=='invalid'){
			  console.log("Error 001")
			}else{
			 mytabla.destroy();
			 var tablin = $('#reportes tbody');
			 tablin.empty();
			 var myheader = "<tr>";
	
			 myheader = myheader + "<th>ID</th>";
             myheader = myheader + "<th>Cedula</th>";
			 myheader = myheader + "<th>Lugar</th>";
             myheader = myheader + "<th>Fecha Compra</th>";
			 myheader = myheader + "<th>Usuario</th>";
			 myheader = myheader + "<th>Device</th>";
             myheader = myheader + "<th>Producto</th>";
			 myheader = myheader + "<th>Precio</th>";
			 myheader = myheader + "<th>Supervisor</th>";
			 myheader = myheader + "<th>Accion</th>";
             myheader = myheader + "</tr>";
             $('#reportes').find('thead').html(myheader);
			
			 var precio = 0;
			 var numOfReports = 0;
			 $.each(res.data, function (a, b) {
                tablin.append("<tr><td>"+b[0]+"</td>" +
                    "<td>"+b[1]+"</td>"+
					"<td>"+b[2]+"</td>"+
					"<td>"+b[3]+"</td>"+
					"<td>"+b[4]+"</td>"+
					"<td>"+b[6]+"</td>"+
					"<td>"+b[5]+"</td>"+
					"<td>"+b[9]+"</td>"+
					"<td>"+b[7]+"</td>"+
					"<td>"+b[8]+"</td>");
					numOfReports = numOfReports + 1;
					precio = precio + Number(b[9]);
					
            });
			//var priceformat = precio.toFixed(2);
			var priceformat = precio.toLocaleString('en-US');
			$("#numofrepo").html(numOfReports+" compradores | Ingresos: B/."+priceformat);
			
			creatableau();
			}
		 },
		 error: function(e){
			$(".superloader").fadeOut();
			console.log("Error Ajax:");
			console.log(e);
			ciclosrunning = 0;
		  }          
		});
}

function creatableau(){
	mytabla = $('#reportes').DataTable(
			{
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
            },
            "bLengthChange" : false,
			order: [[0, 'desc']],
			dom: 'Bfrtip',
			buttons: [
				'csv', 'excel'
			],
			//
			initComplete: function () {
				var thecolums = this.api().column([2, 4]);
				
					thecolums.every(function () {
						var column = this;
						var select = $('<select style="width: 140px;"><option value="">- LUGAR - </option></select>')
							.appendTo($(column.header()).empty())
							.on('change', function () {
								var val = $.fn.dataTable.util.escapeRegex($(this).val());
	 
								column.search(val ? '^' + val + '$' : '', true, false).draw();
							});
	 
						column
							.data()
							.unique()
							.sort()
							.each(function (d, j) {
								select.append('<option value="' + d + '">' + d + '</option>');
							});
					});
			},
			//
			
        });
	
		mytabla.buttons().container().appendTo('#reportes_wrapper .dataTables_filter');
		
}
//
	</script>
</body>

</html>
