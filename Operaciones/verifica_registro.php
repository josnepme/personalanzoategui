<?php
/*
include("../Conexion/conecta.php");
$conn   = new Conexion();
$connex = $conn->abrircon();
$cedula = trim($_POST['cedula']);

if(isset($_POST['cedula'])){
$query = "select * from funcionario where documento =:cedula";
$stmt  = $connex->prepare($query);
$stmt->bindParam('cedula', $cedula, PDO::PARAM_STR);
$stmt->execute();
$count = $stmt->rowCount();
$row   = $stmt->fetch(PDO::FETCH_ASSOC);
if($count == 1 && !empty($row)){


$empleado = $row['documento'].", ".$row['nombres']." ".$row['apellidos'];
//echo "He recibido en el archivo.php: ".$_POST['cedula'];
echo $empleado;

}else{

echo "invalido";

}
}*/
include("../Conexion/conecta.php");
$conn   = new Conexion();
$connex = $conn->abrircon();
$cedula = trim($_POST['cedula']);

if(isset($_POST['cedula'])){
	$query = "select * from funcionario where documento =:cedula";
	$stmt  = $connex->prepare($query);
	$stmt->bindParam('cedula', $cedula, PDO::PARAM_STR);
	$stmt->execute();
	$count = $stmt->rowCount();
	$row   = $stmt->fetch(PDO::FETCH_ASSOC);
	if($count == 1 && !empty($row)){
		header('Content-Type: application/json');
        $nombres = $row['nombres'];
        $apellidos = $row['apellidos'];
		$datos = array(
			'estado'    => 'ok',
			'cedula'    => $cedula,
			'nombres'   => $nombres,
			'apellidos' => $apellidos

		);
		//Devolvemos el array pasado a JSON como objeto
		echo json_encode($datos, JSON_FORCE_OBJECT);
	}
}
?>