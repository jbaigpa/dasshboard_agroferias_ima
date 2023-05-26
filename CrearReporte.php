<?php
include('services/security.php');
include('services/connection.php');
$title['crearreporte'] = 'Crear Reporte';
$lainstitucion = 'SINAPROC';
$estatus = array(0=>"No Leido",5=>"Verificando",10=>"Atendido",99=>"Eliminado/Cancelado");
$mycolor = array(0=>"#AAAAAA",5=>"#FFFF00",10=>"#00FF00",99=>"#FF0000");
$sqlGetProvincias = "SELECT * FROM `provincia`";
$resGetProvincias = mysqli_query($con, $sqlGetProvincias);

$sqlGetindicentes = "SELECT * FROM `indicentes`";
$resGetindicentes = mysqli_query($con, $sqlGetindicentes);

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
	
		$active['report'] = "active";

	?>
	
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
       
	   

       <?php
					include("services/menu.php");
				?>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            

            <!-- Main content -->
            <section class="content">

                <div class="container-fluid">
                    <div class="row" id="formIncidentesManual">
                        <div class="col-md-5" style="margin-top:10px;">
                            <div class="card card-primary">
                                <div class="card-header">
									<div class="card-title">
										Nuevo Reporte Manual:
									</div>
								</div>	
                                <!-- /.card-header -->
                                <!-- form start -->
                               
                                    <div class="card-body">
                                       
									   
										<div class="form-group">
                                            <label for="nombre">Nombre de la persona que reporta:</label>
											<input type="text" class="form-control" name="nombre" />
                                            
                                        </div>
										
										<div class="form-group">
                                            <label for="cedula">Cédula o Pasaporte:</label>
											<input name="cedula" type="text" class="form-control" />
                                            
                                        </div>
										
										<div class="form-group">
                                            <label for="telefono">Teléfono:</label>
                                            <input name="telefono" type="text" class="form-control" />
                                        </div>
										
										<div class="form-group">
                                            <label for="email">Email:</label>
                                            <input name="email" type="mail" class="form-control" />
                                        </div>
										
										<div class="form-group">
                                            <label for="incidente">Tipo de Incidente: </label>
                                            <select name="incidente_id" id="incidente_id"  class="form-control" onchange="selectTexto(this)" data-inpid="incidentetxt">
												
												<option value="NO_SELECTED">seleccione</option>
												<?php while ($rowGetindicentes = mysqli_fetch_assoc($resGetindicentes)) { ?>
												<option value="<?php echo $rowGetindicentes['id']; ?>"><?php echo $rowGetindicentes['nombre'] ?></option>
												<?php } ?>
												
											</select>
											<input type="hidden" name="incidentetxt" id="incidentetxt" value=""/>
                                        </div>
										
										
										
										<div class="form-group">
                                            <label for="descripcion">Descripcion del Incidente:</label> 
											<textarea name="descripcion" class="form-control"></textarea>
                                            
                                        </div>
										
										
                                    </div>
                                    <!-- /.card-body -->

                               
                            </div>
                        </div>
						
						<div class="col-sm-7" style="margin-top:10px;">
							<div class="card card-primary">
								
								<div class="card-body">
									<label for="direccion">Dirección:</label>
										<div class="form-group row">
                                            
												<div class="col-sm-4">
													<label for="provincia">Provincia:</label>
													<select name="selProvincia" id="selProvincia" class="form-control" onchange="PreCambiaProvincias(this);selectTexto(this)" data-inpid="SSProvincia">
														<option value="NO_SELECTED">seleccione</option>
														<?php while ($rowGetProvincias = mysqli_fetch_assoc($resGetProvincias)) { ?>
														<option value="<?php echo $rowGetProvincias['codigo'] ?>"><?php echo $rowGetProvincias['nombre'] ?></option>
														<?php } ?>
													</select>
													<input type="hidden" name="provincia" id="SSProvincia" value=""/>
												</div>
												<div class="col-sm-4" id="selDistritoDiv" style="display:none">
													<label for="distrito">Distrito:</label>
													<select name="selDistrito" id="selDistrito" class="form-control" onchange="PreCambiaDistritos(this);selectTexto(this)" data-inpid="SSDistrito">
													<option value="NO_SELECTED">seleccione</option>
													</select>
													<input type="hidden" name="distrito" id="SSDistrito" value=""/>
												</div>
												<div class="col-sm-4" style="display:none" id="selCorregimientoDiv">
													<label for="corregimiento">Corregimiento:</label>
													<select name="selCorregimiento" id="selCorregimiento" class="form-control" onchange="PreCambiaCorregimientos(this);selectTexto(this)" data-inpid="SSCorregimiento">
														<option value="NO_SELECTED">seleccione</option>
													</select>
													<input type="hidden" name="corregimiento" id="SSCorregimiento" value=""/>
												</div>
												
											
										</div>
										
										<div class="form-group">
                                            <label for="direccion">Dirección exacta (barrio, calle, edificio, # de casa o apartamento):</label>
											<textarea name="direccion" class="form-control"></textarea>
                                         </div>
										
										<hr/>
										
                                            <label for="geopos">Ubicacion Geografica:</label>
										
										<div class="form-group row">
											<div class="col-sm-6">
												<label for="geolat">Latitud:</label>
												<input name="geolat" id="formgeolat" placeholder="Latitud" value="0" type="text" class="form-control"/>
											</div>
											<div class="col-sm-6">
											<label for="geolon">Longitud:</label>
											<input name="geolon" id="formgeolon" placeholder="Longitud" value="0" type="text" class="form-control"/>
											</div>
                                            
                                        </div>
										<hr/>
										
										<div class="form-group">
                                            <label for="estatus">Tipo de Estatus:</label>
                                      		<select name="newstatus" class="form-control">
													<option value="NO_SELECTED">- Seleccione - </option>
													<option value="5">Verificando</option>
													<option value="10">Atendido</option>
													<option value="99">Cancelado</option>
											</select>
										</div>
										
										<div class="form-group">
											<label for="estatus">Descripcion de Estatus:</label>
											<textarea name="newstatusdesc" class="form-control"></textarea>
										</div>
											
											<input type="hidden" name="userid" value="<?php echo $_SESSION['userid']; ?>" />
												
											
											<button tabindex="10" id="btn-add-new-user" type="submit" class="btn text-white" style="background-color: #3B82F6;" onclick="PreEnviaReport()">
                                            <center><img id="loader" style="display: none;" src="img/loader.gif" /></center>
                                            <span id="lbl"><b>Guardar Reporte</b></span>
                                        </button>
								
									
								</div>
								
								<div class="card-footer">
                                        

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
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="js/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="js/admin_lte/adminlte.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment-with-locales.min.js" integrity="sha512-42PE0rd+wZ2hNXftlM78BSehIGzezNeQuzihiBCvUEB3CVxHvsShF86wBWwQORNxNINlBPuq7rG4WWhNiTVHFg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
function selectTexto(th){
	var tt = $(th).val();
	var ttid = $(th).attr("id");
	var ttinpid = $(th).attr("data-inpid");
	if(tt != "NO_SELECTED"){
			var ttx = $("#"+ttid+" :selected").text();
			
			$("#"+ttinpid).val(ttx);
	}
	
}
//
function PreCambiaProvincias(th){ //this,distrito,corregimiento
			var prov = $(th).val();
			if(prov == "" || prov == "NO_SELECTED"){
				// Limpia Distritos
				var myLi = '<option value="" selected="selected">Seleccione Distrito</option>';
				$('#selDistrito').html(myLi);
				$("#selDistritoDiv").hide();
				
				// Limpia Corregimientos
				myLi = '<option value="NO_SELECTED" selected="selected">Seleccione Corregimiento</option>';
				$('#selCorregimiento').html(myLi);
				$("#selCorregimientoDiv").hide();
				
			}else{
				
				// Limpia Distritos
				var myLi = '<option value="NO_SELECTED" selected="selected">Seleccione Distrito</option>';
				$('#selDistrito').html(myLi);
				
				// Limpia Corregimientos
				var myLi = '<option value="NO_SELECTED" selected="selected">Seleccione Corregimiento</option>';
				$('#selCorregimiento').html(myLi);
				
				rellenaProvincias('selDistrito',1,prov);
				
				var myprovincia = $("#selProvincia option:selected").text();
				var data = encodeURI(myprovincia+", "+"Panamá");
				buscaGeoLat(data);	
				
			}
}//END PreCambiaProvincias

function PreCambiaDistritos(th,cc){ //this,corregimiento
		var dist = $(th).val();
		if(dist == "" || dist == "NO_SELECTED"){
			// Limpia Corregimientos
			var myLi = '<option value="NO_SELECTED" selected="selected">Seleccione Corregimiento</option>';
			$('#selCorregimiento').html(myLi);
			$("#selCorregimientoDiv").hide();
			
			var myprovincia = $("#selProvincia option:selected").text();
			var data = encodeURI(myprovincia+", "+"Panamá");
			buscaGeoLat(data);	
			
		}else{
			var myLi = '<option value="NO_SELECTED" selected="selected">Seleccione Corregimiento</option>';
			$('#selCorregimiento').html(myLi);
			
			rellenaProvincias('selCorregimiento',2,dist);
			var myprovincia = $("#selProvincia option:selected").text();
			var mydistrito = $("#selDistrito option:selected").text();
			var data = encodeURI(mydistrito+", "+myprovincia+", "+"Panamá");
			buscaGeoLat(data);
		}
}//END PreCambiaDistritos

function PreCambiaCorregimientos(th,cc,dd){
	var corr = $(th).val();
		if(corr == "" || corr == "NO_SELECTED"){
			var myprovincia = $("#selProvincia option:selected").text();
			var mydistrito = $("#selDistrito option:selected").text();
			var data = encodeURI(mydistrito+", "+myprovincia+", "+"Panamá");
			buscaGeoLat(data);	
			
		}else{
			var myprovincia = $("#selProvincia option:selected").text();
			var mydistrito = $("#selDistrito option:selected").text();
			var mycorregimiento = $("#selCorregimiento option:selected").text();
			var data = encodeURI(mycorregimiento+", "+mydistrito+", "+myprovincia+", "+"Panamá");
			buscaGeoLat(data);	
		}
}//

//
function rellenaProvincias(plat,lev,code){
	var myApi = "https://snpc-ws.aig.gob.pa/ws_data/getProvincias.php?level="+lev+"&code="+code;
	var levelname = ["Provincia","Distrito","Corregimiento"];
	var region = ["","Distrito","Corregimiento"];
	jQuery.getJSON( myApi, function( admdata ){
		var dropdown = $("#"+plat);
		var data = reorder(admdata); //reordenar alfabeticamente
		
		var rrtxt="<option value='NO_SELECTED'>Seleccione "+region[lev]+"</option>";
		jQuery.each( data, function( jj, vv ) {
			//dropdown.append($("<option />").val(vv.codigo).text(vv.nombre));
			rrtxt = rrtxt + "<option value='"+vv.codigo+"'>"+vv.nombre+"</option>";
			//console.log(vv.nombre);

		});
		dropdown.html(rrtxt);
		$("#"+plat+"Div").show();
	});
	
}//END rellenaProvincias

//
function reorder(dd){ //Reordenar Alfabeticamente por nombre
	
	ddarray = [];
	
	jQuery.each( dd, function( jj, vv ) {
		ddarray.push({"nombre":vv.nombre,"codigo":vv.codigo});	
	})
	var byName = ddarray.slice(0);
	byName.sort(function(a,b) {
		var x = a.nombre.toLowerCase();
		var y = b.nombre.toLowerCase();
		return x < y ? -1 : x > y ? 1 : 0;
	});
	
	return byName;

}//end reorderD

function buscaGeoLat(data){
 var getgeo = "https://nominatim.openstreetmap.org/search/"+data+"?format=json";
		 return fetch(getgeo, {
			  method: "GET",
			  headers: {"Content-type": "application/json;charset=UTF-8"}
			})
			.then(response => response.json()) 
			.then(json => {
					var dir = json[0];
					console.log(dir);
					
					if(dir.place_id > 3){
						$("#formgeolat").val(dir.lat);
						$("#formgeolon").val(dir.lon);
					}
				}
			)
			.catch(err => console.log(err));
}

function PreEnviaReport(){
		
		var dD = moment();
		var dDf = dD.format('YYYY-MM-DD HH:mm');
		var dDfID = dD.format('YYYYMMDDHH'); 
		// dBfID = Clave interna para Registros y Fotos
		
		var dT = dD.format('HH:mm');
		
		// Habilita los deshabilitados para envio
		$("#formIncidentesManual").find(':input:disabled').removeAttr('disabled'); 
		//
		
		var formdata = $("#formIncidentesManual").find(":input").serializeArray();//.serializeFormJSON();
		
		var verificaForma = formcheck();
		if(verificaForma > 0){
			Swal.fire('Incompleto','Debe completar los campos marcados en rojo','error');
			return false;
		}
		
		var data = {};
		
		//
		$(formdata).each(function(index, obj){
			dataTMParr = 0;
			if(data.hasOwnProperty(obj.name)){
				var tmpOO = [];
				
				if(!Array.isArray(data[obj.name])){
					tmpOO.push(data[obj.name]);
				
				}else{
					tmpOO = data[obj.name];
				
				}
				
				data[obj.name] = [];
				
				var ntmpOO = [obj.value];
				
				data[obj.name] = tmpOO.concat(ntmpOO);
				
				dataTMParr = 1;
			}else{
				data[obj.name] = obj.value;
			}
			// Verificar INPUTS adicionales que no se cuentan
			var notCounted = 0;
			var Oname = obj.name;
			
			if(Oname.indexOf("_caption") > -1){
				notCounted = 1;
			}
			
			if(Oname.indexOf("_gps") > -1){
				notCounted = 1;
			}
			
			if(Oname.indexOf("_stamp") > -1){
				notCounted = 1;
			}
			
			if(Oname.indexOf("geol") > -1){
				notCounted = 1;
			}
			
			
		});
		
		if(data.descripcion.length < 30){
			$("#preloader").hide();
			Swal.fire('','La descripción debe tener minimo 30 caracteres','error');
			$("[name='descripcion']").addClass("InValid");
			return false;
		}else{
			$("#descripcion").removeClass("InValid");
		}
		
		var fullID = data['cedula']+"_"+dDfID; 
		localdata = {};
		localdata[fullID] = {};
		localdata[fullID]["control"] = fullID;
		localdata[fullID]["fecha"] = dDf;
		localdata[fullID]["cedula"] = data["cedula"];
		localdata[fullID]["nombre"] = data["nombre"];
		localdata[fullID]["email"] = data["email"];
		localdata[fullID]["telefono"] = data["telefono"];
		
		localdata[fullID]["incidente_id"] = data["incidente_id"];
		localdata[fullID]["incidentetxt"] = data["incidentetxt"];
		localdata[fullID]["geolon"] = data["geolon"];
		localdata[fullID]["geolat"] = data["geolat"];
		localdata[fullID]["direccion"] = data["direccion"];
		localdata[fullID]["provincia"] = data["provincia"];
		localdata[fullID]["distrito"] = data["distrito"];
		localdata[fullID]["corregimiento"] = data["corregimiento"];
		localdata[fullID]["sector"] = data["sector"];
		
		localdata[fullID]["referencia"] = data["referencia"];
		localdata[fullID]["descripcion"] = data["descripcion"];
		localdata[fullID]["telefonos"] = data["telefonos"];
		localdata[fullID]["enviado"] = 0;
		localdata[fullID]["userid"] = data["userid"];
		localdata[fullID]["newstatus"] = data["newstatus"];
		localdata[fullID]["newstatusdesc"] = data["newstatusdesc"];
		//
		// Datos Estaticos
		
		//---------------------------------------------------
	
		// console.log(params["dataFull"]);
		
		todoSalvado = 1;
		EnviarReporte(localdata[fullID],fullID); //Enviamos Reporte	
		
	
}
//End SalvaForm

//
function formcheck(){
  var TodoMal = 0; // Si es Cero, los valores requeridos no sumaron
  //
  
  var fields = $("#formIncidentesManual").find(":input").serializeArray();
  
  $.each(fields, function(i, field) {
    var MalReq = 0;
	if (!field.value){
		MalReq = 1;
	}
	
	if(field.value === "NOCHECK"){
		MalReq = 1;
	}
	
	if(field.value === "NO_SELECTED"){
		MalReq = 1;
	}
	
	if(field.name === "selCorregimiento"){
		MalReq = 0;
	}
	
	if(field.name === "selCorregimiento"){
		MalReq = 0;
	}
	//
	
	
	if(MalReq){
      TodoMal = TodoMal + 1; //Cada vez que exista un valor requerido suma
	 
	  $("[name='"+field.name+"']").addClass("InValid");
	}else{
	  $("[name='"+field.name+"']").removeClass("InValid");
	 
	}
   });
  
  return TodoMal;
}//

function EnviarReporte(datos,control){
	var token = "oiweie2i0420ilekdwJJJKDKNWK99094904930402sdklskld1200OOIOOoooS45djjfu3i2di9293kdsjdk";
	$.ajax({
      type: "post",
      url: "./services/CrearReporteService.php",
      beforeSend: () => {
        $("#loader").css("display", "block");
        $("#lbl").css("display", "none");
      },
      data: {
        datos,
		control,
		token
      },
      success: (response) => {
        if (response.register == 12345) {
          Swal.fire(
            "Exito",
            "Datos registrados",
            "success"
          ).then(() => {
            $(location).attr("href", "reportes.php");
          });
		  
        }else{
			Swal.fire(
            "Error!",
            res.msg,
            "error"
          );
          return;
		}
        
      },
      complete: () => {
        $("#loader").css("display", "none");
        $("#lbl").css("display", "block");
      },
    });
}
//
</script>


</body>

</html>