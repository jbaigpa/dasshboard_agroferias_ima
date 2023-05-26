<?php
error_reporting(0);
include('services/security.php');
include('services/connection.php');
$title['createadm'] = 'Crear Usuario del APP';
$lainstitucion = 'AGROFERIAS IMA';


$islogin = 0;
if($_SESSION['level'] == 800 || $_SESSION['level'] == 1000){
	$islogin = 1;
}

if($islogin == 0){
	exit("Error Level");
}

$sqlGetProvincias = "SELECT * FROM `provincia`";
$resGetProvincias = mysqli_query($con, $sqlGetProvincias);

$sqlGetProductos = "SELECT * FROM `productos`";
$resGetProductos = mysqli_query($con, $sqlGetProductos);


?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo $lainstitucion; ?> - <?php echo $title['createadm']; ?></title>

	<?php
		include("services/header.php");
	
		$active['createuserapp'] = "active"; 

	?>

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
                    <a href="appUsers.php" class="nav-link">Administrar usuarios del App</a>
                </li>
            </ul>
        </nav>

       <?php
					include("services/menu.php");
				?>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header" style="padding: 10px;height: 50px;">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <p style="font-size:16px;font-weight:700;">Registrar nuevo usuario del App</p>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="appUsers.php">Usuarios del App</a></li>
                                <li class="breadcrumb-item active">Registrar nuevo usuario del APP</li>
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
                                
                                <!-- /.card-header -->
                                <!-- form start -->
                                <form>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="nombre">* Nombre de Agroferia:</label>
                                            <input tabindex="1" type="text" class="form-control" id="nombrefull" placeholder="Nombre Completo">
                                        </div>
										
										<div class="form-group">
                                            <label for="tipotienda">* Tipo de Tienda:</label>
                                            <select name="tipotienda" id="tipotienda" class="form-control">
												<option value="NO_SELECTED">seleccione</option>
												<option value="agroferia">Agroferia</option>
												<option value="agrodistribuidora">AgroDistribuidora</option>
											</select>
                                        </div>
										
										<div class="form-group">
                                            <label for="codigo">* Codigo de Usuario:</label>
                                            <input tabindex="8" type="text" class="form-control" id="codigo" name="codigo"placeholder="Codigo de usuario/tienda.">
                                        </div>
										
										
										<div class="form-group">
                                            <label for="provincia">* Provincia:</label>
                                            <select name="provincia" id="provincia" class="form-control">
												<option value="NO_SELECTED">seleccione</option>
												<?php while ($rowGetProvincias = mysqli_fetch_assoc($resGetProvincias)) { ?>
														<option value="<?php echo $rowGetProvincias['codigo'] ?>"><?php echo $rowGetProvincias['nombre'] ?></option>
												<?php } ?>
											</select>
                                        </div>
										
										<div class="form-group">
                                            <label for="fuente">* Ubicación:</label>
                                            <input tabindex="4" type="text" class="form-control" id="ubicacion" placeholder="Dirección corta">
                                        </div>
										
                                    </div>
                                    <!-- /.card-body -->

                                </form>
                            </div>
                        </div>
						
						<div class="col-sm-6">
							<div class="card card-primary">
								<div class="card-body">
								
										<div class="form-group">
                                            <label for="producto">* Producto:</label>
                                             <select name="producto" id="producto" class="form-control">
												<option value="NO_SELECTED">seleccione</option>
												<?php while ($rowGetProductos = mysqli_fetch_assoc($resGetProductos)) { ?>
														<option value="<?php echo $rowGetProductos['codigo'] ?>"><?php echo $rowGetProductos['nombre'] ?></option>
												<?php } ?>
											</select>
                                        </div>
										
										
										<div class="form-group">
                                            <label for="password">* Password:</label>
                                            <input tabindex="3" type="password" class="form-control" id="password" placeholder="Password">
                                        </div>
                                        <div class="form-group">
                                            <label for="password-2">* Confirmar Password:</label>
                                            <input tabindex="4" type="password" class="form-control" id="password-2" placeholder="Confirmar Password">
                                        </div>
                                        
										
										
                                		
										
									
								</div>
								
								<div class="card-footer">
                                        <button tabindex="10" id="btn-add-new-user" type="submit" class="btn text-white" style="background-color: #3B82F6;">
                                            <center><img id="loader" style="display: none;" src="img/loader.gif" /></center>
                                            <span id="lbl"><b>Guardar</b></span>
                                        </button>

                                        <br />
                                        <small class="text-muted">* Campos obligatorios</small>
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
                <b>IMA DASH</b> <?php echo $versionDash; ?>
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
    <!--<script src="https://www.google.com/recaptcha/api.js?render=6LeiVcYaAAAAAJdD1cpDx2jP8PSGokBgnG9hKDob"></script>-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="js/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="js/admin_lte/adminlte.min.js"></script>
	<script src="js/cripto-js.min.js""></script>
    <script src="js/functions.js"></script>
    <script src="js/newUserApp.js"></script>

</body>

</html>