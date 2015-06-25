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


// si no hay nombre de cliente registramos solamente la visita
if ($name == "" && isset($_POST['nombreOtroCliente'])) {
	if(isset($_POST['idUser']) && isset($_POST['idVisitType']) && isset($_POST['idClient']) && isset($_POST['date']) && isset($_POST['latitude']) && isset($_POST['longitude']) && isset($_POST['battery'])  && isset($_POST['location'])){ 
	  $queTareas = "INSERT INTO visit (idUser,idVisittype,idClient,date,latitude,longitude,battery,location) VALUES (".$idUser.",".$idVisitType.",".$idClient.",".$date.",".$latitude.",".$longitude.",".$battery.",'".$location."')";
	  mysql_query($queTareas, $conexion) or die(mysql_error());
	 
	  echo "La visita se registro correctamente";
	} 
}
else if ($name != "" && isset($_POST['nombreOtroCliente'])) {
	$description = "Cliente nuevo";
	$nit = "11111111";
	$address = "Av 4 N 6-67";
	$phone = "6618330";
	$city = "Cali";
	$state = "Activo";
	
	$queryCliente = "INSERT INTO client (idAccount,created,updated,name,description,nit,address,phone,city,state) VALUES (".$idAccount.",".$date.",".$date.",'".$name."','".$description."','".$nit."','".$address."','".$phone."','".$city."','".$state."')";
	mysql_query($queryCliente, $conexion) or die(mysql_error());
	
	
	 $queryVisita = "INSERT INTO visit (idUser,idVisittype,idClient,date,latitude,longitude,battery,location) VALUES (".$idUser.",".$idVisitType.",". mysql_insert_id() .",".$date.",".$latitude.",".$longitude.",".$battery.",'".$location."')";
	  mysql_query($queryVisita, $conexion) or die(mysql_error());
	 
	  echo "La visita al nuevo cliente se registro correctamente";
}
 
?>

