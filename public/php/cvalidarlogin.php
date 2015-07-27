<?php

$idEmpresa = $_GET['idEmpresa'];
$usuario = $_GET['usuario'];
$clave = $_GET['clave'];

// quitamos los espacios en blanco
trim($_GET['idEmpresa']);
trim($_GET['usuario']);
trim($_GET['clave']);

preg_replace('/\&(.)[^;]*;/', '\\1', $_GET['idEmpresa']);
preg_replace('/\&(.)[^;]*;/', '\\1', $_GET['usuario']);
preg_replace('/\&(.)[^;]*;/', '\\1', $_GET['clave']);

if(isset($_GET['idEmpresa']) && isset($_GET['usuario']) && isset($_GET['clave'])){
	
	$conexion = mysql_connect("localhost", "sigmatrack_user", "S1gm4134ck100");
	mysql_select_db("sigmamovil_track", $conexion);
	 
	$queTareas = "SELECT COUNT(*) As total,idUser, idAccount, userName, password
					FROM user
					JOIN account USING (idAccount) 
					WHERE userName ='" . $usuario . "' 
					AND password = '" . $clave . "'
					AND idAccount =" . $idEmpresa;
	$resTareas = mysql_query($queTareas, $conexion) or die(mysql_error());
	$totTareas= mysql_num_rows($resTareas);
	 
	$arr = array();
	 
	if ($totTareas> 0) {
	   while ($rowTareas = mysql_fetch_array($resTareas)) {
		  $arr[] = $rowTareas;
	   }
	}
	
	echo json_encode($arr);
}

?>







