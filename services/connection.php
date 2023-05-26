<?php

//
	$databasehost = '10.253.115.69';
	$databasename = 'ferias_ima'; 
	$databaseusername ='feriasima';
	$databasepassword = 'Ferias#Ima2023';
	$tbl = "";
	$secret = "%++3s@2MLKdgX-kw";
	$puerto = 3306;

$con = mysqli_connect($databasehost, $databaseusername, $databasepassword, $databasename) or die(mysqli_connect_error());

mysqli_set_charset($con, "utf8");

if ($con->connect_errno) {
    echo "Failed to connect to MySQL: " . $con->connect_error;
    exit();
}
