<?php

include('services/security.php');
include('services/connection.php');

$sqlGetUsers = "SELECT t1.*, COUNT(t2.id) as countreport 
FROM operadores as t1
LEFT JOIN ventas as t2  
ON t1.cedula = t2.vendedor GROUP BY t1.id;";
$resGetUsers = mysqli_query($con, $sqlGetUsers);
$numOfUsers = mysqli_num_rows($resGetUsers);

$admins = array(1=>"Instructor",2=>"Coordinador",3=>"Supervisor",500=>"Auditoria (Lectura)",1000=>"Super Usuario");

$islogin = 0;
if($_SESSION['level'] == 500 || $_SESSION['level'] == 800 || $_SESSION['level'] == 1000){
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
		$active['appuser'] = "active";
	?>
	
	<style>
	.desactivaClass.even, .desactivaClass.odd {
	  background-color: #f99 !important;
	  color: #eee !important;
	}
	
	.desactivaClass.even:hover, .desactivaClass.odd:hover {
	  background-color: #f44 !important;
	  color: #fff !important;
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
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="reportes.php" class="nav-link">Ventas del Día</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="adminUsers.php" class="nav-link">Usuarios del App</a>
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
                            <h1>Usuarios del APP</h1>
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
                                               	<th>cedula</th>
												<th>Email</th>
												<th>Tipo</th>
                                                <th>Provincia</th>
												<th>Reportes</th>
                                                <th>acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
												while ($rowGetUsers = mysqli_fetch_assoc($resGetUsers)) { 
													$desactivaClass = "";
													$nombrefull = $rowGetUsers['nombre'];
													
													$desactiva = '<a onclick="ApagarActivarUser(this,0)" data-user="'.md5($rowGetUsers['cedula']).'" data-name="'.$nombrefull.'" class="btn btn-danger btn-sm" title="Desactivar Usuario"><i class="fas fa-toggle-off"></i></a>';
													if($rowGetUsers['activo'] == 0){
														$desactiva = '<a onclick="ApagarActivarUser(this,1)" data-user="'.md5($rowGetUsers['cedula']).'" data-name="'.$nombrefull.'" class="btn btn-primary btn-sm" title="Activar Usuario"><i class="fas fa-toggle-on"></i></a>';
														$desactivaClass = "class='desactivaClass'";
													}
													
													
													?>
                                                <tr  <?php echo $desactivaClass; ?> >
                                                    <td style="width: 20%;"><?php echo $nombrefull; ?></td>
                                                    <td style="width: 20%;"><?php echo $rowGetUsers['cedula']; ?></td>
													<td style="width: 15%;"><?php echo $rowGetUsers['email']; ?></td>
													
													<td style="width: 15%;"><?php echo $rowGetUsers['tipo_tienda']; ?></td>
                                                    
                                                    <td style="width: 1%;"><?php echo $rowGetUsers['provincia']; ?></td>
													
													 <td style="width: 1%;"><?php echo $rowGetUsers['countreport']; ?></td>
                                                    <td style="width: 10%;">
													
													
													
													<?php echo $desactiva; ?>
													
                                                    <a href="viewUser.php?id=<?php echo md5($rowGetUsers['id']); ?>" class="btn btn-primary btn-sm" title="Ver"><i class="fas fa-eye"></i></a>&nbsp;
                                                   
												
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
	
	function ApagarActivarUser(th,type){
		var name = $(th).attr("data-name");
		var userid = $(th).attr("data-user");
		var myaccion = "DESHABILITAR";
		var btntxt = 'Deshabilitar';
		if(type == 1){
			myaccion = "HABILITAR";
			btntxt = 'Habilitar';
		}
		var mytitle = "Deseas "+myaccion+" al usuario: "+ name+" ?";
		Swal.fire({
		  title: mytitle,
		  showDenyButton: false,
		  showCancelButton: true,
		  confirmButtonText: btntxt,
		  denyButtonText: `Cancelar`,
		}).then((result) => {
		  /* Read more about isConfirmed, isDenied below */
		  if (result.isConfirmed) {
			apagarUserAjax(userid,type);
		  }
		})
	}
	
	function apagarUserAjax(id,type){
	var apikeypublic = 'ijiwdjiIJIDJSIE9328KDEKJSkj0O0so290jOOOm38ddks';
	var middletoken = id;
	var myurl = "./services/eliminarUserApp.php";
	var myacciontxtok = "El usuario ha sido Deshabilitado";
	if(type == 1){
			myurl = "./services/activarUserApp.php";
			var myacciontxtok = "El usuario ha sido Habilitado";
	}
	$(".superloader").show();
	$.ajax({
      type: "post",
      url: myurl,
      beforeSend: () => {
        $("#loader").css("display", "block");
        $("#lbl").css("display", "none");
      },
     
	 data: { middletoken, apikeypublic},
     success: (response) => {
		$(".superloader").hide(); 
        if (response == "ERROR") {
          Swal.fire({
            html: "Error, intente nuevamente.",
            icon: "error",
            confirmButtonText: "Aceptar",
            position: "top",
          });
		  
          return;
        }else if (response == "DELETED") {
         Swal.fire({
              html: myacciontxtok,
              icon: "success",
              confirmButtonText: "Aceptar",
              position: "top",
            }).then(() => {
				$(location).attr("href", "appUsers.php");
			 });
        }else{
			Swal.fire({
              html: "Problemas con la Acción: Contactar al administrador..!!",
              icon: "error",
              confirmButtonText: "Aceptar",
              position: "top",
            })
		}
		
      },
      complete: () => {
        $("#loader").css("display", "none");
		$(".superloader").hide();
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
