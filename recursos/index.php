<?php
	include("../includes/txtApp.php");
	session_start();
	if($_SERVER["HTTP_REFERER"]==""){
		echo "<script type='text/javascript'> alert('Acceso No Autorizado a Area Administrativa'); </script>";
		header("Location: ../modulos/mod_login/index.php");
		exit;
	}
	if(!isset($_SESSION[$txtApp['session']['name']])){
		header("Location: ../modulos/mod_login/index.php");
		exit;	
	}
?>