<?php
date_default_timezone_set('America/Panama');
include('services/security.php');
include('services/connection.php');

$id = $_REQUEST['id'];

if($id == "0" || $id == 0){
	$rowGetUser['titulo'] = "";
	$rowGetUser['image1'] = "";
	
	$rowGetUser['url_link'] = "";
	$rowGetUser['description'] = "";
	$rowGetUser['active'] = 1;
	
}else{
	$sqlGetUser = "select * from guias where guia_id='$id';";
	$resGetUser = mysqli_query($con, $sqlGetUser);
	$numOfUser = mysqli_num_rows($resGetUser);

	if ($numOfUser == 0) {
		header('location: services/close.php');
	}
	$rowGetUser = mysqli_fetch_assoc($resGetUser);
}


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>SINAPROC - Que Hacer</title>
	<?php
		include("services/header.php");
		$active['quehacer'] = "active";		
		
	?>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <link rel="stylesheet" href="css/all.min.css">
    <link rel="stylesheet" href="css/adminlte.min.css">

    <link rel="icon" type="image/png" href="img/favicon.png">

	<style>
		.form-control.InValid {
		  border: 2px solid #f44;
		}
		
		.imageinput {
		  cursor: pointer;
		  font-size: 13px;
		  border: 1px solid #ddd;
		  width: 180px;
		  text-align: center;
		  padding: 6px;
		  display: inline-block;
		 vertical-align: middle;
		}
		.imageinput:focus,.imageinput:hover {
		  background-color:#eeddee;
		}
		.nominimage img {
		  width: 100%;
		  height: 100%;
		  position: absolute;
		  left: 0;
		  top: 0;
		}
		.nominimage {
		  background-color: #ddd;
		  width: 120px;
		  height: 120px;
		  margin-bottom: 8px;
		  text-align: center;
		  padding-top: 50px;
		  font-size: 12px;
		  overflow: hidden;
		  position: relative;
		  display: inline-block;
		  vertical-align: middle;
		}
		
		.note-btn-group.btn-group.note-insert, .note-btn-group.btn-group.note-table {
		  display: none;
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
                            <h1>Editar ¿QUE HACER?</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item">Parámetros</li>
                                <li class="breadcrumb-item active">Editar ¿QUE HACER?</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">

                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-primary">
                                <!--<div class="card-header">
                                    <h3 class="card-title">Datos personales del usuario</h3>
                                </div>-->
                                <!-- /.card-header -->
                                <!-- form start -->
                                <form>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="nombrefull">* Título:</label>
                                            <input value="<?php echo $rowGetUser['titulo'] ?>" tabindex="1" type="text" class="form-control" id="titulo" placeholder="Titulo">
                                        </div>
                                        <form id="FormaImagen" method="post" enctype="multipart/form-data">
											<div class="form-group">
												<label for="imagen1">Imagen:</label>
												<div style="clear:both"></div>
												 <?php 
												 if($rowGetUser['image1'] ==""){
													 echo '<div id="MinImg" class="nominimage" onclick="$(\'#image1\').click();">No Imagen</div>';
												 }else{
													 echo '<img id="myimage" src="'.$rowGetUser['image1'].'" style="height: 130px;"/>';
												 }
												 
												 ?>
												
												<div style="display:none">
													<input type="file" id="image1" />
													<input type="text" id="image1path" value="" />
												</div>
												<div class="imageinput" onclick="$('#image1').click();">
													<i class="fa fa-upload"></i> Cargar nueva imagen
												</div>
												
											</div>
										</form>
										
										<div class="form-group">
                                            <label for="url_link">Url (enlace opcional):</label>
											<input value="<?php echo $rowGetUser['url_link'] ?>" tabindex="1" type="text" class="form-control" id="url_link" placeholder="URL externo">
                                        </div>
										
										<div class="form-group">
                                            <label for="valorparametro">* Descripcion:</label>
                                            <!--<textarea tabindex="8" class="form-control" id="descripcion" rows="6"></textarea>-->
											<div id="descripcion"><?php echo $rowGetUser['descripcion'] ?></div>
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
    <script src="js/functions.js"></script>
	<!-- include summernote css/js-->
	<link href="js/plugins/summernote-bs4.min.css" rel="stylesheet">
	<script src="js/plugins/summernote-bs4.min.js"></script>
    
<script>
$(document).ready(function () {
  $("#btn-edit-params").click((e) => {
    e.preventDefault();
    editParams();
  });
  
  $('#descripcion').summernote();
  $(document).on('change','#image1',function(){
	  var prop = document.getElementById("image1").files[0];
	  var image_name = prop.name;
	  var image_ext = image_name.split(".").pop().toLowerCase();
	  if(jQuery.inArray(image_ext, ['gif','png','jpg','jpeg']) == -1) 
	  {
	   alert("Invalid Image File");
	  }
	  var oFReader = new FileReader();
	  oFReader.readAsDataURL(document.getElementById("image1").files[0]);
	  var f = document.getElementById("image1").files[0];
	  var fsize = f.size||f.fileSize;
	  if(fsize > 2000000){
		alert("Image File Size is very big");
	  }else{
		subeImagen();
	  }
  });
});

  const editParams = async () => {
  let titulo = $("#nombreparametro").val();
  let imagen1 = $("#image1path").val();
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

function subeImagen(){
	var qhid = '<?php echo $id; ?>';
	var form_data = new FormData();
	form_data.append("file", document.getElementById('image1').files[0]);
	form_data.append("qhid", qhid);
	$("#MinImg").html("Cargando...").fadeIn();
	$.ajax({
		url: "services/UploadImagesService.php",
	    method:"POST",
		data: form_data,
		contentType: false,
		cache: false,
		processData: false,
	   beforeSend : function()
	   {
		//$("#preview").fadeOut();
		$("#err").fadeOut();
	   },
	   success: function(data)
		  {
		if(data=='invalid')
		{
		 // invalid file format.
			Swal.fire(
				"Error",
				"Formato de imagen no aceptado!",
				"error"
			  )
		}
		else
		{
		 // view uploaded file.
		 $("#MinImg").html("<img src='"+data+"' />").fadeIn();
		 $("#image1path").val(data);
		 $("#myimage").attr("src",data);
		; 
		}
		  },
		 error: function(e) 
		  {
			Swal.fire(
				"Error",
				"Error subiendo imagen",
				"error"
			  )
		  }          
		});
}
//
</script>

</body>

</html>