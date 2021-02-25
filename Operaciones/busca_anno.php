<?php
include("../Conexion/conecta.php");
$conn   = new Conexion();
$connex = $conn->abrircon();
$cedula = trim($_POST['cedula']);
 //$cedula = '12913479';
$query = "SELECT DISTINCT 
  EXTRACT(year FROM nominapago.fhasta) AS ano
FROM
  public.nominapagodetalle
  INNER JOIN public.nominapago ON (public.nominapagodetalle.nominapago = public.nominapago.id)
  INNER JOIN public.nominapagodetalleconceptos ON (public.nominapagodetalle.id = public.nominapagodetalleconceptos.nominapagodetalle)
WHERE
  public.nominapago.status = 'PR' AND 
  public.nominapagodetalle.cedula = :cedula AND 
  nominapago.frecuencianomina <> 24 
GROUP BY
   ano
ORDER BY
 EXTRACT(year FROM nominapago.fhasta) ASC ";
	$stmt  = $connex->prepare($query);
	$stmt->bindParam(':cedula', $cedula, PDO::PARAM_STR);
    $stmt->execute();
 	$annos='';
    
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
  	
  
  echo  $annos = "<option value='$row[ano]'>$row[ano]</option>";
    
   /*  $data = array(
			
			//'id'    => $id,
			//'cargo' => $cargo,
			'annos'=> $annos
	);  */
    
   //  header('Content-type: application/json; charset=utf-8');
  //   echo json_encode($data,JSON_FORCE_OBJECT);
  }
    
    
    
?>