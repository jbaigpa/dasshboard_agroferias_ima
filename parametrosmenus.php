<?php

include('services/security.php');
include('services/connection.php');
#--- var_dump($_SESSION);
$sqlGetParams = "SELECT * FROM `config` WHERE type = 'cientificweb'";
$resGetParams = mysqli_query($con, $sqlGetParams);
$numOfParams = mysqli_num_rows($resGetParams);

$islogin = 0;
if($_SESSION['level'] == 800 || $_SESSION['level'] == 1000){
	$islogin = 1;
}

if($islogin == 0){
	exit("No tiene permisos para este recurso. Contactar a administrador");
}


?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Parametros - SINAPROC</title>

	<?php
		include("services/header.php");
		$active['parametrosmenus'] = "active";
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
                   <a href="#" class="nav-link"><i class="fa fa-cog"></i> Parámetros de Configuración del App</a>
                </li>
            </ul>
        </nav>
		<?php include("services/menu.php"); ?>
       

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
             <!--<section class="content-header">-->
               <!-- <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="reportes.php">Reportes de Incidencias o Emergencias</a></li>
                            </ol>
                        </div>
                    </div>
                </div>--><!-- /.container-fluid -->
            <!--</section>-->

            <!-- Main content -->
            <section class="content">

                <div class="container-fluid">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                Cantidad de Parámetros: <?php echo $numOfParams; ?>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="dataTables_wrapper dt-bootstrap4">
                                <div class="table-responsive">
                                    <table id="reportes" class="table table-hover table-condensed table-striped">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                               	<th>Nombre</th>
												<th>Parametros</th>
												<th>Tipo</th>
												<th>Activo</th>
                                                <th>Acciones</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($rowGetParams = mysqli_fetch_assoc($resGetParams)) { ?>
                                                <tr>
                                                    <td style="width: 5%;"><?php echo $rowGetParams['config_id'] ?></td>
                                                    <td style="width: 20%;"><?php echo $rowGetParams['name'] ?></td>
													<td style="width: 20%;"><?php echo $rowGetParams['parameters'] ?></td>
													<td style="width: 20%;"><?php echo $rowGetParams['type'] ?></td>
                                                    <td style="width: 5%;"><?php echo $rowGetParams['active'] ?></td>
													<td style="width: 5%;"><a href="editarParams.php?config=2&id=<?php echo $rowGetParams['config_id'] ?>"><i class="fa fa-edit" title="Editar"></i></a></td>
													
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
    <script src="js/reportes.js"></script>
	
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
