<?php
include("../Conexion/conecta.php");
$conn     = new Conexion();
$connex   = $conn->abrircon();
$cedula   = trim($_POST['cedula']);
$pregunta = trim($_POST['pregunta']);
$respuesta= trim($_POST['respuesta']);
/*
$cedula   = 12913479;
$pregunta = 'color favorito';
$respuesta= 'verde';
*/
if(isset($_POST['cedula']) && $_POST['cedula'] != '' && isset($_POST['respuesta']) && $_POST['respuesta'] != ''){
$sql      = "UPDATE usuarios_nom SET  pregunta = :pregunta, respuesta= :respuesta WHERE cedulas = :cedula;";
$stmt     = $connex->prepare($sql);

$stmt->bindParam(':cedula', $cedula, PDO::PARAM_INT);
$stmt->bindParam(':pregunta', $pregunta, PDO::PARAM_STR);
$stmt->bindParam(':respuesta',$respuesta, PDO::PARAM_STR);

if($stmt->execute()){
	echo "actualizado";

}else{
	echo "error";
}
}else{
	header('location:../index.php');
}

?>