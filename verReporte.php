<?php
date_default_timezone_set('America/Panama');
include('services/security.php');
include('services/connection.php');
$title['verreporte'] = 'Venta';

$islogin = 0;
if( $_SESSION['level'] >= 500){
	$islogin = 1;
}

if($islogin == 0){
	exit("Error Level");
}

$lainstitucion = 'IMA AGROFERIAS';
$id = $_GET['id'];

$sqlGetReport = "SELECT t1.id, t1.cedula, t1.producto, t1.lugar_compra, t1.fecha_compra, t1.fecha_registro, t1.precio, t1.vendedor, t2.nombre as vendedor_nombre, t1.supervisor, t1.offline, t1.geolat, t1.geolon FROM ventas as t1 JOIN operadores as t2 on t1.vendedor = t2.cedula WHERE t1.id = $id;";

$resGetReport = mysqli_query($con, $sqlGetReport);
$rowGetReport = mysqli_fetch_assoc($resGetReport);


$cedula = $rowGetReport['vendedor'];

$sqlGetUser = "SELECT * FROM `operadores` WHERE `cedula` = '$cedula'";
$resGetUser = mysqli_query($con, $sqlGetUser);
$rowGetUser= mysqli_fetch_assoc($resGetUser);
$email = $rowGetUser['email'];

$estatus = array(0=>"No Leido",5=>"Verificando",10=>"Atendido",99=>"Eliminado/Cancelado");
$mycolor = array(0=>"#AAAAAA",5=>"#FFFF00",10=>"#00FF00",99=>"#FF0000");

$mymodify_at = $rowGetReport['fecha_registro'];
if($mymodify_at == ""){
	$mymodify_at = "";
}else{
	
	$mymodify_at = "<div class='form-group'>";
	$mymodify_at .= "<label for='modify_at'>Fecha de registo: ";
	$mymodify_at .= $rowGetReport['fecha_registro']."</label>";
	$mymodify_at .= "</div>";
}


?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo $lainstitucion; ?> - <?php echo $title['verreporte']; ?></title>

	<?php
		include("services/header.php");
	
		$active['verreporte'] = "active"; 
		$active['report'] = "active";

	?>
	
	<style>
	.mygaleria{
	  height: 80px;
	  width: 80px;
	  border: 1px solid #eee;
	  background-size: cover;
	  display: inline-block;
	  margin: 1px 3px;
	}
	
	.form-control.InValid {
		border: 2px solid #f44;
	}
	
	.miniubicadir {
	  display: inline-block;
	  margin-left: 6px;
	}
	
	.estatusEvent, .descripcionEvent, .dirExacta{
	  border: 1px solid #ddd;
	  padding: 8px;
	}
	
	#EstatusOldArea {
	  padding: 8px;
	  margin-bottom: 10px;
	}
	
	#estatusnewarea {
	  border: 2px dashed #22622A;
	  padding: 10px;
	}
	
	
	</style>

</head>

<body class="hold-transition sidebar-mini">
    <!-- Site wrapper -->
    <div class="wrapper">
        <!-- Navbar -->
       
	   

       <?php
					include("services/menu.php");
				?>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            

            <!-- Main content -->
            <section class="content">

                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6" style="margin-top: 20px;">
                            <div class="card card-primary">
                                
                                <!-- /.card-header -->
								<div class="card-header">
									<div class="card-title">
										Venta ID: <strong><?php echo $rowGetReport['id']; ?></strong>
										
									</div>
								</div>
                                <!-- form start -->
                                <form>
									
                                    <div class="card-body">
                                      
										
										<div class="form-group">
                                            <label for="email">Cédula: <?php echo $rowGetReport['cedula'] ?></label>
                                            
                                        </div>
										
										
										<div class="form-group">
                                            <label for="email">Fecha de Venta: <?php echo $rowGetReport['fecha_compra']; ?></label>
                                            <input type="hidden" id="id_reporte" value="<?php echo $id; ?>" />
											<input type="hidden" id="user_id" value="<?php echo $_SESSION['userid']; ?>" />
											<input type="hidden" id="level" value="<?php echo $_SESSION['level']; ?>" />
											  
                                        </div>
										
											<?php echo $mymodify_at ?>
                                            <!--<div><a href="#">Ver historial de modificaciones</a></div>-->
                                        
										
										
										
										<div class="form-group">
                                            <label for="email">Producto: <?php echo $rowGetReport['producto'] ?></label>
                                            
                                        </div>
										
										<div class="form-group">
                                            <label for="email">Precio: B/.<?php echo number_format($rowGetReport['precio'], 2, '.', ','); ?></label>
                                            
                                        </div>
										
										<div class="form-group">
                                            <label for="email">Vendedor: <?php echo $rowGetReport['vendedor_nombre']." (".$rowGetReport['vendedor'].")"; ?></label>
                                            
                                        </div>
										
										<div class="form-group">
                                            <label for="email">Supervisor: <?php echo $rowGetReport['supervisor']; ?></label>
                                            
                                        </div>
										
										<div class="form-group">
                                            <label for="email">En Linea: <?php if($rowGetReport['offline'] == 0){echo "SI";}else{echo "NO";} ?></label>
                                            
                                        </div>
										
										
																			
                                    </div>
                                    <!-- /.card-body -->
									</div>
									<!-- /.card -->
									
									</div>
									<!-- /.col -->
									
									<div class="col-md-6" style="margin-top: 20px;">
									<div class="card card-primary">
									 
									    <!-- /.card-header -->
										<div class="card-header">
											<div class="card-title">
												Ubicación
												
											</div>
										</div>
										<!-- /.END card-header -->
										
										 <div class="card-body">
										<div class="form-group">
                                            <label for="email">Dirección:</label>
											
											
										<div class="form-group">
                                            <label for="email">Ubicacion Geográfica:</label>
											<div>Latitud: <?php echo $rowGetReport['geolat'] ?> , Longitud: <?php echo $rowGetReport['geolon'] ?></div>
											
                                        </div>
										<div>
										
										<iframe 
											  width="400" 
											  height="270" 
											  frameborder="0" 
											  scrolling="no" 
											  marginheight="0" 
											  marginwidth="0" 
											  src="https://maps.google.com/maps?q=<?php echo $rowGetReport['geolat'] ?>,<?php echo $rowGetReport['geolon'] ?>&hl=es&z=14&amp;output=embed"
											 >
											 </iframe>
											 <br />
											 <small>
											   <a 
												href="https://maps.google.com/maps?q=<?php echo $rowGetReport['geolat'] ?>,<?php echo $rowGetReport['geolon'] ?>&hl=es;z=14&amp;output=embed" 
												style="color:#0000FF;text-align:left" 
												target="_blank"
											   >
												 Agrandar el Mapa
											   </a>
											 </small>
										
										</div>
									</div>
									</div>

                                </form>
                            </div>
                      <!-- / col -->
					  
						
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
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="js/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="js/admin_lte/adminlte.min.js"></script>
    <script src="js/functions.js"></script>
    <script src="js/verreporte.js"></script>
	
	<script>
	function openImage(th){
		var myimg = $(th).attr('data-img');
		Swal.fire({
		  imageUrl: myimg,
		  imageHeight: 500,
		  width: 700,
		  imageAlt: 'Image',
		  padding: '1px'
		})
	}
	
	function cambiaNewStatus(){
		$("#estatusnewarea").show();
		$("#EstatusOldArea").css("background","#ccc");
		$("#btnAddNewEstatus").hide();
		$("#btn-close-reppo1").hide();
		
	}
	
	
	</script>

</body>

</html>