<?php
include("../Conexion/conecta.php");
$conn        = new Conexion();
$connex      = $conn->abrircon();
$cedula = trim($_POST['cedula']);
//$cedula      = 12913479;

$nombre='';


$path        = '../Recursos/fotos_personal/';

$files       = glob($path .$cedula."*",GLOB_BRACE);

foreach($files as $file){
	if(is_file("$file")){
	
        $nombre=basename($file);
	}
}


if(isset($_POST['cedula']) && $_POST['cedula'] != ''){
$sql      = "UPDATE usuarios_nom SET  foto = :foto WHERE cedulas = :cedula;";
$stmt     = $connex->prepare($sql);

$stmt->bindParam(':cedula', $cedula, PDO::PARAM_INT);
$stmt->bindParam(':foto', $nombre, PDO::PARAM_STR);

if($stmt->execute()){
echo "actualizado";

}else{
echo "error";
}
}else{
header('location:../index.php');
}



?>