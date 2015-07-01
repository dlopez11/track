<?php

$conexion = mysql_connect("localhost", "sigmatrack_user", "S1gm4134ck100");
mysql_select_db("sigmamovil_track", $conexion);

	
$idUser = $_POST['idUser'];
$idVisitType = $_POST['idVisitType'];
$idClient = $_POST['idClient'];

if($_POST['date'] == "") {
	$date = Time();
}
else {
	$fechaString =$_POST['date'];
	$date = Time($fechaString);
}
$latitude = $_POST['latitude'];
$longitude = $_POST['longitude'];
$battery = $_POST['battery'];
$location = $_POST['location'];
$name = $_POST['nombreOtroCliente'];
$idAccount = $_POST['idCuenta'];

$name = trim($name);

// si no hay nombre de cliente registramos solamente la visita
if ($name == "" && isset($_POST['nombreOtroCliente'])) {
	if(isset($_POST['idUser']) && isset($_POST['idVisitType']) && isset($_POST['idClient']) && isset($_POST['date']) && isset($_POST['latitude']) && isset($_POST['longitude']) && isset($_POST['battery'])  && isset($_POST['location'])){ 
		$lastVisit = "SELECT date FROM visit WHERE idUser = {$idUser} ORDER BY date DESC LIMIT 0,1 ";
		
		$result = mysql_query($lastVisit, $conexion) or die(mysql_error());

		$lastTime = "No Disponible";
		if (mysql_num_rows($result) > 0) {
		   while ($rr = mysql_fetch_array($result)) {
			  $x = $rr["date"];
		   }
		   
		   $date1 = date('Y-m-d h:i', $x);
		   $date2 = date('Y-m-d h:i', time());
		   
		   $time1 = date_create($date1);
		   $time2 = date_create($date2);
		   
		   $interval = date_diff($time1, $time2);
		   $lastTime = $interval->format("%a día(s) %H:%I%");
		}
		
		
	
	  $queTareas = "INSERT INTO visit (idUser,idVisittype,idClient,date,latitude,longitude,battery,location,lastVisit) VALUES (".$idUser.",".$idVisitType.",".$idClient.",".$date.",".$latitude.",".$longitude.",".$battery.",'".$location."', '{$lastTime}')";
	  mysql_query($queTareas, $conexion) or die(mysql_error());
	  
	  echo '[{"0": "1","id": "1","1": "ok","respuesta": "ok"}]';
	  //echo "La visita se registro correctamente";
	} 
}
else if ($name != "" && isset($_POST['nombreOtroCliente'])) {
	$description = "No Disponible";
	$nit = "No Disponible";
	$address = "No Disponible";
	$phone = "No Disponible";
	$city = "No Disponible";
	$state = "No Disponible";
	$arry = array();
	$totalReg = 0;
	
	// primero validamos si el nombre del nuevo cliente ya existe
	
	$queTareas = "SELECT  LOWER(client.name), count(*) AS Total
				FROM account
				JOIN client USING (idAccount)
				WHERE  client.name = '" . strtolower($name) ."'
				AND idAccount = '" . $idAccount ."'
				GROUP BY 1
				LIMIT 1";
	
	$resTareas = mysql_query($queTareas, $conexion) or die(mysql_error());
	$totTareas= mysql_num_rows($resTareas);

	if ($totTareas > 0) {
	   while ($rowTareas = mysql_fetch_array($resTareas)) {
		  $arry[] = $rowTareas;
		  $totalReg = $rowTareas["Total"];
	   }
	}
	
	if ($totalReg > 0) {
	    //indica que el clienet existe
		echo '[{"0": "1","id": "1","1": "existe","respuesta": "existe"}]';
	}
	else {
		//si el nombre de cliente no existe lo guardamos
		$queryCliente = "INSERT INTO client (idAccount,created,updated,name,description,nit,address,phone,city,state) VALUES (".$idAccount.",".$date.",".$date.",'".$name."','".$description."','".$nit."','".$address."','".$phone."','".$city."','".$state."')";
		
		mysql_query($queryCliente, $conexion) or die(mysql_error());
	
	
	   $queryVisita = "INSERT INTO visit (idUser,idVisittype,idClient,date,latitude,longitude,battery,location) VALUES (".$idUser.",".$idVisitType.",". mysql_insert_id() .",".$date.",".$latitude.",".$longitude.",".$battery.",'".$location."')";
	   mysql_query($queryVisita, $conexion) or die(mysql_error());
	   echo '[{"0": "1","id": "1","1": "ok","respuesta": "ok"}]';
	}
	
}
 
?>

