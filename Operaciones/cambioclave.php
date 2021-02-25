<?php
include("../Conexion/conecta.php");
include("../utilidades/seguridad.php");
$conn   = new Conexion();
$connex = $conn->abrircon();
$cedula = trim($_POST['cedula']);
$clave1   = trim($_POST['clave1']);
$clave2   = trim($_POST['clave2']);
/*$cedula = 12913479;
$clave1   = 'SamVero#2020';
$clave2   = '123';*/

$clave_encriptada1 = $encriptar($clave1);
$clave_encriptada2 = $encriptar($clave2);


if(isset($_POST['cedula']) && $_POST['cedula'] != '' && isset($_POST['clave1']) && $_POST['clave1'] != ''){
$query = "select * from usuarios_nom where cedulas= :cedula and clave =:clave1 ";
	$buscar= $connex->prepare($query);
	$buscar->bindParam(':cedula', $cedula, PDO::PARAM_INT);
	$buscar->bindParam(':clave1', $clave_encriptada1, PDO::PARAM_STR);
	$buscar->execute();
	$count = $buscar->rowCount();
	$row   = $buscar->fetch(PDO::FETCH_ASSOC);
	if($count > 0 && !empty($row)){
		
		$sql = "UPDATE usuarios_nom SET  clave = :clave2 WHERE cedulas = :cedula;";

		$stmt= $connex->prepare($sql);

		$stmt->bindParam(':clave2', $clave_encriptada2, PDO::PARAM_STR);
		$stmt->bindParam(':cedula', $cedula, PDO::PARAM_INT);

			 if($stmt->execute()){
			 	echo "actualizado";

			 }
		
	}else{
		echo "no encontrada";

	}
}else{
	header('location:../index.php');
}

?>