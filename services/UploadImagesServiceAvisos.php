<?php
# -- UploadImagesService.php
date_default_timezone_set('America/Panama');
$valid_extensions = array('jpeg', 'jpg', 'png', 'gif', 'bmp' , 'pdf' , 'doc' , 'ppt'); // valid extensions
$path = '/mnt/snpc/public_html/filedata/avisos/'; // upload directory
$public = 'https://agroferias-ws.aig.gob.pa/filedata/avisos/';
if($_FILES['file']['name'] && isset($_POST['qhid'])){
	$img = $_FILES['file']['name'];
	$tmp = $_FILES['file']['tmp_name'];
	$qhid = $_POST['qhid'];

	// get uploaded file's extension
	$ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));
	$nowdate = date('Ymd_His');
	// can upload same image using rand function
	$final_image = $nowdate = "aviso_".$nowdate."_".rand(1000,1000000)."_".$img;

	// check's valid format
	if(in_array($ext, $valid_extensions)){ 
		$path = $path.strtolower($final_image); 

		if(move_uploaded_file($tmp,$path)) {
			echo $public.$final_image;
		
			//include database configuration file
			#include_once 'db.php';

			//insert form data in the database
			#$insert = $db->query("INSERT uploading (name,email,file_name) VALUES ('".$name."','".$email."','".$path."')");

			//echo $insert?'ok':'err';
		}	
	}else{
		echo 'invalid';
	}
}else{
	echo "invalid2";
}
	
?>