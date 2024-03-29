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
$configtype = $_REQUEST['config'];
$sqlGetUser = "select * from config where config_id='$id';";

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

    <title>IMA AGROFERIAS - Parametros</title>
	<?php
		include("services/header.php");
		if($configtype == 1){
			$active['parametros'] = "active";
		}else{
			$active['parametrosmenus'] = "active";
		}
		
	?>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <link rel="stylesheet" href="css/all.min.css">
    <link rel="stylesheet" href="css/adminlte.min.css">

    <link rel="icon" type="image/png" href="img/favicon.png">

	<style>
		.form-control.InValid {
		  border: 2px solid #f44;
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
                    <a href="parametros.php" class="nav-link">Ver todos los Parámetros</a>
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
                            <h1>Editar Parametros de Configuracion</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item">Parámetros</li>
                                <li class="breadcrumb-item active">Editar Parámetros</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">

                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-8">
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
                                            <input value="<?php echo $rowGetUser['name'] ?>" tabindex="1" type="text" class="form-control" id="nombreparametro" placeholder="Nombre de Parámetro">
                                        </div>
                                        
										<div class="form-group">
                                            <label for="tipoparametro">* Tipo:</label>
                                            <input value="<?php echo $rowGetUser['type'] ?>" tabindex="8" type="text" class="form-control" id="tipoparametro" >
                                        </div>
										
										<div class="form-group">
                                            <label for="valorparametro">* Valor:</label>
                                            <textarea tabindex="8" class="form-control" id="valorparametro" rows="6"><?php echo $rowGetUser['parameters'] ?></textarea>
                                        </div>
										
										<div class="form-check">
                                            
                                            <?php  
											 
											 $checkboxactive = "checked"; 
											 if ($rowGetUser['active'] == 0){
												 $checkboxactive = "";
											 }
											
											?>
											<input type="checkbox" value="" class="form-check-input" id="activoparametro" <?php echo $checkboxactive; ?> name="activoparametro" /><label for="activoparametro">Activo:</label>
                                        </div>
										
                                    <div class="card-footer">
                                        <input type="hidden" id="admin_id" value="<?php echo $_SESSION['userid']; ?>">
										<input type="hidden" id="config_id" value="<?php echo $_REQUEST['id']; ?>">
                                        <button tabindex="10" id="btn-edit-params" type="submit" class="btn text-white" style="background-color: #3B82F6;">
                                            <center><img id="loader" style="display: none;" src="img/loader.gif" /></center>
                                            <span id="lbl"><b>SALVAR</b></span>
                                        </button>

                                        <br />
                                        <small class="text-muted">* Campos obligatorios</small>
                                    </div>
									
									
									</div><!-- /.card-body -->
									
									 </form>
								</div>
							</div><!-- End Columna -->
							
						 </div><!-- END CONTAINER -->
                   
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <footer class="main-footer">
            <div class="d-none d-sm-block">
                <b>IMA AGROFERIAS DASH</b> <?php echo $versionDash; ?>
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
    <script src="js/functions.js"></script>
    
<script>
var configtype = <?php echo $configtype; ?>;
$(document).ready(function () {
  $("#btn-edit-params").click((e) => {
    e.preventDefault();
    editParams();
  });
});

  const editParams = async () => {
  let nombre = $("#nombreparametro").val();
  let type = $("#tipoparametro").val();
  let param = $("#valorparametro").val();
  let active = $("#activoparametro").is(':checked')?1:0;
  let admin_id = $("#admin_id").val();
  let config_id = $("#config_id").val();

  if (!nombre) {
    Swal.fire({
      html: "Ingrese el nombre del parámetro.",
      icon: "error",
      confirmButtonText: "Aceptar",
      position: "top",
    });
    $("#nombreparametro").addClass("InValid");
      return;
    }else{
		$("#nombreparametro").removeClass("InValid");
	}

  if (!type) {
    Swal.fire({
        html: "Confirme el Tipo de Parámetro.",
        icon: "error",
        confirmButtonText: "Aceptar",
        position: "top",
      });
	  $("#tipoparametro").addClass("InValid");
      return;
    }else{
		$("#tipoparametro").removeClass("InValid");
	}
	
	if (!param) {
    Swal.fire({
        html: "El Valor del Parámetro no puede estar vacío.",
        icon: "error",
        confirmButtonText: "Aceptar",
        position: "top",
      });
	  $("#valorparametro").addClass("InValid");
      return;
    }else{
		$("#valorparametro").removeClass("InValid");
	}

  
  let siteKey = "6LeiVcYaAAFgsghshGGshuesueyYDYEKmSBbagakckdlslsJdjshjhdassad0odoOOodjdjjswheD1cpDx2jP8P00oSGokBgnG9hKDob";
  let action = "editParams";
  
  
    $.ajax({
      type: "post",
	  dataType: "json",
      url: "./services/editParamsService.php",
      beforeSend: () => {
        $("#loader").css("display", "block");
        $("#lbl").css("display", "none");
      },
      data: {
		  nombre,
		  type,
		  param,
		  active,
		  admin_id,
		  config_id,
		  siteKey,
		  action
	   },
      success: (response) => {
        if (response.check == "error") {
          Swal.fire(
            "Error!",
            response.msg,
            "error"
          );
          return;
        }
        if (response.check == "success") {
          Swal.fire(
            "Actualización Exitosa",
            "Parámetro actualizada.",
            "success"
          ).then(() => {
			if(configtype == 1 || configtype == '1'){
				$(location).attr("href", "parametros.php");
			}else{
				$(location).attr("href", "parametrosmenus.php");
			}
            
          });
        }
      },
      complete: () => {
        $("#loader").css("display", "none");
        $("#lbl").css("display", "block");
      },
    });
};

</script>

</body>

</html>