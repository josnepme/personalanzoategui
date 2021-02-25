<?php
include("../Conexion/conecta.php");
$conn         = new Conexion();
$connex       = $conn->abrircon();
$cedula       = trim($_POST['cedula']);
$tipo         = trim($_POST['tip']);
/*$cedula = '8237366';*/
// $tipo = '4';
/*$query = "SELECT DISTINCT
public.nominapago.concepto,
public.nominapagodetalle.id
FROM
public.nominapagodetalle
INNER JOIN public.nominapago ON (public.nominapagodetalle.nominapago = public.nominapago.id)
WHERE
public.nominapago.status = 'PR' AND
public.nominapago.tipopersonal = :tipo AND
public.nominapagodetalle.cedula = :cedula AND
nominapago.frecuencianomina <> 4 AND
nominapago.frecuencianomina <> 24
ORDER BY
nominapagodetalle.id";*/
$query        = "SELECT DISTINCT
public.nominapagodetalle.id,
public.frecuencianomina.frecuencia,
public.nominapago.fhasta,
public.nominapago.tipopersonal,
public.nominapago.fdesde
FROM
public.nominapagodetalle
INNER JOIN public.nominapago ON (public.nominapagodetalle.nominapago = public.nominapago.id)
INNER JOIN public.frecuencianomina ON (public.nominapago.frecuencianomina = public.frecuencianomina.id)
INNER JOIN public.nominapagodetalleconceptos ON (public.nominapagodetalle.id = public.nominapagodetalleconceptos.nominapagodetalle)
WHERE
public.nominapago.status = 'PR' AND
public.nominapago.tipopersonal = :tipo AND
public.nominapagodetalle.cedula = :cedula AND
nominapago.frecuencianomina <> 4 AND
nominapago.frecuencianomina <> 24 AND
nominapagodetalleconceptos.asignacion > 0
ORDER BY
nominapagodetalle.id";
$stmt         = $connex->prepare($query);
$stmt->bindParam(':cedula', $cedula, PDO::PARAM_STR);
$stmt->bindParam(':tipo', $tipo, PDO::PARAM_STR);
$stmt->execute();
$tipopersonal = '';
$id           = '';
$descripcion  = '';
$opcion       = '';

while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
	
	$fecha1 = date_create($row['fdesde']);
	$fecha2 = date_create($row['fhasta']);
	
     

	$descripcion = $row['frecuencia'].' DESDE: '.date_format($fecha1,"d/m/Y").' HASTA: '.date_format($fecha2,"d/m/Y").'.';
	$id          = $row['id'];

	$opcion .= "<option value='$row[id]'>$descripcion</option>";

	$data = array(

		'id'    => $id,
		'opcion'=> $opcion
	);


}
header('Content-type: application/json; charset=utf-8');
echo json_encode($data,JSON_FORCE_OBJECT);
?>