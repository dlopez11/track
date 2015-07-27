<?php
if(isset($_POST['idusuario'])) {
	$limite = $_POST['limite'];
	
	
	$conexion = mysql_connect("localhost", "sigmatrack_user", "S1gm4134ck100");
	mysql_select_db("sigmamovil_track", $conexion);
	
	$queTareas = "SELECT idVisit, idUser, name, DATE_FORMAT(date(from_unixtime(start)),'%d/%M/%Y') AS fechaInicio, TIME_FORMAT(time(from_unixtime(start)), '%h:%i') AS horaInicio, DATE_FORMAT(date(from_unixtime(end)),'%d/%M/%Y') AS fechaFinal, TIME_FORMAT(time(from_unixtime(end)),'%h:%i') AS horaFinal
				FROM visit JOIN client USING(idClient)
				WHERE idUser = " . $_POST['idusuario'] . "
				ORDER BY 1 DESC LIMIT " . $limite;
	$resTareas = mysql_query($queTareas) or die(mysql_error());
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