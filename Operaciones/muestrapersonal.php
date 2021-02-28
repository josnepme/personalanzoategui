<?php
include("../Conexion/conecta.php");
$conn   = new Conexion();
$connex = $conn->abrircon();
$cedula = trim($_POST['cedula']);
//$cedula = 9671952;

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
	public.funcionario.telefomovil,
	COALESCE((SELECT count(DATE_PART('year', age(fechanacimiento))) from  funcionariohijos
	WHERE (DATE_PART('year', age(fechanacimiento))) <= 18  AND
	funcionariohijos.funcionario = funcionario.id
	GROUP BY funcionariohijos.funcionario
	ORDER BY funcionariohijos.funcionario),0) AS menores,
	COALESCE((SELECT sum(hasta::date - desde::date) from  funcionariolaboral
	WHERE funcionariolaboral.funcionario = funcionario.id AND
	funcionariolaboral.area = 'G'
	GROUP BY funcionariolaboral.funcionario
	ORDER BY funcionariolaboral.funcionario),0) AS antiguedad,
	fichapersonal.fechaingreso
	FROM

	public.funcionario

	INNER JOIN public.fichapersonal ON (public.fichapersonal.funcionario = public.funcionario.id)
	LEFT OUTER JOIN public.funcionariolaboral ON (public.funcionario.id = public.funcionariolaboral.funcionario)

	where funcionario.documento = :cedula";
	$stmt  = $connex->prepare($query);
	$stmt->bindParam('cedula', $cedula, PDO::PARAM_STR);
	$stmt->execute();
	$count = $stmt->rowCount();
	$row   = $stmt->fetch(PDO::FETCH_ASSOC);
	if($count >= 1 && !empty($row)){
		$fechan     = date_create($row['fachanacimiento']);

		$antiguedad = $row['antiguedad'];
		$fing       = $row['fechaingreso'];

		$now        = date("Y-m-d");

		$tservicio  = (strtotime($fing) - strtotime($now)) / 86400;
		$tservicio  = abs($tservicio);
		$dias       = floor($tservicio);

		$antiguedad = $antiguedad + $tservicio;

		$diasant    = $antiguedad / 365;

		$entero     = explode(".",$diasant);

		$antiguedad = $entero[0];


		$datos      = array(

			'nacionalidad' => $row['nacioanlidad'],
			'cedula'       => $cedula,
			'nombres'      => $row['nombres'],
			'apellidos'    => $row['apellidos'],
			'sexo'         => $row['sexo'],
			'fechan'       => date_format($fechan,"d/m/Y"),
			'lnac'         => $row['lnacimiento'],
			'edocivil'     => $row['estadocivil'],
			'direccion'    => $row['direccion'],
			'telefonofijo' => $row['telefonofijo'],
			'telefonomovil'=> $row['telefomovil'],
			'hijos'        => $row['menores'],
            'antiguedad'   => $antiguedad


		);
		header('Content-Type: application/json');
		echo json_encode($datos, JSON_FORCE_OBJECT);
	}
}else{
	header('location:../index.php');
}

?>