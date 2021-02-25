<?php
include("../Conexion/conecta.php");
$conn             = new Conexion();
$connex           = $conn->abrircon();
$cedula           = trim($_POST['cedula']);
$correo           = trim($_POST['correo']);
/*$cedula           = 12913479;
$correo           = 'josnepme@gmail.com';*/


if(isset($_POST['cedula']) && $_POST['cedula'] != '' && isset($_POST['correo']) && $_POST['correo'] != ''){
	$sql = "UPDATE usuarios_nom SET  correo = :correo WHERE cedulas = :cedula;";
	$stmt= $connex->prepare($sql);

	$stmt->bindParam(':cedula', $cedula, PDO::PARAM_INT);
	$stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
	
	if($stmt->execute()){
			echo "actualizado";

	}else{
		    echo "error";
	}
	
}else{
	header('location:../index.php');
}




?>