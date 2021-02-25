<?php
include("../Conexion/conecta.php");
$conn   = new Conexion();
$connex = $conn->abrircon();
$archivo= $_FILES['file'];
$cedula = $_POST['cedula'];
$nombre = '';
if(($_FILES["file"]["type"] == "image/ico")
	|| ($_FILES["file"]["type"] == "image/jpeg")
	|| ($_FILES["file"]["type"] == "image/png")
	|| ($_FILES["file"]["type"] == "image/gif")){

	$nombre           = $archivo['name'];
	$tipo             = $archivo['type'];
	$ruta_provisional = $archivo['tmp_name'];

	$size             = $archivo['size'];
	$carpeta_foto     = "../Recursos/fotos_personal/";

	$imgExt           = strtolower(pathinfo($nombre,PATHINFO_EXTENSION));




	// $src = $carpeta_foto . $nombre;
	$src              = $carpeta_foto . $cedula .'.'.$imgExt;
	$nombre           = $cedula .'.'.$imgExt;

	move_uploaded_file($ruta_provisional, $src);

	$sql              = "UPDATE usuarios_nom SET  foto = :foto WHERE cedulas = :cedula;";
	$stmt             = $connex->prepare($sql);

	$stmt->bindParam(':cedula', $cedula, PDO::PARAM_INT);
	$stmt->bindParam(':foto', $nombre, PDO::PARAM_STR);

	if($stmt->execute()){

		echo $src;
	}else{
		echo "../Recursos/user-png-icon-15.jpg";
	}




} else{
	echo 0;
}

?>