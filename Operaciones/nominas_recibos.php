<?php
include("../Conexion/conecta.php");
$conn   = new Conexion();
$connex = $conn->abrircon();
$cedula = trim($_POST['cedula']);
 //$cedula = '8237366';
$query = "SELECT DISTINCT 
  public.nominapago.tipopersonal,
  public.tipopersonal.tipo,
  public.nominapagodetalle.cedula
FROM
  public.nominapagodetalle
  INNER JOIN public.nominapago ON (public.nominapagodetalle.nominapago = public.nominapago.id)
  INNER JOIN public.tipopersonal ON (public.nominapago.tipopersonal = public.tipopersonal.id)
WHERE
  nominapagodetalle.cedula = :cedula AND 
  nominapago.status = 'PR'
ORDER BY
  nominapago.tipopersonal";
	$stmt  = $connex->prepare($query);
	$stmt->bindParam(':cedula', $cedula, PDO::PARAM_STR);
    $stmt->execute();
    $tipopersonal='';
    $tipo='';
    $opcion='';
   
  while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
  	
  	$tipopersonal   = $row['tipopersonal'];
	$tipo = $row['tipo'];
	
    $opcion .= "<option value='$row[tipopersonal]'>$row[tipo]</option>";
    
  $data = array(
			
			'tipo'    => $tipo,
			//'cargo' => $cargo,
			'opcion'=> $opcion
	);  
    
    
  }
  header('Content-type: application/json; charset=utf-8');
  echo json_encode($data,JSON_FORCE_OBJECT);
?>