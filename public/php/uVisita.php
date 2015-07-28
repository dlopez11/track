<?php
if(isset($_POST['idVisit'])) {
	$idVisit = $_POST['idVisit'];
	$latitude = $_POST['latitude'];
	$longitude = $_POST['longitude'];
	$location = $_POST['location'];
	
	$date = Time();
	
	$conexion = mysqli_connect("localhost", "sigmatrack_user", "S1gm4134ck100", "sigmamovil_track");
	
	if (!$conexion) {
		printf("No se puede conectar a la base de datos. Error: %s\n", mysqli_connect_error());
		exit();
	}
	
	mysqli_query($conexion,  "UPDATE visit set end = " . $date . ", finalLatitude = '" . $latitude . "', finalLongitude = '"  . $longitude . "' ,finalLocation = '" . $location .  "' WHERE idVisit = " .  $idVisit);
    $resultado = mysqli_affected_rows($conexion);
		 
	echo '[{"0": "1","id": "1","1": "' . $resultado .'","respuesta": "' . $resultado . '"}]';
	
}
?>
