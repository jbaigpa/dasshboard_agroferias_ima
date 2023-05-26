<?php
include('services/security.php');
include('services/connection.php');

$id = $_REQUEST['id'];
$sqlGetUser = "select * from ".$tbl." where md5(id)='$id';";
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

    <title>PanaVac - Módulo para ver usuario</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <link rel="stylesheet" href="css/all.min.css">
    <link rel="stylesheet" href="css/adminlte.min.css">

    <link rel="icon" type="image/png" href="../img/favicon.png">


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

        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <a href="../../index3.html" class="brand-link logo-switch" style="height: 140px;">
                <img style="width: 120px;margin-left: 20%;" src="../img/logoWhite.png" alt="PANAMÁ VACUCHECK" class="logo-xl">
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
                                    Usuarios
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
                                        <i class="nav-icon fas fa-users"></i>
                                        <p>Crear Usuario</p>
                                    </a>
                                </li>
                                
								<li class="nav-item">
                                    <a href="Auditoria.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Auditoria</p>
                                    </a>
                                </li>
								
								<li class="nav-item <?php if($_GET['id'] == md5($_SESSION['userid'])){ echo "item-active";} ?>">
                                    <a href="editUser.php?id=<?php echo md5($_SESSION['userid']);?>" class="nav-link">
                                        <i class="far fa-user nav-icon"></i>
                                        <p>Mi Perfil</p>
                                    </a>
                                </li>
								
								<li class="nav-item">
                                    <a href="deletedUsers.php" class="nav-link">
                                        <i class="fa fa-ban nav-icon"></i>
                                        <p>Usuarios Eliminados</p>
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
                            <h1>Editar Usuario</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="adminUsers.php">Administración de usuarios</a></li>
                                <li class="breadcrumb-item active">Editar usuario</li>
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
                                            <input value="<?php echo $rowGetUser['nombre'] ?>" tabindex="1" type="text" class="form-control" id="nombrefull" placeholder="Nombre de usuario" disabled>
                                        </div>
                                        
										<div class="form-group">
                                            <label for="cedulaResponsable">* Cédula del responsable:</label>
                                            <input value="<?php echo $rowGetUser['cedula_responsable'] ?>" tabindex="8" type="text" class="form-control" id="cedulaResponsable" placeholder="Formato de cédula valido." disabled>
                                        </div>
										
										<div class="form-group">
                                            <label for="email">Email:</label>
                                            <input value="<?php echo $rowGetUser['email'] ?>" tabindex="7" type="text" class="form-control" id="email" placeholder="Ingrese el email" disabled>
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
                                            <input value="<?php echo $rowGetUser['fuente'] ?>" tabindex="4" type="text" class="form-control" id="fuente" placeholder="Fuente del usuario, por ejemplo: MINSA, AIG, PRESIDENCIA." disabled>
                                        </div>
                                        <div class="form-group">
                                            <label for="telefono">* Teléfono:</label>
                                            <input value="<?php echo $rowGetUser['telefono'] ?>" tabindex="5" type="text" class="form-control" id="telefono" placeholder="Celular o de trabajo" disabled>
                                        </div>
                                        <div class="form-group">
                                            <label for="provincia">* Provincia:</label>
                                            <select tabindex="6" name="provincia" id="provincia" class="form-control" disabled >
                                                <option value=""></option>
                                                <option <?php  if($rowGetUser['provincia']=='BOCAS DEL TORO'){echo "selected";} ?> value="BOCAS DEL TORO">BOCAS DEL TORO</option>
                                                <option <?php  if($rowGetUser['provincia']=='CHIRIQUÍ'){echo "selected";} ?> value="CHIRIQUÍ">CHIRIQUÍ</option>
                                                <option <?php  if($rowGetUser['provincia']=='COCLÉ'){echo "selected";} ?> value="COCLÉ">COCLÉ</option>
                                                <option <?php  if($rowGetUser['provincia']=='COLÓN'){echo "selected";} ?> value="COLÓN">COLÓN</option>
                                                <option <?php  if($rowGetUser['provincia']=='COMARCA GUNA YALA'){echo "selected";} ?> value="COMARCA GUNA YALA">COMARCA GUNA YALA</option>
                                                <option <?php  if($rowGetUser['provincia']=='COMARCA NGÄBE BUGLÉ'){echo "selected";} ?> value="COMARCA NGÄBE BUGLÉ">COMARCA NGÄBE BUGLÉ</option>
                                                <option <?php  if($rowGetUser['provincia']=='DARIEN'){echo "selected";} ?> value="DARIEN">DARIEN</option>
                                                <option <?php  if($rowGetUser['provincia']=='EMBERA WOUNAAN'){echo "selected";} ?> value="EMBERA WOUNAAN">EMBERA WOUNAAN </option>
                                                <option <?php  if($rowGetUser['provincia']=='HERRERA'){echo "selected";} ?> value="HERRERA">HERRERA</option>
                                                <option <?php  if($rowGetUser['provincia']=='LOS SANTOS'){echo "selected";} ?> value="LOS SANTOS">LOS SANTOS</option>
                                                <option <?php  if($rowGetUser['provincia']=='PANAMÁ'){echo "selected";} ?> value="PANAMÁ">PANAMÁ</option>
                                                <option <?php if($rowGetUser['provincia']=='PANAMÁ OESTE'){echo "selected";} ?>  value="PANAMÁ OESTE">PANAMÁ OESTE</option>
                                                <option <?php if($rowGetUser['provincia']=='VERAGUAS'){echo "selected";} ?> value="VERAGUAS">VERAGUAS</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="nivel">* Nivel:</label>
                                            <select tabindex="9" name="nivel" id="nivel" class="form-control" disabled>
                                                <option value=""></option>
                                                <option <?php  if($rowGetUser['nivel']=='1'){echo "selected";} ?> value="1">Comercios y otros aun no definidos</option>
                                                <option <?php  if($rowGetUser['nivel']=='2'){echo "selected";} ?> value="2">Gobierno</option>
												<option <?php  if($rowGetUser['nivel']=='3'){echo "selected";} ?> value="3">Seguridad Nacional</option>
												<option <?php  if($rowGetUser['nivel']=='500'){echo "selected";} ?> value="500">Auditor (Solo Lectura)</option>
												<option <?php  if($rowGetUser['nivel']=='1000'){echo "selected";} ?> value="1000">Super Administrador</option>
                                            </select>
                                        </div>
										
										<div class="form-group">
                                            <label for="apps">* Aplicaciones Autorizadas:</label>
                                            
											<div class="custom-control custom-checkbox">
											<input type="checkbox" class="custom-control-input" id="apps-panavac-normal"
											<?php  if($rowGetUser['panavac_normal']==1){echo"checked";} ?> disabled>
											<label class="custom-control-label" for="apps-panavac-normal">PanaVac - Normal
											</label>
											</div>
											
											<div class="custom-control custom-checkbox">
											<input type="checkbox" class="custom-control-input" id="apps-panavac-xt"
											<?php  if($rowGetUser['panavac_xt']==1){echo"checked";} ?> disabled>
											<label class="custom-control-label" for="apps-panavac-xt">PanaVac - XT</label>
											</div>
											
											<div class="custom-control custom-checkbox">
											<input type="checkbox" class="custom-control-input" id="apps-vacucheck"
											<?php  if($rowGetUser['vacucheck']==1){echo"checked";} ?> disabled>
											<label class="custom-control-label" for="apps-vacucheck">VacuCheck</label>
											</div>
											
										</div>
										
                                    </div><!-- /.card-body -->
								</form>
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
                <b>Version</b> 1.0.5
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
    <script src="https://www.google.com/recaptcha/api.js?render=6LeiVcYaAAAAAJdD1cpDx2jP8PSGokBgnG9hKDob"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="js/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="js/admin_lte/adminlte.min.js"></script>
    <script src="js/functions.js"></script>
    <script src="js/editUser.js?v=1.0.5"></script>


</body>

</html>