<?php
include('services/security.php');
include('services/connection.php');

$id = $_REQUEST['id'];
$sqlGetUser = "select * from users where md5(id)='$id';";
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

    <title>Asistencia INADEH - Módulo para editar usuario</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <link rel="stylesheet" href="css/all.min.css">
    <link rel="stylesheet" href="css/adminlte.min.css">

    <link rel="icon" type="image/png" href="/img/favicon.png">


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
                            <h1>Editar Instructor</h1>
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
                                            <input value="<?php echo $rowGetUser['nombre'] ?>" tabindex="1" type="text" class="form-control" id="nombrefull" placeholder="Nombre de usuario">
                                        </div>
                                        
										<div class="form-group">
                                            <label for="cedulaResponsable">* Cédula del responsable:</label>
                                            <input value="<?php echo $rowGetUser['cedula_responsable'] ?>" tabindex="8" type="text" class="form-control" id="cedulaResponsable" placeholder="Formato de cédula valido.">
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
                                    
									<div class="card-footer">
                                        <input type="hidden" id="id" value="<?php echo $_REQUEST['id']; ?>">
                                        <button tabindex="10" id="btn-edit-user" type="submit" class="btn text-white" style="background-color: #3B82F6;">
                                            <center><img id="loader" style="display: none;" src="../img/loader.gif" /></center>
                                            <span id="lbl"><b>Editar</b></span>
                                        </button>

                                        <br />
                                        <br />
                                        <small class="text-muted">* Campos obligatorios</small>
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
                                            <input value="<?php echo $rowGetUser['fuente'] ?>" tabindex="4" type="text" class="form-control" id="fuente" placeholder="Fuente del usuario, por ejemplo: INADEH u otra entidad.">
                                        </div>
                                        <div class="form-group">
                                            <label for="telefono">* Teléfono:</label>
                                            <input value="<?php echo $rowGetUser['telefono'] ?>" tabindex="5" type="text" class="form-control" id="telefono" placeholder="Celular o de trabajo">
                                        </div>
                                        <div class="form-group">
                                            <label for="provincia">* Provincia:</label>
                                            <select tabindex="6" name="provincia" id="provincia" class="form-control">
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
                                            <select tabindex="9" name="nivel" id="nivel" class="form-control">
                                                <option value=""></option>
                                                <option <?php  if($rowGetUser['nivel']=='1'){echo "selected";} ?> value="1">Instructores</option>
                                                <option <?php  if($rowGetUser['nivel']=='2'){echo "selected";} ?> value="2">Coordinador</option>
												<option <?php  if($rowGetUser['nivel']=='3'){echo "selected";} ?> value="3">Supervisor</option>
												<option <?php  if($rowGetUser['nivel']=='500'){echo "selected";} ?> value="500">Auditor (Solo Lectura)</option>
												<option <?php  if($rowGetUser['nivel']=='1000'){echo "selected";} ?> value="1000">Super Administrador</option>
                                            </select>
                                        </div>
										
										<div class="form-group" style="display:none">
                                            <label for="apps">* Aplicaciones Autorizadas:</label>
                                            
											<div class="custom-control custom-checkbox">
											<input type="checkbox" class="custom-control-input" id="apps-panavac-normal"
											<?php  if($rowGetUser['panavac_normal']==1){echo"checked";} ?> >
											<label class="custom-control-label" for="apps-panavac-normal">PanaVac - Normal
											</label>
											</div>
											
											<div class="custom-control custom-checkbox">
											<input type="checkbox" class="custom-control-input" id="apps-panavac-xt"
											<?php  if($rowGetUser['panavac_xt']==1){echo"checked";} ?> >
											<label class="custom-control-label" for="apps-panavac-xt">PanaVac - XT</label>
											</div>
											
											<div class="custom-control custom-checkbox">
											<input type="checkbox" class="custom-control-input" id="apps-vacucheck"
											<?php  if($rowGetUser['vacucheck']==1){echo"checked";} ?> >
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