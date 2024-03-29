<?php

include('services/security.php');
include('services/connection.php');

$sqlGetReports = "SELECT * FROM `mensajes_avisos`";
$resGetReports = mysqli_query($con, $sqlGetReports);
$numOfReports = mysqli_num_rows($resGetReports);

$islogin = 0;
if($_SESSION['level'] >= 90){ // 90 Operadores ASEP y Otros de Monitoreo
	$islogin = 1;
}

if($islogin == 0){
	exit("Usted no tiene permisos para este modulo");
}


?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Lista de Avisos - IMA AGROFERIAS</title>

	<?php
		include("services/header.php");
		$active['lista_avisos'] = "active";
	?>
	<style>
		.wrapper .card-body {
		  padding: 0.7rem 1rem;
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
                    <a href="ingresarAviso.php?id=0" class="nav-link"><i class="fa fa-plus"></i> Agregar Nuevo Aviso</a>
                </li>
            </ul>
        </nav>
		<?php
					include("services/menu.php");
				?>
       

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
           
		   

            <!-- Main content -->
            <section class="content">

                <div class="container-fluid">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                Cantidad de Avisos: <?php echo $numOfReports; ?>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="dataTables_wrapper dt-bootstrap4">
                                <div class="table-responsive">
                                    <table id="reportes" class="table table-hover table-condensed table-striped">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                               	<th>Titulo</th>
												<th>Duración</th>
                                                <th>Activo</th>
												<th>Acciones</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($rowGetReports = mysqli_fetch_assoc($resGetReports)) { 
												$activo = "si";
												if($rowGetReports['activo'] == 0){
													$activo = "no";
												}
											?>
                                                <tr>
                                                    <td style="width: 5%;"><?php echo $rowGetReports['aviso_id'] ?></td>
                                                    <td style="width: 20%;"><?php echo $rowGetReports['aviso_title'] ?></td>
													<td style="width: 20%;"><?php echo $rowGetReports['duracion'] ?></td>
                                                    <td style="width: 5%;"><?php echo $activo ?></td>
													<td style="width: 5%;"><a href="ingresarAviso.php?id=<?php echo $rowGetReports['aviso_id'] ?>"><i class="fa fa-edit" title="Editar"></i></a></td>
													
												</tr>
                                            <?php } ?>
                                        </tbody>
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
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
   <!-- <script src="https://www.google.com/recaptcha/api.js?render=6LeiVcYaAAAAAJdD1cpDx2jP8PSGokBgnG9hKDob"></script>-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="js/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="js/admin_lte/adminlte.min.js"></script>
    <script src="js/avisos.js"></script>
	
	<script>
	$(document).ready(function () {
    $('#reportes').DataTable(
        {
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
            },
            "bLengthChange" : false,
			order: [[3, 'desc']],
        }
    );
});

	</script>
</body>

</html>
