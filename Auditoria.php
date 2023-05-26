<?php

include('services/security.php');
include('services/connection.php');

$sqlGetLogs = "SELECT `id`, `cedula`, `fecha_ingreso`, `control`, `notificado`, `curso_cod`, `instructor_ced`, `geolat`, `geolon`, `typescan` FROM `track_asistencia`;";
if(isset($_GET['id'])){
	$elid = $_GET['id'];
	$sqlGetLogs = "SELECT `id`, `cedula`, `fecha_ingreso`, `control`, `notificado`, `curso_cod`, `instructor_ced`, `geolat`, `geolon`, `typescan` FROM `track_asistencia` where md5(cedula) = '$elid' order by id desc;";
}
$resGetLogs = mysqli_query($con, $sqlGetLogs);
$numOfLogs = mysqli_num_rows($resGetLogs);


?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Asistencia | Asistencia INADEH</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">

    <link rel="stylesheet" href="css/all.min.css">
    <link rel="stylesheet" href="css/adminlte.min.css">

    <link rel="icon" type="image/png" href="img/favicon.png">


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
               <!-- <li class="nav-item d-none d-sm-inline-block">
                    <a href="newUser.php" class="nav-link">Registrar nuevo usuario</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="adminUsers.php" class="nav-link">Administrar usuarios</a>
                </li>-->
            </ul>
        </nav>

        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <a href="../../index3.html" class="brand-link logo-switch" style="height: 140px;">
                <img style="width: 120px;margin-left: 20%;" src="img/inadeh_asistencia.jpg" alt="INADEH" class="logo-xl">
            </a>


            <!-- Sidebar -->
            <div class="sidebar">

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                        <li class="nav-item  menu-is-opening menu-open">
                            <a href="#" class="nav-link active">
                                <i class="nav-icon fas fa-users"></i>
                                <p>
                                    Instructores
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                
								<li class="nav-item">
                                    <a href="adminUsers.php" class="nav-link">
                                        <i class="fa fa-list nav-icon"></i>
                                        <p>Administrar</p>
                                    </a>
                                </li>
								
								<li class="nav-item">
                                    <a href="newUser.php" class="nav-link">
                                        <i class="nav-icon fa fa-user-tie"></i>
                                        <p>Crear Instructor</p>
                                    </a>
                                </li>
                                
								<li class="nav-item item">
                                    <a href="adminEstudiantes.php" class="nav-link">
                                        <i class="fa fa-graduation-cap nav-icon"></i>
                                        <p>Estudiantes Activos</p>
                                    </a>
                                </li>
								
								<li class="nav-item item-active">
                                    <a href="Auditoria.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Auditoria</p>
                                    </a>
                                </li>
								
								<li class="nav-item">
                                    <a href="editUser.php?id=<?php echo md5($_SESSION['userid']);?>" class="nav-link">
                                        <i class="far fa-user nav-icon"></i>
                                        <p>Mi Perfil</p>
                                    </a>
                                </li>
								
								<li class="nav-item">
                                    <a href="deletedUsers.php" class="nav-link">
                                        <i class="fa fa-ban nav-icon"></i>
                                        <p>Instructors Eliminados</p>
                                    </a>
                                </li>

                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="services/close.php" class="nav-link">
                                <i class="nav-icon far fa-window-close"></i>
                                <p>
                                    Cerrar Sessión
                                </p>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Auditoria</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="Auditoria.php">Auditoria</a></li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">

                <div class="container-fluid">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                Número de Lineas: <?php echo $numOfLogs ?>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="dataTables_wrapper dt-bootstrap4">
                                <div class="table-responsive">
                                    <table id="users" class="table table-hover table-condensed table-striped">
                                        <thead>
                                            <tr>
                                                <th>Estudiante</th>
                                                <th>Cedula</th>
												<th>Fecha</th>
                                                <th>Curso Cod</th>
                                                <th>Curso Nombre</th>
                                                <th>Instructor</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($rowGetUsers = mysqli_fetch_assoc($resGetLogs)) { ?>
                                                <tr>
                                                    <td style="width: 15%;"><?php echo $rowGetUsers['cedula']; ?></td>
                                                    <td style="width: 10%;"><?php echo $rowGetUsers['cedula']; ?></td>
													<td style="width: 15%;"><?php echo $rowGetUsers['fecha_ingreso']; ?></td>
                                                    <td style="width: 15%;"><?php echo $rowGetUsers['curso_cod']; ?></td>
                                                    <td style="width: 10%;"><?php echo $rowGetUsers['curso_cod']; ?></td>
													<td style="width: 10%;"><?php echo $rowGetUsers['instructor_ced']; ?></td>
                                                    
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
                <b>Version</b> 1.5.0
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
    <script src="https://www.google.com/recaptcha/api.js?render=6LeiVcYaAAAAAJdD1cpDx2jP8PSGokBgnG9hKDob"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="js/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="js/admin_lte/adminlte.min.js"></script>
    <script src="js/auditoria.js"></script>

</body>

</html>
