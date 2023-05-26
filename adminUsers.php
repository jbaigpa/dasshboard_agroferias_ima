<?php

include('services/security.php');
include('services/connection.php');

$sqlGetUsers = "select * from admin_users where active = 1 order by admin_id desc;";
$resGetUsers = mysqli_query($con, $sqlGetUsers);
$numOfUsers = mysqli_num_rows($resGetUsers);

$admins = array(90=>"Telecom / Monitoreo", 100=>"Visor de Estadisticas",300=>"Publicador Avisos y Alertas",500=>"Coordinador",800=>"Administrador",1000=>"Super Admin");

$islogin = 0;
if($_SESSION['level'] == 800 || $_SESSION['level'] == 1000){
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

    <title>Módulo de Administración Agroferias</title>

	<?php
		include("services/header.php");
		$active['admin'] = "active";
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
                    <a href="reportes.php" class="nav-link">Reportes</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="adminUsers.php" class="nav-link">Administradores</a>
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
                            <h1>Usuarios Activos</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="adminUsers.php">Administración de usuarios</a></li>
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
                                Número de usuarios: <?php echo $numOfUsers ?>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="dataTables_wrapper dt-bootstrap4">
                                <div class="table-responsive">
                                    <table id="users" class="table table-hover table-condensed table-striped">
                                        <thead>
                                            <tr>
                                                <th>Nombre</th>
                                               	<th>Fuente</th>
												<th>Email</th>
                                                <th>Nivel</th>
                                                <th>Editar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($rowGetUsers = mysqli_fetch_assoc($resGetUsers)) { ?>
                                                <tr>
                                                    <td style="width: 20%;"><?php echo $rowGetUsers['name'] ?></td>
                                                    <td style="width: 20%;"><?php echo $rowGetUsers['institucion'] ?></td>
													<td style="width: 15%;"><?php echo $rowGetUsers['email'] ?></td>
                                                    
                                                    <td style="width: 1%;"><?php echo $admins[$rowGetUsers['level']]; ?></td>
                                                    <td style="width: 10%;">
													<?php 
													# Solo usuarios logueados con nivel 500 o usuarios listados menor a 800
													# Administradores
													if($_SESSION['level'] == 500){
													?>
													<a href="viewUser.php?id=<?php echo md5($rowGetUsers['admin_id']); ?>" class="btn btn-primary btn-sm" title="Ver"><i class="fas fa-eye"></i></a>
													<?php
													}elseif($_SESSION['level'] > 800 && $rowGetUsers['level'] < 900){
													?>
                                                    <a href="editUserAdmin.php?id=<?php echo md5($rowGetUsers['admin_id']); ?>" class="btn btn-primary btn-sm" title="Editar"><i class="fas fa-pen"></i></a>&nbsp;<a onclick="apagarUser('<?php echo md5($rowGetUsers['admin_id']); ?>','<?php echo $rowGetUsers['name']; ?>','<?php echo $rowGetUsers['level']; ?>')" class="btn btn-danger btn-sm" title="Eliminar"><i class="fas fa-trash"></i></a>
                                                    
													<?php
													 }
													?>
													<a href="Auditoria.php?id=<?php echo md5($rowGetUsers['admin_id']); ?>" class="btn btn-warning btn-sm" title="Auditoria"><i class="fas fa-circle"></i></a>
													</td>
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
    <!--<script src="https://www.google.com/recaptcha/api.js?render=6LeiVcYaAAAAAJdD1cpDx2jP8PSGokBgnG9hKDob"></script>-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="js/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="js/admin_lte/adminlte.min.js"></script>
    <script src="js/adminUsers.js"></script>
	
	<script>
	
	function apagarUser(id,name,nivel){
		var mytitle = "Deseas eliminar el usuario: "+ name+" ?";
		Swal.fire({
		  title: mytitle,
		  showDenyButton: false,
		  showCancelButton: true,
		  confirmButtonText: 'Eliminar',
		  denyButtonText: `Cancelar`,
		}).then((result) => {
		  /* Read more about isConfirmed, isDenied below */
		  if (result.isConfirmed) {
			apagarUserAjax(id,nivel);
		  }
		})
	}
	
	function apagarUserAjax(id,nivel){
	var apikeypublic = 'ijiwdjiIJIDJSIE9328KDEKJSkjdks';
	$.ajax({
      type: "post",
      url: "./services/eliminarUser.php",
      beforeSend: () => {
        $("#loader").css("display", "block");
        $("#lbl").css("display", "none");
      },
     
	 data: { id, apikeypublic,nivel},
      success: (response) => {
        if (response == "ERROR") {
          Swal.fire({
            html: "Error eliminando, intente nuevamente.",
            icon: "error",
            confirmButtonText: "Aceptar",
            position: "top",
          });
		  
          return;
        }
      
	  
        if (response == "DELETED") {
         Swal.fire({
              html: "El usuario ha sido eliminado",
              icon: "success",
              confirmButtonText: "Aceptar",
              position: "top",
            }).then(() => {
			  window.location.reload();
			})
			
        }
		
      },
      complete: () => {
        $("#loader").css("display", "none");
        $("#lbl").css("display", "block");
      },
      error: (xhr, status, error) => {
        Swal.fire({
          html: xhr.responseText,
          icon: "error",
          confirmButtonText: "Aceptar",
          position: "top",
        });
      },
    });
		
	}//END FUNCTION apagarUserAjax
	</script>
</body>

</html>
