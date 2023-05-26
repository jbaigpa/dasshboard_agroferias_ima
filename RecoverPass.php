<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.1.2/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="icon" type="image/png" href="../img/favicon.png">

    <title>Recuperar Password | INADEH ASISTENCIA</title>


</head>

<body id="vacucheckApp" style="background-repeat: no-repeat;background-attachment: fixed;background-size: cover;" onload="MyGetCaptcha()">

    <div class="flex mt-8">

        <div class="lg:w-2/5 md:w-full w-full m-auto" style="width:94%;max-width:500px;margin:0 auto">
            <form class="bg-white shadow rounded px-8 pt-10 mb-4 pb-8">
                <img class="w-1/4 mx-auto max-w-48 pb-8" src="img/inadeh_asistencia.jpg" alt="">
                <h4 class="mb-4 mt-2 text-2xl font-light">Recuperar Contraseña:</h4>
                <div class="mb-4 relative flex w-full flex-wrap items-stretch">
                    <input value="" class="shadow  focus:ring h-12 appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="user" type="text" placeholder="Ingrese su cédula o usuario">
                </div>
				<div style="display: none;">
					<label for="mysex">Mi Sexo</label>
					<input type="text" name="mysex" name="mysex" value=""/>
				</div>
				
				<div style="display: none;">
					<label for="myjob">Mi Job</label>
					<input type="text" name="myjob" name="myjob" value="" />
				</div>
                
				<div class="h-captchas" id="ElCaptcha" nodata-sitekey="c4089e02-35f3-4088-9e7c-a68e23c82c4a"></div>
				
                <div class="flex items-center justify-between ">
                    <!--<input type="hidden" id="_token">-->
					
                    <button id="btn-login" class="text-center bg-blue-500 font-bold text-white mt-3 pt-3 pb-3 w-full py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="button">
                        <center><img id="loader" class="hidden" src="img/loader.gif" /></center>
                        <span id="lbl" class="block">Enviar</span>
                    </button>
                </div>
            </form>

        </div>
    </div>
	
	 
    <script src='https://www.hCaptcha.com/1/api.js' async defer></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    
	
    <script src="./js/main.js"></script>
    <script src="./js/recoverpass.js"></script>
</body>

</html>
