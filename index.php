<?php
if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
    else
    {
        session_destroy();
        session_start(); 
    }

if (@$_SESSION['isLogged']) {
    //header("location: menu.php");
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

    <title>Módulo de Administración IMA AGROFERIAS</title>


</head>

<body id="vacucheckApp" style="background-repeat: no-repeat;background-attachment: fixed;background-size: cover;" >

<!-- onload="MyGetCaptcha()" -->

    <div class="flex justify-center items-center min-h-screen">

        <div class="lg:w-2/5 md:w-full w-full m-auto">
            <form class="bg-white shadow rounded px-6 pt-8 mb-4 pb-8" style="width:92%;max-width:480px;margin:0 auto">
                <img class="w-1/3 mx-auto max-w-48 pb-4" src="img/agroferias_logo.png" alt="">
                <h4 class="mb-4 mt-2 text-xl font-light">Sistema de Gestion:</h4>
                <div class="mb-4 relative flex w-full flex-wrap items-stretch">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-2">
						<img src="./img/usericon.png" style="width: 20px;height: 20px;"/>
					</span>
					<input value="" class="shadow  focus:ring h-12 appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline pl-10
					" id="user" type="text" placeholder="Ingrese su usuario">
                </div>
				
                <div class="mb-4 relative flex w-full flex-wrap items-stretch">
					<span class="absolute inset-y-0 left-0 flex items-center pl-2">
						<svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24" viewBox="0 0 24 24" style=" fill:#CCCCCC;"><path d="M 12 1 C 8.6761905 1 6 3.6761905 6 7 L 6 8 C 4.9069372 8 4 8.9069372 4 10 L 4 20 C 4 21.093063 4.9069372 22 6 22 L 18 22 C 19.093063 22 20 21.093063 20 20 L 20 10 C 20 8.9069372 19.093063 8 18 8 L 18 7 C 18 3.6761905 15.32381 1 12 1 z M 12 3 C 14.27619 3 16 4.7238095 16 7 L 16 8 L 8 8 L 8 7 C 8 4.7238095 9.7238095 3 12 3 z M 6 10 L 18 10 L 18 20 L 6 20 L 6 10 z M 12 13 C 10.9 13 10 13.9 10 15 C 10 16.1 10.9 17 12 17 C 13.1 17 14 16.1 14 15 C 14 13.9 13.1 13 12 13 z"></path></svg>
					</span>
                    <input value="" class="shadow  focus:ring h-12 appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline pl-10" id="password" type="password" placeholder="Ingrese su contraseña">
                </div>
				<div style="display: none;">
					<label for="mysex">Mi Sexo</label>
					<input type="text" name="mysex" name="mysex" value=""/>
				</div>
				
				<div style="display: none;">
					<label for="myjob">Mi Job</label>
					<input type="text" name="myjob" name="myjob" value="" />
				</div>
				
				<!--<div class="h-captchas" id="ElCaptcha" nodata-sitekey="c4089e02-35f3-4088-9e7c-a68e23c82c4a"></div>--!>
				
                <div class="flex items-center justify-between ">
                    <!--<input type="hidden" id="_token">-->
					
                    <button id="btn-login" class="text-center bg-blue-500 font-bold text-white mt-3 pt-3 pb-3 w-full py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="button">
                        <center><img id="loader" class="hidden" src="img/loader.gif" /></center>
                        <span id="lbl" class="block">Iniciar sesión</span>
                    </button>
                </div>
            
			
			    <div style="text-align: right;margin: 10px auto;">
                   
				   <a id="recovermypass" href="https://vacunas.panamasolidario.gob.pa/GestionInadeh/sso_inadeh/RecoverPass.php" style="
					padding: 6px 6px 3px 6px;
					display: inline-block;
					border-bottom: 2px solid #4D4D4D;">
                        <span class="block" style="font-size:0.9em;color:#4D4D4D">Recuperar Contraseña?</span>
                    </a>
                </div>
            </form>
        </div>
    </div>
	
	 
    <!--<script src='https://www.hCaptcha.com/1/api.js' async defer></script>-->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    
	<!--<script src="https://www.google.com/recaptcha/api.js?render=6LeiVcYaAAAAAJdD1cpDx2jP8PSGokBgnG9hKDob"></script>-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="./js/cripto-js.min.js"></script> 
	
	
    <script src="./js/main.js"></script>
    <script src="./js/index.js"></script>
</body>

</html>
