<?php
	

  include("../Conexion/conecta.php");
$conn   = new Conexion();
$connex = $conn->abrircon();
$cedula = trim($_POST['cedula']);
// $cedula = '8237366';
$query = "SELECT fichapersonal.id,fichapersonal.cargo
	FROM   fichapersonal
	INNER JOIN tipopersonal ON (fichapersonal.tiponomina = tipopersonal.id)
	INNER JOIN funcionario ON (fichapersonal.funcionario = funcionario.id)
	INNER JOIN situacion ON (fichapersonal.situacion = situacion.id)
	WHERE
	funcionario.documento =:cedula and situacion.situacion = 'ACTIVO'";
	$stmt  = $connex->prepare($query);
	$stmt->bindParam(':cedula', $cedula, PDO::PARAM_STR);
    $stmt->execute();
    $id='';
    $cargo='';
    $opcion='';
   
  while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
  	
  	$id    = $row['id'];
	$cargo = $row['cargo'];
    $opcion .= "<option value='$row[id]'>$row[cargo]</option>";
    
  $data = array(
			
			//'id'    => $id,
			//'cargo' => $cargo,
			'opcion'=> $opcion
	);  
    
    
  }
  header('Content-type: application/json; charset=utf-8');
  echo json_encode($data,JSON_FORCE_OBJECT);



?>