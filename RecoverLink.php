<?php
date_default_timezone_set('America/Panama');

if(isset($_GET['apikey']) && isset($_GET['token'])){
	$link = $_GET['apikey'];
	$ElId = $_GET['token'];
	include("./services/connection.php");
}else{
	exit("Error Dump Memory!");
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.1.2/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="icon" type="image/png" href="../img/favicon.png">

    <title>Editar Password | PanaVac</title>
	
	<style>
	.VertHorzCenter {
		text-align: center;
		width: 90%;
		max-width: 500px;
		padding: 25px;
		background-color: #fff;
		height: auto;
		margin: 0;
		position: absolute;
		top: 50%;
		left: 50%;
		-ms-transform: translate(-50%,-50%);
		transform: translate(-50%,-50%);
		-webkit-transform: translate(-50%,-50%);
		box-shadow: 0 0 15px #000;
	}
	a.brutto{
		    margin: 20px auto;
			padding: 10px;
			display: block;
			border: 3px solid #ccc;
			color: #ff0000;
			background-color: #ffff00;
			width: 90%;
			max-width: 300px;
			text-transform: uppercase;
			font-size: 13px;
			font-weight: bold;
	}
	</style>


</head>

<body id="vacucheckApp" style="background-repeat: no-repeat;background-attachment: fixed;background-size: cover;">

<?php

	//Verificar si el usuario existe en la tabla de restauro
	$sqlCheckUser = "select * from inadeh_users_restore upr where upr.user_id = '$ElId ' and upr.link = '$link';";
	$resCheckUser = mysqli_query($con, $sqlCheckUser);

	$rowCheckUser = $resCheckUser->fetch_assoc();

	$numRows = $resCheckUser->num_rows;

	if ($numRows == 1) {
		$linkdate = $rowCheckUser['created_at'];
		$to_time = time();
		$from_time = strtotime($linkdate);
		$difftime = abs($to_time - $from_time) / 60;
		
		if($difftime > 7){
			echo '<div class="VertHorzCenter"><h2>El enlace ha expirado. Solo dura 5 minutos.</h2>';
			echo '<a  class="brutto" href="https://vacunas.panamasolidario.gob.pa/GestionInadeh/sso_inadeh/RecoverPass.php">Solicitar otro enlace de recuperacion</a>';
			echo '</center>';
			echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>';
			echo '<script src="./js/main.js"></script></body></html>';
			exit();
		}
		
	}else{
		exit("Error Dump Params!");
	}

?>

    <div class="flex mt-8">

        <div class="lg:w-2/5 md:w-full w-full m-auto" style="width:94%;max-width:500px;margin:0 auto">
            <form class="bg-white shadow rounded px-8 pt-10 mb-4 pb-8">
                <img class="w-1/4 mx-auto max-w-48 pb-8" src="img/inadeh_asistencia.jpg" alt="">
                <h4 class="mb-4 mt-2 text-2xl font-light">Crear Nueva Contraseña:</h4>
                <div class="mb-4 relative flex w-full flex-wrap items-stretch">
                    <input value="" class="shadow  focus:ring h-12 appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="password" type="password" placeholder="Nueva Contraseña">
                </div>
				
				<div class="mb-4 relative flex w-full flex-wrap items-stretch">
                    <input value="" class="shadow  focus:ring h-12 appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="password2" type="password" placeholder="Repetir Contraseña">
                </div>
				
                <div class="flex items-center justify-between ">
                    <!--<input type="hidden" id="_token">-->
					
                    <button id="btn-check" class="text-center bg-blue-500 font-bold text-white mt-3 pt-3 pb-3 w-full py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="button">
                        <center><img id="loader" class="hidden" src="img/loader.gif" /></center>
                        <span id="lbl" class="block">Aceptar</span>
                    </button>
                </div>
            </form>

        </div>
    </div>
	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
     <script src="./js/cripto-js.min.js"></script>
	
    <script src="./js/main.js"></script>
    <script src="./js/recoverlink.js"></script>

<script>
	var linked = <?php echo "'".$link."'"; ?>;
	var elid = <?php echo "'".$ElId."'"; ?>;
	var _token = "DkeiDIaL023nd81200230293023jdsjnjehwe0";
</script>
</body>

</html>
