<?php

# ---- CrearReporteService---- 2023/04/10
# --- 
# ------------- JBENAVIDES (PISOFAREJULU) ---------

date_default_timezone_set('America/Panama');
include("connection.php");

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    $message = htmlentities("Solo se admite el método POST");
}


$out = 'invalid';


if(isset($_POST['keynote']) && isset($_POST['delta'])){
	
	if($_POST['keynote'] == 'sjdksjiUIdhywwdmnwhjUEHiE083890kws2989sdj8282'){
			
			$out = 'invalid';
			
			$out = CheckDatas($con,$_POST['delta']);
			
			mysqli_close($con);
			
			//************************
			
		}else{
			$out = "invalid";
		}
	}else{
		$out = "invalid";
	}


//header('Content-type: application/json');
//header('content-type:text/html;charset=utf-8');
echo json_encode($out,JSON_PRETTY_PRINT);

function CheckDatas($mysql,$dt){
	
	$nowdate = date('Y-m-d H:i:s');
	
	
	$sqlGetReports = "SELECT * FROM `mensajes_alertas` where activo = 1 order by modify_at desc";
	$tmp = [];
	$fila = [];
	$fila['data'] = [];
	$cc = 0;
	if($salida = $mysql -> query($sqlGetReports)){
		
		while($row = mysqli_fetch_assoc($salida))
            {
                $tmp[$cc] = $row;
				$cc = $cc + 1;
            }
			
			// print_r($tmp);
			
			$ii = 0;
			
		
		
			
			
			if($cc == 0){
				$tmp = 'invalid';
			}
			
			
			
	
 	}else{
		$tmp = 'invalid';
	}
	
	$fila['data'] = $tmp;
	
    return $fila; 
			
}
//
function addZero($aa){
	$a = (int)$aa;
	$num_padded = sprintf("%02d", $a);
	return $num_padded; // 
}

function getIPAddress() {  
    //whether ip is from the share internet  
     if(!empty($_SERVER['HTTP_CLIENT_IP'])) {  
                $ip = $_SERVER['HTTP_CLIENT_IP'];  
        }  
    //whether ip is from the proxy  
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {  
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];  
     }  
//whether ip is from the remote address  
    else{  
             $ip = $_SERVER['REMOTE_ADDR'];  
     }  
     return $ip;  
}  

function isMobile() {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}

?>
