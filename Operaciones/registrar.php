<?php

include("../Conexion/conecta.php");
include("../utilidades/seguridad.php");

$conn             = new Conexion();
$connex           = $conn->abrircon();

$cedula           = trim($_POST['cedula']);
$nombre           = trim($_POST['nombre']);
$apellido         = trim($_POST['apellido']);
$usuario          = trim($_POST['user']);
$clave            = trim($_POST['password']);
$correo           = trim($_POST['correo']);
$combo            = trim($_POST['combo']);
$respuestas       = trim($_POST['respuestas']);
/*
$cedula           = 1;
$nombre           = "jos";
$apellido         = "me";
$usuario          = "jossss";
$clave            = "123";
$correo           = "jos@hto.com";
$combo            = "comb";
$respuestas       = "resp";
*/
$clave_encriptada = $encriptar($clave);

if(isset($_POST['user']) && $_POST['user'] != '' && isset($_POST['password']) && $_POST['password'] != ''&& isset($_POST['nombre']) && $_POST['nombre'] != ''
&& isset($_POST['apellido']) && $_POST['apellido'] != '' && isset($_POST['correo']) && $_POST['correo'] != '' & isset($_POST['combo']) && $_POST['combo'] != '' 
&& isset($_POST['respuestas']) && $_POST['respuestas'] != ''){


	$query = "select * from usuarios_nom where usuario =:username or cedulas =:cedula";
	$buscar= $connex->prepare($query);
	$buscar->bindParam(':username', $usuario, PDO::PARAM_STR);
	$buscar->bindParam(':cedula', $cedula, PDO::PARAM_STR);
	$buscar->execute();
	$count = $buscar->rowCount();
	$row   = $buscar->fetch(PDO::FETCH_ASSOC);
	if($count == 1 && !empty($row)){
		echo "encontrado";
	}else{
		$sql = "INSERT INTO usuarios_nom (usuario, clave, cedulas, nombres, apellidos, correo,pregunta, respuesta)
		VALUES(:usuario, :clave, :cedulas, :nombres, :apellidos, :correo, :pregunta, :respuesta)";

		$stmt= $connex->prepare($sql);

		$stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
		$stmt->bindParam(':clave', $clave_encriptada, PDO::PARAM_STR);
		$stmt->bindParam(':cedulas', $cedula, PDO::PARAM_INT);
		$stmt->bindParam(':nombres', $nombre, PDO::PARAM_STR);
		$stmt->bindParam(':apellidos', $apellido, PDO::PARAM_STR);
		$stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
        $stmt->bindParam(':pregunta', $combo, PDO::PARAM_STR);
        $stmt->bindParam(':respuesta', $respuestas, PDO::PARAM_STR);


		if($stmt->execute()){
			echo "insertado";

		}
	}



}else{
	header('location:../index.php');
}
?>