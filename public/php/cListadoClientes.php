<?php

$idUsuario = $_GET['idUsuario'];

// quitamos los espacios en blanco
trim($_GET['idUsuario']);

preg_replace('/\&(.)[^;]*;/', '\\1', $_GET['idUsuario']);



if(isset($idUsuario)){ 

	$conexion = mysql_connect("localhost", "sigmatrack_user", "S1gm4134ck100");
	mysql_select_db("sigmamovil_track", $conexion);
	 
	$queTareas = "SELECT client.idClient, client.name
				FROM user 
					JOIN account USING (idAccount)
					JOIN client USING (idAccount)
				WHERE idUser = " . $idUsuario ."
				ORDER BY 2 ASC";
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

