<?php

include('services/security.php');
include('services/connection.php');

$sqlGetUsers = "select * from asistentes where activo = 1 order by id desc;";
$resGetUsers = mysqli_query($con, $sqlGetUsers);
$numOfUsers = mysqli_num_rows($resGetUsers);


$islogin = 0;
if($_SESSION['nivel'] == 1){
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

    <title>Módulo de Descarga de Asistencia | INADEH</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">

    <link rel="stylesheet" href="css/all.min.css">
    <link rel="stylesheet" href="css/adminlte.min.css">

    <link rel="icon" type="image/png" href="img/favicon.png">

<style>
.inlinefrm {
  display: inline;
}

.link-buttonfrm {
  background: none;
  border: none;
  color: green;
  text-decoration: none;
  cursor: pointer;
  font-size: 1.2em;
}
.link-buttonfrm:focus {
  outline: none;
}
.link-buttonfrm:active {
  color:red;
}

.swal2-html-container label{
  text-align: left !important;
  margin: 15px auto 5px auto !important;
  line-height: 1 !important;
}

.swal2-html-container input{
  text-align: left !important;
  margin: 3px auto 15px auto !important;
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
            </ul>
        </nav>

        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <a href="#" class="brand-link logo-switch" style="height: 140px;">
                <img style="width: 120px;margin-left: 20%;" src="img/inadeh_asistencia.jpg" alt="PanaVac19" class="logo-xl">
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
                                    Estudiantes
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                
								<li class="nav-item item">
                                    <a href="adminUsers.php" class="nav-link">
                                        <i class="fa fa-list nav-icon"></i>
                                        <p>Instructores Activos</p>
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
                            <h1>Estudiantes Activos</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="adminUsers.php">Administración de estudiantes</a></li>
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
								
								<form method="post" action="services/descargarxls.php" style="margin-left:10px;margin-right:10px" class="inlinefrm">
													 <input type="hidden" name="supercode" value="aeIouXYZ2022@Inadeh500R">
													  <input type="hidden" name="total" value="1">
													  <button type="submit" name="submit_param" value="1" class="btn btn-success" title="descargar">
														<i class="fa fa-file-excel"></i> Descargar Todo
													  </button>
													</form>
							
								<a href="javascript:void(0)" onclick="temporal()" class="btn btn-primary"><i class="fa fa-calendar"></i>  Seleccionar Fechas</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="dataTables_wrapper dt-bootstrap4">
                                <div class="table-responsive">
                                    <table id="users" class="table table-hover table-condensed table-striped">
                                        <thead>
                                            <tr>
                                                <th>Nombre</th>
                                                <th>Cedula</th>
												<th>Curso</th>
												<th>Provincia</th>
                                                <th>Descargar</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($rowGetUsers = mysqli_fetch_assoc($resGetUsers)) { ?>
                                                <tr>
                                                    <td style="width: 20%;"><?php echo $rowGetUsers['nombre'] ?></td>
                                                    <td style="width: 20%;"><?php echo $rowGetUsers['cedula'] ?></td>
													<td style="width: 15%;"><?php echo $rowGetUsers['curso_name'] ?></td>
                                                    <td style="width: 15%;"><?php echo $rowGetUsers['provincia'] ?></td>
                                                    <td style="width: 1%;text-align:center">
													<form method="post" action="services/descargarxls.php" class="inlinefrm">
													 <input type="hidden" name="supercode" value="aeIouXYZ2022@Inadeh500R">
													  <input type="hidden" name="cedula" value="<?php echo $rowGetUsers['cedula'] ?>">
													  <button type="submit" name="submit_param" value="submit_value" class="link-buttonfrm" title="descargar">
														<i class="fa fa-file-excel"></i>
													  </button>
													</form>
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
    <script src="https://www.google.com/recaptcha/api.js?render=6LeiVcYaAAAAAJdD1cpDx2jP8PSGokBgnG9hKDob"></script>
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
	
	async function temporal(){
		const { value: formValues } = await Swal.fire({
		  title: 'Seleccione Rango de Fechas',
		  showCancelButton: true,
		  confirmButtonText: 'Descargar XLS',
		  showLoaderOnConfirm: true,
		  html:
			'<label>Fecha - Desde</label><input id="swal-input1" name="fechaini" class="swal2-input" type="date">' +
			'<label>Fecha - Hasta</label><input id="swal-input2" name="fechaend" class="swal2-input" type="date">',
		 preConfirm: () => {
			return [
			  document.getElementById('swal-input1').value,
			  document.getElementById('swal-input2').value
			]
		  }
			})
		  
		  if (formValues) {
			var ff0 = formValues[0]+" 00:00:00";
			var ff1 = formValues[1]+" 00:00:00";
			var data = {"fechaini":ff0, "fechaend":ff1, "supercode":"aeIouXYZ2022@Inadeh500R"};
			var formBody = [];
			for (var property in data) {
			  var encodedKey = encodeURIComponent(property);
			  var encodedValue = encodeURIComponent(data[property]);
			  formBody.push(encodedKey + "=" + encodedValue);
			}
			formBody = formBody.join("&");

			//Swal.fire(ff0+" "+ff1);
			fetch("services/descargarxls.php", {
			  method: "POST",
			  headers: {'Content-Type': 'application/x-www-form-urlencoded'}, 
			  body: formBody
			}).then(response => response.blob())
				.then(blob => {
					var url = window.URL.createObjectURL(blob);
					var a = document.createElement('a');
					a.href = url;
					a.download = "inadeh_asistencia_fechas_"+formValues[0]+"-"+formValues[1]+".xls";
					document.body.appendChild(a); // we need to append the element to the dom -> otherwise it will not work in firefox
					a.click();    
					a.remove();  //afterwards we remove the element again         
				});
			
		  }
	}
	</script>
</body>

</html>
