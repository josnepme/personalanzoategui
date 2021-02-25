<?php
include("../Conexion/conecta.php");
$conn   = new Conexion();
$connex = $conn->abrircon();
$cedula = trim($_POST['cedula']);
$usuario   = trim($_POST['usuario']);
/*$cedula = 12913479;
$usuario   = 'josnepme';*/
if(isset($_POST['usuario']) && $_POST['usuario'] != '' && isset($_POST['cedula']) && $_POST['cedula'] != ''){



	$query = "select * from usuarios_nom where usuario =:username ";
	$buscar= $connex->prepare($query);
	$buscar->bindParam(':username', $usuario, PDO::PARAM_STR);
	$buscar->execute();
	$count = $buscar->rowCount();
	$row   = $buscar->fetch(PDO::FETCH_ASSOC);
	if($count > 0 && !empty($row)){
		echo "encontrado";
	}else{
		$sql = "UPDATE usuarios_nom SET  usuario = :usuario WHERE cedulas = :cedula;";

		$stmt= $connex->prepare($sql);

		$stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
		$stmt->bindParam(':cedula', $cedula, PDO::PARAM_INT);

			 if($stmt->execute()){
			 	echo "actualizado";

			 }

	}
	

}else{
	header('location:../index.php');
}
?>