<?php
date_default_timezone_set('America/Panama');
include('services/security.php');

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
		$active['sismos'] = "active";		
		
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
      <?php include("services/menu.php"); ?>

       <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
        <div class="row">    
			<div class="col-md-6">
				<div class="card card-danger" style="height: inherit; width: inherit; transition: all 0.15s ease 0s;">
					<div class="card-header">
						<h3 class="card-title">Geociencias UP</h3>
					<div class="card-tools">
						<button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i>
						</button>
					</div>

					</div>

					<div class="card-body" style="padding: 0px !important;height: 70vh;">
						<iframe src="https://sismos.panamaigc-up.com/eqview/" style="width: 100%;height: 100%;"></iframe>
					</div>

				</div>

			</div>
			
			<div class="col-md-6">
				<div class="card card-danger" style="height: inherit; width: inherit; transition: all 0.15s ease 0s;">
					<div class="card-header">
						<h3 class="card-title">Geociencias UP</h3>
					<div class="card-tools">
						<button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i>
						</button>
					</div>

					</div>

					<div class="card-body" style="padding: 0px !important;height: 70vh;">
						<iframe src="			https://earthquake.usgs.gov/earthquakes/map/?extent=5.03623,-86.27563&extent=11.48002,-74.78394" style="width: 100%;height: 100%;"></iframe>
					</div>

				</div>

			</div>
			

			</div>
			
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
</script>

</body>

</html>