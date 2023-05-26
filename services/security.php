<?php 
    session_start();

    if(!$_SESSION['isLogged']){
        header("location: ./");
    }
?>