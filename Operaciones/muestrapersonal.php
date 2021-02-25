<?php
include("../Conexion/conecta.php");
$conn   = new Conexion();
$connex = $conn->abrircon();
$cedula = trim($_POST['cedula']);
//$cedula = 12913479;

if(isset($_POST['cedula'])){
	$query = "SELECT DISTINCT 
  public.funcionario.nacioanlidad,
  public.funcionario.documento,
  public.funcionario.nombres,
  public.funcionario.apellidos,
  public.funcionario.fachanacimiento,
  public.funcionario.lnacimiento,
  public.funcionario.sexo,
  public.funcionario.estadocivil,
  public.funcionario.direccion,
  public.funcionario.telefonofijo,
  public.funcionario.telefomovil
  
FROM
  public.funcionario where documento =:cedula";
	$stmt  = $connex->prepare($query);
	$stmt->bindParam('cedula', $cedula, PDO::PARAM_STR);
	$stmt->execute();
	$count = $stmt->rowCount();
	$row   = $stmt->fetch(PDO::FETCH_ASSOC);
	if($count == 1 && !empty($row)){
		$fechan = date_create($row['fachanacimiento']);
		$datos = array(
		
			'nacionalidad'    => $row['nacioanlidad'],
			'cedula'    => $cedula,
			'nombres'   => $row['nombres'],
			'apellidos' => $row['apellidos'],
		    'sexo' => $row['sexo'],
		    'fechan' => date_format($fechan,"d/m/Y"),
		    'lnac' => $row['lnacimiento'],
            'edocivil' => $row['estadocivil'],
            'direccion' => $row['direccion'],
            'telefonofijo' => $row['telefonofijo'],
            'telefonomovil' => $row['telefomovil']
            
            
		);
		header('Content-Type: application/json');
		echo json_encode($datos, JSON_FORCE_OBJECT);
	}
}else{
	header('location:../index.php');
}

?>