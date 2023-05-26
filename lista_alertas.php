<?php
date_default_timezone_set('America/Panama');
include('services/security.php');
include('services/connection.php');

$sqlGetReports = "SELECT * FROM `mensajes_alertas` where activo = 1 order by modify_at desc";
$resGetReports = mysqli_query($con, $sqlGetReports);
$numOfReports = mysqli_num_rows($resGetReports);

$islogin = 0;
if($_SESSION['level'] >= 90){ // 90 Operadores ASEP y Otros de Monitoreo
	$islogin = 1;
}

if($islogin == 0){
	exit("Usted no tiene permisos para este modulo");
}

function diferenciaMinus($datetime_1){
	$start_datetime = new DateTime($datetime_1); 
    $diff = $start_datetime->diff(new DateTime()); 
	 /*
	echo $diff->days.' Days total<br>'; 
	echo $diff->y.' Years<br>'; 
	echo $diff->m.' Months<br>'; 
	echo $diff->d.' Days<br>'; 
	echo $diff->h.' Hours<br>'; 
	echo $diff->i.' Minutes<br>'; 
	echo $diff->s.' Seconds<br>';
	*/
	$total_minutes = ($diff->days * 24 * 60); 
	$total_minutes += ($diff->h * 60); 
	$total_minutes += $diff->i; 
	 
	#-- 'Diff in Minutes: '

	return $total_minutes;
}


?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Lista de Alertas - SINAPROC</title>

	<?php
		include("services/header.php");
		$active['lista_alertas'] = "active";
	?>
	<style>
		.wrapper .card-body {
		  padding: 0.7rem 1rem;
		}
		.tipoalerta {
			height: 24px;
			width: auto;
		}
		
		.tipoalerta.boomba{
			animation: pulsa 1s infinite ;
		}
		
		@keyframes pulsa{
			0%{
				transform: scale(1.1);
			}
			
			50%{
				transform: scale(1.5);
			}
			
			100%{
				transform: scale(1.1);
			}
		}
		
		.tipoalerton img {
		  height: 64px;
		  margin: 6px auto 16px auto;
		}
		.timesweet {
		  margin: 12px auto;
		}
		
		.titlesweet {
		  font-size: 1.3em;
		}
		
		.supertitlesweet {
		  font-size: 1.5em;
		  font-weight: 600;
		}
		
		.tipoalerton img {
			  width: 400px;
			  height: 118px;
			}
					
	</style>

</head>

<body class="hold-transition sidebar-mini">

    <audio id="myAudio">
	  <source src="img/sirena.mp3" type="audio/mpeg">
	</audio>
    <!-- Site wrapper -->
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
				<?php if( $_SESSION['level'] >= 800){ ?>
				<li class="nav-item d-none d-sm-inline-block">
                    <a href="ingresarAlerta.php?id=0" class="nav-link"><i class="fa fa-plus"></i> Agregar Nueva Alerta</a>
                </li>
				<?php } ?>
            </ul>
        </nav>
		<?php
					include("services/menu.php");
		?>
       

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
           
		    <!-- Main content -->
            <section class="content">

                <div class="container-fluid">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                Cantidad de Alertas: <span id="numOfReports"><?php echo $numOfReports; ?></span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="dataTables_wrapper dt-bootstrap4">
                                <div class="table-responsive">
                                    <table id="reportes" class="table table-hover table-condensed table-striped">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                               	<th>Titulo</th>
												<th>tipo</th>
												<th>Duración</th>
												<th>fecha</th>
                                                <th>Activo</th>
												<th>Acciones</th>
												
												
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
											$totalAlertas = array();
											$kk = 0;
											$debesonar = 0;
											while ($rowGetReports = mysqli_fetch_assoc($resGetReports)) { 
												$activo = "si";
												$diffi = diferenciaMinus($rowGetReports['created_at']);
												$class = "";
												if($diffi < 10){
													$class = "boomba";
													$debesonar = 1; #-- Solo 1 de la lista con menos de 10 minutos debe sonar
												}
												if($rowGetReports['activo'] == 0){
													$activo = "no";
												}
												$color = "<img src='img/alerta_".$rowGetReports['color'].".png' class='tipoalerta ".$class."' />";
												$totalAlertas[$rowGetReports['alerta_id']] = $rowGetReports;
											?>
                                                <tr>
                                                    <td style="width: 5%;"><?php echo $rowGetReports['alerta_id'] ?></td>
                                                    <td style="width: 25%;"><?php echo $rowGetReports['alerta_title'] ?></td>
													<td style="width: 5%;"><?php echo $color ?></td>
													<td style="width: 10%;"><?php echo $rowGetReports['duracion'] ?></td>
                                                    <td style="width: 15%;"><?php echo $rowGetReports['created_at'] ?></td>
													<td style="width: 5%;"><?php echo $activo ?></td>
													<td style="width: 5%;">
													<?php if( $_SESSION['level'] == 300 || $_SESSION['level'] >= 800){ ?>
													 <a href="ingresarAlerta.php?id=<?php echo $rowGetReports['alerta_id'] ?>"><i class="fa fa-edit" title="Editar"></i></a>
													<?php } ?>
													<a href="javascript:void(0)" data-id="<?php echo $rowGetReports['alerta_id'] ?>" onclick="detallesAlertas(this)"><i class="fa fa-eye" title="Ver"></i></a>
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
   <!-- <script src="https://www.google.com/recaptcha/api.js?render=6LeiVcYaAAAAAJdD1cpDx2jP8PSGokBgnG9hKDob"></script>-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="js/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="js/admin_lte/adminlte.min.js"></script>
 
 
	
	<script>
	var totaldata = {};
	var sonando = 0;
	var audio = document.getElementById('myAudio');
	
	<?php 
	
	foreach ($totalAlertas as $tal) { 
		
		$activo = "si";
		if($tal['activo'] == 0){
			$activo = "no";
		}

	
	echo "totaldata['".$tal['alerta_id']."'] = {";
		echo "'alerta_id': '".$tal['alerta_id']."',";
		echo "'alerta_title': '".$tal['alerta_title']."',";
		echo "'color': '".$tal['color']."',";
		echo "'alerta_desc': '".$tal['alerta_desc']."',";
		echo "'created_at': '".$tal['created_at']."',";
		echo "'modify_at': '".$tal['modify_at']."'";
	echo "}; ";
	
	 } ?>

	window.addEventListener('load', () => {
		
		<?php if($debesonar == 1){ ?>
		 alertaWin();
		 
		<?php } ?>
	}); 
	
	var delta = 12;
	var ciclosrunning = 0;
	$(document).ready(function () {
    $('#reportes').DataTable(
        {
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
            },
            "bLengthChange" : false,
			order: [[5, 'desc']],
        }
    );
	
	ciclos = setInterval(function () {
		if(ciclosrunning == 0){
			refrescaData(delta);
			console.log("Refrescando...")
		}
		
		}, 30000);
		
});


function refrescaData(delta){
	$(".superloader").fadeIn();
	var form_data = {'keynote':'sjdksjiUIdhywwdmnwhjUEHiE083890kws2989sdj8282','delta':delta};
	ciclosrunning = 1;
	sonando = 0;
	audio.pause();
	audio.currentTime = 0;
	
	$.ajax({
		url: "services/alertasService.php",
	    method:"POST",
		data: form_data,
		dataType: 'json',
	   beforeSend : function()
	   {
		//$("#preview").fadeOut();
	   },
	   success: function(res){
		   ciclosrunning = 0;
		   //console.log(res);
			$(".superloader").fadeOut();
			if(res=='invalid'){
			  console.log("Error 001")
			}else{
			 $('#reportes').DataTable().destroy();
			 var tablin = $('#reportes tbody');
			 tablin.empty();
			 var myheader = "<tr>";
			 myheader = myheader + "<th>ID</th>";
             myheader = myheader + "<th>Titulo</th>";
			 myheader = myheader + "<th>Tipo</th>";
			 myheader = myheader + "<th>Duración</th>";
             myheader = myheader + "<th>Fecha</th>";
			 myheader = myheader + "<th>Activo</th>";
             myheader = myheader + "<th>Acciones</th>";
             myheader = myheader + "</tr>";
             $('#reportes').find('thead').html(myheader);
		
			
			 var numOfReports = 0;
			 
			 $.each(res.data, function (a, b) {
				 totaldata[b['alerta_id']] = b;
				 var classi = "";
				 var diffimin = diffi(b['modify_at']);
				 if(diffimin < 10){
					 classi = "boomba";
					 if(sonando == 0){
						audio.play();
						sonando = 1;
					}
				 }
				 var colorimg = "<img src='img/alerta_"+b['color']+".png' class='tipoalerta "+classi+"' />";
				 var myactivo = "si";
				if(b['activo'] == 0){
					myactivo = "no";
				}
				var acciones = '';
				<?php if( $_SESSION['level'] == 300 || $_SESSION['level'] >= 800){ ?>
				acciones = '<a href="ingresarAlerta.php?id='+b['alerta_id']+'"><i class="fa fa-edit" title="Editar"></i></a>';
				<?php } ?>
				acciones = acciones + '<a href="javascript:void(0)" data-id="'+b['alerta_id']+'" onclick="detallesAlertas(this)"><i class="fa fa-eye" title="Ver"></i></a>';
                tablin.append("<tr><td style='width: 5%;'>"+b['alerta_id']+"</td>" +
                    "<td style='width: 25%;'>"+b['alerta_title']+"</td>"+
					"<td style='width: 5%;'>"+colorimg+"</td>"+
					"<td style='width: 10%;'>"+b['duracion']+"</td>"+
					"<td style='width: 15%;'>"+b['modify_at']+"</td>"+
					"<td style='width: 5%;'>"+myactivo+"</td>"+
					"<td style='width: 5%;'>"+acciones+"</td>");
					numOfReports = numOfReports + 1;
					
            });
			
			$("#numOfReports").html(numOfReports);
			
			$("#reportes").DataTable({
				order: [[5, 'desc']],
			});
			
			}
		 },
		 error: function(e){
			$(".superloader").fadeOut();
			console.log("Error Ajax:");
			console.log(e);
			ciclosrunning = 0;
		  }          
		});
		
	
}
//

	function detallesAlertas(th){
			var myid = $(th).attr("data-id");
			var mydata = totaldata[myid];
			var mydatetime = mydata["modify_at"];
			 mydatetime = mydatetime.split(" ");
			var myhtml = "<div class='tipoalerton'><img src='img/banner_"+mydata["color"]+".jpg' /></div>"+
							"<div class='supertitlesweet'>"+mydata["alerta_title"]+"</div>"+
							"<div class='titlesweet'>"+mydata["alerta_desc"]+"</div>"+
							"<div class='timesweet'>Fecha: "+mydatetime[0]+" - Hora: "+mydatetime[1]+"</div>";
			Swal.fire({
			  title: "",
			  html: myhtml,
			  showDenyButton: false,
			  showCancelButton: false,
			  confirmButtonText: 'Aceptar',
			  
			})
		}
		
	function diffi(dd){
		const startDate = new Date(dd); // replace with your start date
		const endDate = new Date(); // replace with your end date

		const diffInMs = endDate - startDate; // get the difference between the two dates in milliseconds
		const diffInMinutes = Math.round(diffInMs / 60000); // convert milliseconds to minutes and round to the nearest integer

		console.log(diffInMinutes); // output the difference in minutes
		
		
		return diffInMinutes;
	}
	//
	
	function alertaWin(){
		Swal.fire({
		  html: "Existe una alerta reciente",
		  icon: "warning",
		  confirmButtonText: "Cerrar"
		}).then(() => {
			audio.play();
			sonando = 1;
		});
	}		
		
	</script>
</body>

</html>
