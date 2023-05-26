<?php
include('services/security.php');
include('services/connection.php');

$islogin = 0;
if($_SESSION['level'] == 800 || $_SESSION['level'] == 1000){
	$islogin = 1;
}

if($islogin == 0){
	exit("Error Level");
}

$id = $_REQUEST['id'];
$sqlGetUser = "select * from admin_users where md5(admin_id)='$id';";
#exit($sqlGetUser);
$resGetUser = mysqli_query($con, $sqlGetUser);
$numOfUser = mysqli_num_rows($resGetUser);

if ($numOfUser == 0) {
    header('location: services/close.php');
}

$rowGetUser = mysqli_fetch_assoc($resGetUser);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>SINAPROC - Módulo para editar Administradores</title>
	<?php
		include("services/header.php");
		$active['profile'] = "active";
	?>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

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
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="newUser.php" class="nav-link">Registrar nuevo usuario</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="adminUsers.php" class="nav-link">Administrar usuarios</a>
                </li>
            </ul>
        </nav>
		
			<?php include("services/menu.php"); ?>

            <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Editar Administrador</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="adminUsers.php">Gestión de Administradores</a></li>
                                <li class="breadcrumb-item active">Editar Administrador</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">

                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card card-primary">
                                <!--<div class="card-header">
                                    <h3 class="card-title">Datos personales del usuario</h3>
                                </div>-->
                                <!-- /.card-header -->
                                <!-- form start -->
                                <form>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="nombrefull">* Nombre:</label>
                                            <input value="<?php echo $rowGetUser['name'] ?>" tabindex="1" type="text" class="form-control" id="nombrefull" placeholder="Nombre de usuario">
                                        </div>
                                        
										<div class="form-group">
                                            <label for="cedula">* Cédula:</label>
                                            <input value="<?php echo $rowGetUser['cedula'] ?>" tabindex="8" type="text" class="form-control" id="cedula" placeholder="Formato de cédula valido.">
                                        </div>
										
										<div class="form-group">
                                            <label for="email">Email:</label>
                                            <input value="<?php echo $rowGetUser['email'] ?>" tabindex="7" type="text" class="form-control" id="email" placeholder="Ingrese el email">
                                        </div>
										
										<div class="form-group">
                                            <label for="password">* Password:</label>
                                            <input tabindex="2" type="password" class="form-control" id="password" placeholder="Password">
                                        </div>
                                        <div class="form-group">
                                            <label for="password-2">* Confirmar Password:</label>
                                            <input tabindex="3" type="password" class="form-control" id="password-2" placeholder="Confirmar Password">
                                            <small class="text-muted">Si no desea cambiar el password lo debe dejar en blanco.</small>
                                        </div>
                                    
									
									
									</div><!-- /.card-body -->
									
									 </form>
								</div>
							</div><!-- End Columna -->
							
							<div class="col-md-6">
								<div class="card card-primary">
								<form>
									<div class="card-body">
										<div class="form-group">
                                            <label for="fuente">* Fuente:</label>
                                            <input value="<?php echo $rowGetUser['institucion'] ?>" tabindex="4" type="text" class="form-control" id="institucion" placeholder="Fuente del usuario, por ejemplo: SINAPROC u otra entidad.">
                                        </div>
                                        <div class="form-group">
                                            <label for="telefono">* Teléfono:</label>
                                            <input value="<?php echo $rowGetUser['telefono'] ?>" tabindex="5" type="text" class="form-control" id="telefono" placeholder="Celular o de trabajo">
                                        </div>
									   
										
									<?php include('services/select_permisos.php'); ?>
										
                                    </div><!-- /.card-body -->
									
									<div class="card-footer">
                                        <input type="hidden" id="admin_id" value="<?php echo $_REQUEST['id']; ?>">
                                        <button tabindex="10" id="btn-edit-user" type="submit" class="btn text-white" style="background-color: #3B82F6;">
                                            <center><img id="loader" style="display: none;" src="img/loader.gif" /></center>
                                            <span id="lbl"><b>SALVAR</b></span>
                                        </button>

                                        <br />
                                        <small class="text-muted">* Campos obligatorios</small>
                                    </div>
								
                                </div>
							</div>
									

                                   
                                
                            </div><!-- END ROW -->
                        </div><!-- END CONTAINER -->
                   
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <footer class="main-footer">
            <div class="d-none d-sm-block">
                <b>SINAPROC DASH</b> <?php echo $versionDash; ?>
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
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="js/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="js/admin_lte/adminlte.min.js"></script>
	<script src="js/cripto-js.min.js""></script>
    <script src="js/functions.js"></script>
    <script src="js/editUserAdmin.js?v=1.1.10"></script>



</body>

</html>