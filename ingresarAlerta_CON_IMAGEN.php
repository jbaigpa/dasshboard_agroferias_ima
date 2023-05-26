<?php
date_default_timezone_set('America/Panama');
include('services/security.php');
include('services/connection.php');
include('services/regions.php');

$id = $_REQUEST['id'];

if($id == "0" || $id == 0){
	$rowGetAlert['alerta_title'] = "";
	$rowGetAlert['imagen'] = "";
	$rowGetAlert['urlopt'] = "";
	$rowGetAlert['alerta_desc'] = "";
	$rowGetAlert['activo'] = 1;
	$rowGetAlert['checkall'] = 1;
	$rowGetAlert['duracion'] = '12h';
	$rowGetAlert['color'] = "verde";
	$duracionselect['12h'] = "selected";
	$duracionselect['24h'] = "";
	$duracionselect['36h'] = "";
	$duracionselect['48h'] = "";
	$duracionselect['72h'] = "";
	
	
	$checkboxactive = "checked";
	
	foreach ($myregions as $k => $v) {
		$checkboxregion[$k] = "checked";
	}
	
	
}else{
	$sqlGetAlert = "select * from mensajes_alertas where alerta_id='$id';";
	$resGetAlert = mysqli_query($con, $sqlGetAlert);
	$numOfAlert = mysqli_num_rows($resGetAlert);

	if ($numOfAlert == 0) {
		header('location: services/close.php');
	}
	$rowGetAlert = mysqli_fetch_assoc($resGetAlert);
	
	$getRegion = $rowGetAlert['region'];
	$getRegionArr = explode(",",$getRegion);
	foreach ($myregions as $k => $v) {
		if(in_array($k,$getRegionArr)){
			$checkboxregion[$k] = "checked";
		}else{
			$checkboxregion[$k] = "";
		}
	}
	
	if($rowGetAlert['activo']==1){
		$checkboxactive = "checked";
	}else{
		$checkboxactive = "";
	}
	
	
	$duracionselect['12h'] = ($rowGetAlert['duracion'] == '12h') ? "selected":"";
	$duracionselect['24h'] = ($rowGetAlert['duracion'] == '24h') ? "selected":"";
	$duracionselect['36h'] = ($rowGetAlert['duracion'] == '36h') ? "selected":"";
	$duracionselect['48h'] = ($rowGetAlert['duracion'] == '48h') ? "selected":"";
	$duracionselect['72h'] = ($rowGetAlert['duracion'] == '72h') ? "selected":"";
	
	$colorselect['verde'] = ($rowGetAlert['color'] == 'verde') ? "selected":"";
	$colorselect['amarilla'] = ($rowGetAlert['color'] == 'amarilla') ? "selected":"";
	$colorselect['roja'] = ($rowGetAlert['color'] == 'roja') ? "selected":"";
	
}


$alertaaviso = "Alerta";

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>IMA AGROFERIAS - Ingresar Alerta</title>
	<?php
		include("services/header.php");
		$active['alertas'] = "active";		
		
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
		/*
		option[value="verde"]{
		  color: #000 !important;
		  font-weight: 700;
		  background: #00FF2A !important;
		}
		
		option[value="amarilla"] {
		  color: #000 !important;
		  font-weight: 700;
		  background: #FF0 !important;
		}
				
		option[value="roja"]{
		  color: #000 !important;
		  font-weight: 700;
		  background: #FF0000 !important;
		}
		*/
		
		.icontypealert.ictpa-verde {
		  color: #00FF2A;
		}
		
		.icontypealert.ictpa-amarilla {
		  color: #FF0;
		}
		
		.icontypealert.ictpa-roja {
		  color: #FF0000;
		}
		
		#icontypealert {
		  font-size: 1.6em;
		  vertical-align: middle;
		  margin-left: 6px;
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
                    <a href="lista_alertas.php" class="nav-link">Ver todas las Alertas</a>
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
                            <h1>Ingresar <?php echo $alertaaviso; ?></h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item">Avisos y Alertas</li>
                                <li class="breadcrumb-item active">Ingresar <?php echo $alertaaviso; ?></li>
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
                                            <input value="<?php echo $rowGetAlert['alerta_title'] ?>" tabindex="1" type="text" class="form-control" id="titulo" placeholder="Titulo">
                                        </div>
										
										<div class="row">
											<div class="col-4">
												<div class="form-group">
													<label for="color">Tipo de Alerta: <i id="icontypealert" class="fa fa-circle icontypealert ictpa-<?php echo $rowGetAlert['color']; ?>"></i></label>
													<select id="color" name="color" class="form-control" onchange="cambiacolortipo()">
														<option value="verde" <?php echo $colorselect['verde']; ?>  >Verde </option>
														<option value="amarilla" <?php echo $colorselect['amarilla']; ?> >Amarilla  <i class="fa fa-circle" style="color:#FFFF00"></i></option>
														<option value="roja" <?php echo $colorselect['roja']; ?> >Roja  <i class="fa fa-circle" style="color:#ff0000"></i></option>
														
													</select>
													
												</div>
											</div>
										</div>
                                        <form id="FormaImagen" method="post" enctype="multipart/form-data">
											<div class="form-group">
												<label for="imagen1">Imagen:</label>
												<div style="clear:both"></div>
												 <?php 
												 if($rowGetAlert['imagen'] ==""){
													 echo '<div id="MinImg" class="nominimage" onclick="$(\'#image1\').click();">No Imagen</div>';
												 }else{
													 echo '<img id="myimage" src="'.$rowGetAlert['imagen'].'" style="height: 130px;"/>';
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
											<input value="<?php echo $rowGetAlert['urlopt'] ?>" tabindex="1" type="text" class="form-control" id="url_link" placeholder="URL externo">
                                        </div>
										
										<div class="form-group">
                                            <label for="valorparametro">* Descripcion:</label>
                                            <!--<textarea tabindex="8" class="form-control" id="descripcion" rows="6"></textarea>-->
											<div id="descripcion"><?php echo $rowGetAlert['alerta_desc'] ?></div>
                                        </div>
										
										
										<div class="form-check">
                                            	<input type="checkbox" <?php echo $checkboxactive; ?> class="form-check-input" name="checkboxactive" id="checkboxactive" /><label for="activobox">¿Alerta Activa?</label>
											</div>
										
										
										<div class="row">
											<div class="col-4">
												<div class="form-group">
													<label for="selectduracion">Duración del <?php echo $alertaaviso; ?>:</label>
													<select id="selectduracion" name="selectduracion" class="form-control">
														<option value="12h" <?php echo $duracionselect['12h']; ?> >12 horas</option>
														<option value="24h" <?php echo $duracionselect['24h']; ?> >24 horas</option>
														<option value="36h" <?php echo $duracionselect['36h']; ?> >36 horas</option>
														<option value="48h" <?php echo $duracionselect['48h']; ?> >48 horas</option>
														<option value="72h"<?php echo $duracionselect['72h']; ?> >72 horas</option>
													</select>
													
												</div>
											</div>
										</div>
										<hr/>
										<h5 style="font-size: 18px;font-weight: 600;">Regiones Afectadas:</h5>
										
										<div class="row" id="regionable">
										
										<?php 
											$jj = 0;
											$myregionsCount = count($myregions);
											$myregionsHalf  = round($myregionsCount/2, PHP_ROUND_HALF_DOWN);
											
											foreach($myregions as $kk => $vv) {
												if($jj == 0 || $jj == $myregionsHalf ){
													echo '<div class="col-4"><div class="form-group">';
												}
										?>
											<div class="form-check">
                                            	<input type="checkbox" value="<?php echo $kk; ?>" class="form-check-input" name="regionbox" id="regionbox_<?php echo $kk; ?>" <?php if($kk == "region_all"){ echo 'onclick="checkallregions();"';} ?> <?php echo $checkboxregion[$kk]; ?>  /><label for="regionbox"><?php echo $vv; ?></label>
											</div>
										<?php 
											
											
											$jj = $jj + 1;
											
											if($jj == $myregionsHalf|| $jj == $myregionsCount ){
													echo '</div></div>';
												}

											// End foreach
											} 
										?>
											
										
										
										
										</div>
										
                                    <div class="card-footer">
                                        <input type="hidden" id="admin_id" value="<?php echo $_SESSION['userid']; ?>">
										<input type="hidden" id="alerta_id" value="<?php echo $_REQUEST['id']; ?>">
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
  let titulo = $("#titulo").val();
  let color = $("#color").val();
  let imagen1 = $("#myimage").attr("src");
   let urlopt = $("#url_link").val();
  let descripcion = $('#descripcion').summernote('code');
  let active = $("#checkboxactive").is(':checked')?1:0;
  let admin_id = $("#admin_id").val();
  var duracion = $("#selectduracion option:selected").val();
  let alerta_id = $("#alerta_id").val();
  let regions = $('input[name="regionbox"]:checked').map(function(){return this.value}).get();
  regions = regions.toString();
  if (!titulo) {
    Swal.fire({
      html: "Ingrese el Titulo.",
      icon: "error",
      confirmButtonText: "Aceptar",
      position: "top",
    });
    $("#titulo").addClass("InValid");
      return;
    }else{
		$("#titulo").removeClass("InValid");
	}
	
	if (!descripcion) {
    Swal.fire({
      html: "Ingrese la descripcion.",
      icon: "error",
      confirmButtonText: "Aceptar",
      position: "top",
    });
    $("#descripcion").addClass("InValid");
      return;
    }else{
		$("#descripcion").removeClass("InValid");
	}

	if (regions.length === 0) {
    Swal.fire({
      html: "Seleccione al menos una (1) region",
      icon: "error",
      confirmButtonText: "Aceptar",
      position: "top",
    });
    $("#regionable").addClass("InValid");
      return;
    }else{
		$("#regionable").removeClass("InValid");
	}

   let siteKey = "6LeiVcYaAAFgsghshGGshuesueyYDYEKmSBbagakckdlslsJdjshjhdassad0odoOOodjdjjswheD1cpDx2jP8P00oSGokBgnG9hKDob";
  

    $.ajax({
      type: "post",
	  dataType: "json",
      url: "./services/editAlertas.php",
      beforeSend: () => {
        $("#loader").css("display", "block");
        $("#lbl").css("display", "none");
      },
      data: {
		  titulo,
		  color,
		  descripcion,
		  urlopt,
		  regions,
		  imagen1,
		  active,
		  admin_id,
		  alerta_id,
		  duracion,
		  siteKey
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
		var avisodatabase = "Alerta Actualizada";
		if(alerta_id == 0){
			avisodatabase = "Alerta Ingresada";
		}
        if (response.check == "success") {
          Swal.fire(
            avisodatabase,
            "operación exitosa!",
            "success"
          ).then(() => {
			
				$(location).attr("href", "lista_alertas.php");
			
            
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
		url: "services/UploadImagesServiceAlertas.php",
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
		 $("#MinImg").html("<img id='myimage' src='"+data+"' />").fadeIn();
		 $("#image1path").val(data);
		 $("#myimage").attr("src",data);
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

var checkregionboxall = 1;
function checkallregions(){
	if(checkregionboxall == 1){
		$("input[name='regionbox']").prop("checked",false);
		checkregionboxall = 0;
	}else{
		$("input[name='regionbox']").prop("checked",true);
		checkregionboxall = 1;
	}
}
//

//
function cambiacolortipo(){
	$("#icontypealert").removeClass();
	var colorselected = $("#color :selected").val();
	var myclass='fa fa-circle icontypealert ictpa-'+colorselected.trim();
	$("#icontypealert").attr('class', myclass);
	$("#icontypealert").prop('class', myclass);
}
//
</script>

</body>

</html>