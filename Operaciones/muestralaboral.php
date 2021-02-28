<?php
//header('Content-Type: text/html;charset=ISO-8859-1');
include("../Conexion/conecta.php");
include("../Operaciones/globales.php");


$conn   = new Conexion();
$connex = $conn->abrircon();
$cedula       = trim($_POST['cedula']);
$tipop       = trim($_POST['tip']);
/*$tipop= 2;
$cedula = 12913479;*/
function campoadicionalper($idcampo,$ficha){
	$conn   = new Conexion();
	$connex = $conn->abrircon();
	$query  = "Select valor From fichapersonalcampoa Where campoadicional = ".$idcampo." and fichapersonal = ".$ficha;
	$stmt   = $connex->prepare($query);
	$stmt->execute();

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		$valor = $row['valor'];

		return $valor;
	}
}

function concepto($codnom,$ficha,$cconcepto){
	$conn   = new Conexion();
	$connex = $conn->abrircon();

	$query  = "SELECT
	nominapagodetalleconceptos.asignacion,
	nominapagodetalleconceptos.deduccion,
	nominapagodetalleconceptos.aporte
	FROM nominapagodetalleconceptos
	INNER JOIN nominapagodetalle
	ON nominapagodetalleconceptos.nominapagodetalle = nominapagodetalle.id
	WHERE  nominapagodetalle.fichapersonal = ".$ficha
	." AND nominapagodetalleconceptos.concepto = ".$cconcepto
	." AND nominapagodetalle.nominapago = ".$codnom;


	$stmt   = $connex->prepare($query);

	$stmt->execute();

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)){

		$valor = $row['asignacion'] + $row['deduccion'] + $row['aporte'];

		return $valor;
	}
}

function conceptovarios($codnom,$ficha,$cconcepto){
	$conn   = new Conexion();
	$connex = $conn->abrircon();
	$query  = "SELECT
	sum(nominapagodetalleconceptos.asignacion) as asignacion,
	sum(nominapagodetalleconceptos.deduccion) as deduccion,
	sum(nominapagodetalleconceptos.aporte) as aporte
	FROM nominapagodetalleconceptos
	INNER JOIN nominapagodetalle
	ON nominapagodetalleconceptos.nominapagodetalle = nominapagodetalle.id
	WHERE  nominapagodetalle.fichapersonal = ".$ficha
	." AND nominapagodetalleconceptos.concepto in (".$cconcepto.")"
	." AND nominapagodetalle.nominapago = ".$codnom;

	$stmt   = $connex->prepare($query);
	$stmt->execute();

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)){

		$valor = $row['asignacion'] + $row['deduccion'] + $row['aporte'];

		return $valor;
	}
}


function conceptoperiodo($ficha,$codcon,$desde,$hasta){
	$conn   = new Conexion();
	$connex = $conn->abrircon();
	list($ano,$mes,$dia) = explode('-',$desde);
	list($ano1,$mes1,$dia1) = explode('-',$hasta);
	$query = "SELECT SUM(nominapagodetalleconceptos.asignacion)as asigtotal FROM nominapagodetalleconceptos
	INNER JOIN nominapagodetalle ON nominapagodetalleconceptos.nominapagodetalle = nominapagodetalle.id
	INNER JOIN nominapago ON nominapagodetalle.nominapago = nominapago.id
	WHERE nominapago.fhasta >='$desde' and nominapago.fhasta<='$hasta'
	AND nominapagodetalleconceptos.concepto in ($codcon)
	AND nominapagodetalle.fichapersonal='$ficha'";

	$stmt  = $connex->prepare($query);
	$stmt->execute();


	while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		$valor = $row['asigtotal'];

		return $valor;
	}
}


function conceptoperiodocant($ficha,$codcon,$desde, $hasta){

	$conn   = new Conexion();
	$connex = $conn->abrircon();

	list($ano,$mes,$dia) = explode('-',$desde);
	list($ano1,$mes1,$dia1) = explode('-',$hasta);
	$query = "SELECT COUNT(nominapagodetalleconceptos.asignacion)as asigtotal FROM nominapagodetalleconceptos
	INNER JOIN nominapagodetalle ON nominapagodetalleconceptos.nominapagodetalle = nominapagodetalle.id
	INNER JOIN nominapago ON nominapagodetalle.nominapago = nominapago.id
	WHERE nominapago.fhasta >='$desde' and nominapago.fhasta<='$hasta'
	AND nominapagodetalleconceptos.concepto in ($codcon)
	AND nominapagodetalle.fichapersonal='$ficha'";

	$stmt  = $connex->prepare($query);
	$stmt->execute();

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		$valor = $row['asigtotal'];

		return $valor;
	}
}


function dia($fecha){
	return date("d",strtotime($fecha));
}



function mes($fecha){
	return date("m",strtotime($fecha));
}



function anio($fecha){
	return date("Y",strtotime($fecha));
}



function SI($condicion,$v,$f){

	if($condicion == ""){
		return 0;
	}

	$if = "if(".$condicion."){\$retorno=".$v.";}else{\$retorno=".$f.";}";

	eval($if);
	return $retorno;

}
function lunes($fecha){

	$numero_dia = dia($fecha);
	$mes        = mes($fecha);
	$anio       = anio($fecha);

	$count      = 0;
	$dias_mes   = cal_days_in_month(CAL_GREGORIAN, $mes, $anio);
	for($i = 1;$i <= $dias_mes;$i++)
	if(date('N',strtotime($anio.'-'.$mes.'-'.$i)) == $numero_dia)
	$count++;
	return $count;
}

function codnomi($cedula,$tip,$frec){

	$conn   = new Conexion();
	$connex = $conn->abrircon();


	$query  = "SELECT
	max(public.nominapagodetalle.nominapago)as cod,
	public.nominapagodetalle.cedula,
	public.frecuencianomina.id
	FROM
	public.nominapagodetalle
	INNER JOIN public.tipopersonal ON (public.nominapagodetalle.tipopersona = public.tipopersonal.tipo)
	INNER JOIN public.frecuencianomina ON (public.nominapagodetalle.frecuencia = public.frecuencianomina.frecuencia)
	WHERE
	public.nominapagodetalle.cedula =".$cedula." AND
	public.tipopersonal.id =".$tip." AND
	public.frecuencianomina.id =".$frec."
	group by

	public.nominapagodetalle.cedula, public.frecuencianomina.id";

	$stmt   = $connex->prepare($query);
	$stmt->execute();

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		$valor = $row['cod'];

		return $valor;
	}
}




	$conn      = new Conexion();
	$connex    = $conn->abrircon();

/*	$query     = "SELECT
	fichapersonal.id,
	public.funcionario.documento,
	public.funcionario.nombres,
	public.funcionario.apellidos,
	public.conceptos.codigo,
	public.conceptos.concepto,
	public.conceptos.formula,
	public.conceptos.tipopersonal,
	public.conceptos.frecuencianomina,
	public.fichapersonal.salario,
	public.frecuencianomina.diaspago,
	fichapersonal.fechaingreso,
	fichapersonal.sso,
	funcionario.hijos,
	fichapersonal.faov,

	fichapersonal.fondopj,

	fichapersonal.regimenpf,

	fichapersonal.ince,

	fichapersonal.islr,

	fichapersonal.cajaahorros,

	fichapersonal.sindicato,

	situacion.situacion,
	tabuladorrrhh.salariominimo,
	situacion.accion,
	COALESCE((SELECT primaprofesion.porcentaje
	FROM  funcionarioestudios
	INNER JOIN primaprofesion
	ON CAST(funcionarioestudios.grado AS NUMERIC) = primaprofesion.id
	WHERE funcionarioestudios.funcionario = funcionario.id
	ORDER BY funcionarioestudios.grado DESC LIMIT 1),0) AS profesion,
	COALESCE((SELECT count(DATE_PART('year', current_date) - DATE_PART('year', fechanacimiento)) from  funcionariohijos

	WHERE (DATE_PART('year', current_date) - DATE_PART('year', fechanacimiento)) < 18 AND

	funcionariohijos.funcionario = funcionario.id

	GROUP BY funcionariohijos.funcionario

	ORDER BY funcionariohijos.funcionario),0) AS menores,
	COALESCE((SELECT sum(hasta::date - desde::date) from  funcionariolaboral

	WHERE funcionariolaboral.funcionario = funcionario.id AND

	funcionariolaboral.area = 'G'

	GROUP BY funcionariolaboral.funcionario

	ORDER BY funcionariolaboral.funcionario),0) AS antiguedad,
	fichapersonal.sector,

	fichapersonal.programa,

	fichapersonal.subprograma,

	fichapersonal.proyecto,

	fichapersonal.actividad,

	fichapersonal.unidadadministrativa,
	fichapersonal.cargo

	FROM
	public.fichapersonal
	INNER JOIN funcionario ON (fichapersonal.funcionario = funcionario.id)
	INNER JOIN cargosrrhh ON (fichapersonal.idcargo = cargosrrhh.id)
	INNER JOIN cargosinst ON (cargosrrhh.idcargo = cargosinst.id)
	INNER JOIN tipopersonal ON (cargosrrhh.tipopersonal = tipopersonal.id)
	INNER JOIN situacion ON (fichapersonal.situacion = situacion.id)
	INNER JOIN tabuladorrrhh ON (fichapersonal.idtabulador = tabuladorrrhh.id)

	INNER JOIN public.conceptos ON (public.tipopersonal.tipo = public.conceptos.tipopersonal)
	INNER JOIN public.frecuencianomina ON (public.conceptos.frecuencianomina = public.frecuencianomina.frecuencia)
	WHERE
	public.tipopersonal.id =".$tipop. " AND
	public.frecuencianomina.id = 1 AND
	public.funcionario.documento =".$cedula." AND
	public.conceptos.status='A' AND
	public.conceptos.tipoconcepto <> 'P'

	order by public.conceptos.codigo asc";*/
	
/*	$query     ="SELECT distinct
  public.fichapersonal.id,
  funcionario.documento,
  funcionario.nombres,
  funcionario.apellidos,
  public.conceptos.codigo,
  public.conceptos.concepto,
  public.conceptos.formula,
  public.conceptos.tipopersonal,
  public.conceptos.frecuencianomina,
  public.fichapersonal.salario,
  public.frecuencianomina.diaspago,
  public.fichapersonal.fechaingreso,
  public.fichapersonal.sso,
  funcionario.hijos,
  public.fichapersonal.faov,
  public.fichapersonal.fondopj,
  public.fichapersonal.regimenpf,
  public.fichapersonal.ince,
  public.fichapersonal.islr,
  public.fichapersonal.cajaahorros,
  public.fichapersonal.sindicato,
  situacion.situacion,
  tabuladorrrhh.salariominimo,
  situacion.accion,
  public.fichapersonal.sector,
  public.fichapersonal.programa,
  public.fichapersonal.subprograma,
  public.fichapersonal.proyecto,
  public.fichapersonal.actividad,
  public.fichapersonal.unidadadministrativa,
  public.fichapersonal.cargo,
  conceptos.tipoconcepto,
  COALESCE((SELECT primaprofesion.porcentaje
	FROM  funcionarioestudios
	INNER JOIN primaprofesion
	ON CAST(funcionarioestudios.grado AS NUMERIC) = primaprofesion.id
	WHERE funcionarioestudios.funcionario = funcionario.id
	ORDER BY funcionarioestudios.grado DESC LIMIT 1),0) AS profesion,
	COALESCE((SELECT count(DATE_PART('year', age(fechanacimiento))) from  funcionariohijos
	WHERE (DATE_PART('year', age(fechanacimiento))) <= 18  AND
	funcionariohijos.funcionario = funcionario.id
	GROUP BY funcionariohijos.funcionario
	ORDER BY funcionariohijos.funcionario),0) AS menores,
	COALESCE((SELECT sum(hasta::date - desde::date) from  funcionariolaboral
	WHERE funcionariolaboral.funcionario = funcionario.id AND
	funcionariolaboral.area = 'G'
	GROUP BY funcionariolaboral.funcionario
	ORDER BY funcionariolaboral.funcionario),0) AS antiguedad
FROM
  public.fichapersonal
  INNER JOIN funcionario ON (public.fichapersonal.funcionario = funcionario.id)
  INNER JOIN situacion ON (public.fichapersonal.situacion = situacion.id)
  INNER JOIN tabuladorrrhh ON (public.fichapersonal.idtabulador = tabuladorrrhh.id),
  tipopersonal
  INNER JOIN public.conceptos ON (tipopersonal.tipo = public.conceptos.tipopersonal)
  INNER JOIN public.frecuencianomina ON (public.conceptos.frecuencianomina = public.frecuencianomina.frecuencia)
WHERE
  public.tipopersonal.id = ".$tipop. " AND 
  public.frecuencianomina.id = 1 AND 
  public.funcionario.documento = ".$cedula." AND 
  public.conceptos.status = 'A' AND 
  public.conceptos.tipoconcepto <> 'P'
ORDER BY
  public.conceptos.codigo ASC";*/
  $query="SELECT DISTINCT 
  public.fichapersonal.id,
  funcionario.documento,
  funcionario.nombres,
  funcionario.apellidos,
  public.conceptos.codigo,
  public.conceptos.concepto,
  public.conceptos.formula,
  public.conceptos.tipopersonal,
  public.conceptos.frecuencianomina,
  public.fichapersonal.salario,
  public.frecuencianomina.diaspago,
  public.fichapersonal.fechaingreso,
  public.fichapersonal.sso,
  funcionario.hijos,
  public.fichapersonal.faov,
  public.fichapersonal.fondopj,
  public.fichapersonal.regimenpf,
  public.fichapersonal.ince,
  public.fichapersonal.islr,
  public.fichapersonal.cajaahorros,
  public.fichapersonal.sindicato,
  situacion.situacion,
  tabuladorrrhh.salariominimo,
  situacion.accion,
  public.fichapersonal.sector,
  public.fichapersonal.programa,
  public.fichapersonal.subprograma,
  public.fichapersonal.proyecto,
  public.fichapersonal.actividad,
  public.fichapersonal.unidadadministrativa,
  public.fichapersonal.cargo,
  conceptos.tipoconcepto,
    COALESCE((SELECT primaprofesion.porcentaje
	FROM  funcionarioestudios
	INNER JOIN primaprofesion
	ON CAST(funcionarioestudios.grado AS NUMERIC) = primaprofesion.id
	WHERE funcionarioestudios.funcionario = funcionario.id
	ORDER BY funcionarioestudios.grado DESC LIMIT 1),0) AS profesion,
	COALESCE((SELECT count(DATE_PART('year', age(fechanacimiento))) from  funcionariohijos
	WHERE (DATE_PART('year', age(fechanacimiento))) <= 18  AND
	funcionariohijos.funcionario = funcionario.id
	GROUP BY funcionariohijos.funcionario
	ORDER BY funcionariohijos.funcionario),0) AS menores,
	COALESCE((SELECT sum(hasta::date - desde::date) from  funcionariolaboral
	WHERE funcionariolaboral.funcionario = funcionario.id AND
	funcionariolaboral.area = 'G'
	GROUP BY funcionariolaboral.funcionario
	ORDER BY funcionariolaboral.funcionario),0) AS antiguedad
FROM
  public.fichapersonal
  INNER JOIN funcionario ON (public.fichapersonal.funcionario = funcionario.id)
  INNER JOIN situacion ON (public.fichapersonal.situacion = situacion.id)
  INNER JOIN tabuladorrrhh ON (public.fichapersonal.idtabulador = tabuladorrrhh.id)
  INNER JOIN tipopersonal ON (public.fichapersonal.tiponomina = tipopersonal.id)
  INNER JOIN public.conceptos ON (tipopersonal.tipo = public.conceptos.tipopersonal)
  INNER JOIN public.frecuencianomina ON (public.conceptos.frecuencianomina = public.frecuencianomina.frecuencia)
WHERE
  public.tipopersonal.id =".$tipop. " AND 
  public.frecuencianomina.id = 1 AND 
  public.funcionario.documento = ".$cedula." AND 
  public.conceptos.status = 'A' AND 
  public.conceptos.tipoconcepto <> 'P'
ORDER BY
  public.conceptos.codigo";

	$stmt      = $connex->prepare($query);
	$stmt->execute();
	$monto     = 0;
	$montoprof = 0;
	$nhijos    = 0;
	$codnom    = codnomi($cedula,$tipop,1);
	$lunes     = lunes(date("Y-m-d"));
	$fechafin  = date("Y-m-d");
	$asignaciones=0;
	$deducciones=0;
	$valortabla='';
	while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		$ficha                = $row['id'];
		$cedula               = $row['documento'];
		$nombres              = $row['nombres'];
		$apellidos            = $row['apellidos'];
		$codigo               = $row['codigo'];
		$concepto             = $row['concepto'];
		$formula              = $row['formula'];
		$salario              = $row['salario'];
		$diaspago             = $row['diaspago'];
		$porcprof             = $row['profesion'];
		$menores              = $row['menores'];
		$salariomin           = $row['salariominimo'];
		$tipoconcepto=$row['tipoconcepto'];
		$tienehijos           = $row['hijos'];

		$sso                  = $row['sso'];

		$vivienda             = $row['faov'];

		$fondop               = $row['fondopj'];

		$parof                = $row['regimenpf'];

		$ince                 = $row['ince'];

		$islr                 = $row['islr'];
		$cajaa                = $row['cajaahorros'];

		$sindicato            = $row['sindicato'];
		$sector               = $row['sector'];
		$programa             = $row['programa'];
		$subprograma          = $row['subprograma'];
		$proyecto             = $row['proyecto'];
		$actividad            = $row['actividad'];
		$unidadadministrativa = $row['unidadadministrativa'];
		$cargo = $row['cargo'];
		if( $porcprof > 0){

			$montoprof = ($salario * $porcprof) / 100;

		}else{

			$montoprof = 0;

		}


		if($menores > 0){
			$nhijos = $menores;
		}else{
			$nhijos = 0;
		}
		$tservicio = 0;

		$antiguedad= $row['antiguedad'];

		$fing      = $row['fechaingreso'];

		$now       = date("Y-m-d");

		$tservicio = (strtotime($fing) - strtotime($now)) / 86400;

		$tservicio = abs($tservicio);
		$dias      = floor($tservicio);

		$antiguedad= $antiguedad + $tservicio;

		$diasant   = $antiguedad / 365;

		$entero    = explode(".",$diasant);

		$antiguedad= $entero[0];





			//	echo $cedula.' '.$nombres.' '.$apellidos.' '.$codigo.' '.$concepto.' '.$formula.' '.$salario.'<br>';

		eval($formula);

		if($monto > 0){
		if($tipoconcepto=='A'){
			$asignaciones=$monto;
			$deducciones=0;
		}else{
			$asignaciones=0;
			$deducciones=$monto;
		}
	/*	if($tipoconcepto=='D'){
			$deducciones=$monto;
		}else{
			$deducciones=0;
		}*/
		
$valortabla .= "<tr>".
     "<td scope='row' align=center>" .$codigo . "</td>".
     "<td scope='row'>" . $concepto . "</td>".
     "<td scope='row'align=right>" . number_format($asignaciones, 2, ',', '.') . "</td>". 
     "<td scope='row'align=right>" . number_format($deducciones, 2, ',', '.') . "</td>".
     
     "<tr>";
			
			$fechai = date_create($fing);
			$datos      = array(

			'salario' => number_format($salario, 2, ',', '.'),
			'sector'       => $sector,
			'programa'      => $programa,
			'subprograma'    => $subprograma,
			'proyecto'         => $proyecto,
			'actividad'       => $actividad,
			'unidad'         => $unidadadministrativa,
			'fing'     => date_format($fechai,"d/m/Y"),
			'codigo'    => $codigo,
			'concepto' => $concepto,
			'asignaciones'=> number_format($asignaciones, 2, ',', '.'),
			'deducciones'=> number_format($deducciones, 2, ',', '.'),
			'cargo'=> $cargo,
			'valor'=>$valortabla

		);
			
		//	echo $codigo.' '.$concepto.' '.$asignaciones.' '.$deducciones.'<br>';

		}
		
	}
	
	header('Content-type: application/json; charset=ISO-8859-1');
	echo json_encode($datos,JSON_FORCE_OBJECT);
	
	//echo $cedula.' '.$nombres.' '.$apellidos.' '.$salario.'<br>';
//	echo $sector.' '.$programa.' '.$subprograma.' '.$proyecto.' '.$actividad.' '.$unidadadministrativa.'<br>';
	//	echo $fing.'<br>';
	




?>