<?php
header('Content-Type: text/html;charset=ISO-8859-1');
//header('Content - Type: application / pdf');


include('../utilidades/fpdf.php');
include("../Conexion/conecta.php");
include('../utilidades/phpqrcode/qrlib.php');
include("../Operaciones/globales.php");


$conn      = new Conexion();
$connex    = $conn->abrircon();
/*$id = '832';
$cedula    = 12913479;
$tipop       = trim($_POST['tip']);*/
$id        = trim($_POST['id']);
$cedula    = trim($_POST['cedula']);


$tipop     = sacartipop($id);

$monto     = 0;
$montoprof = 0;
$nhijos    = 0;
$lunes     = lunes(date("Y-m-d"));
$fechafin  = date("Y-m-d");

$sintegral = 0;
$datosjson = array();
$codnom       = codnomi($cedula,$tipop,1);

$nacionalidad = '';
$cedula       = '';
$nombres      = '';
$apellidos    = '';
$direccion    = '';
$telefono     = '';
$cargo        = '';
$salario      = 0;
$tipo         = '';
$fechaingreso = '';
$gober        = 'Gobernación del Estado Anzoátegui';
$qr           = '';
$telefonofijo = '';
$telefonomovil= '';



function sacartipop($id){

	$conn   = new Conexion();
	$connex = $conn->abrircon();


	$query  = "SELECT DISTINCT
	public.fichapersonal.tiponomina
	FROM
	public.fichapersonal
	where
	public.fichapersonal.id=".$id;

	$stmt   = $connex->prepare($query);
	$stmt->execute();

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		$valor = $row['tiponomina'];
		return $valor;
	}
}


class PDF extends FPDF{

	function RoundedRect($x, $y, $w, $h, $r, $corners = '1234', $style = ''){
		$k = $this->k;
		$hp= $this->h;
		if($style == 'F')
		$op = 'f';
		elseif($style == 'FD' || $style == 'DF')
		$op = 'B';
		else
		$op    = 'S';
		$MyArc = 4 / 3 * (sqrt(2) - 1);
		$this->_out(sprintf('%.2F %.2F m',($x + $r) * $k,($hp - $y) * $k ));

		$xc    = $x + $w - $r;
		$yc    = $y + $r;
		$this->_out(sprintf('%.2F %.2F l', $xc * $k,($hp - $y) * $k ));
		if(strpos($corners, '2') === false)
		$this->_out(sprintf('%.2F %.2F l', ($x + $w) * $k,($hp - $y) * $k ));
		else
		$this->_Arc($xc + $r * $MyArc, $yc - $r, $xc + $r, $yc - $r * $MyArc, $xc + $r, $yc);

		$xc = $x + $w - $r;
		$yc = $y + $h - $r;
		$this->_out(sprintf('%.2F %.2F l',($x + $w) * $k,($hp - $yc) * $k));
		if(strpos($corners, '3') === false)
		$this->_out(sprintf('%.2F %.2F l',($x + $w) * $k,($hp - ($y + $h)) * $k));
		else
		$this->_Arc($xc + $r, $yc + $r * $MyArc, $xc + $r * $MyArc, $yc + $r, $xc, $yc + $r);

		$xc = $x + $r;
		$yc = $y + $h - $r;
		$this->_out(sprintf('%.2F %.2F l',$xc * $k,($hp - ($y + $h)) * $k));
		if(strpos($corners, '4') === false)
		$this->_out(sprintf('%.2F %.2F l',($x) * $k,($hp - ($y + $h)) * $k));
		else
		$this->_Arc($xc - $r * $MyArc, $yc + $r, $xc - $r, $yc + $r * $MyArc, $xc - $r, $yc);

		$xc = $x + $r ;
		$yc = $y + $r;
		$this->_out(sprintf('%.2F %.2F l',($x) * $k,($hp - $yc) * $k ));
		if(strpos($corners, '1') === false){
			$this->_out(sprintf('%.2F %.2F l',($x) * $k,($hp - $y) * $k ));
			$this->_out(sprintf('%.2F %.2F l',($x + $r) * $k,($hp - $y) * $k ));
		}
		else
		$this->_Arc($xc - $r, $yc - $r * $MyArc, $xc - $r * $MyArc, $yc - $r, $xc, $yc - $r);
		$this->_out($op);
	}

	function _Arc($x1, $y1, $x2, $y2, $x3, $y3){
		$h = $this->h;
		$this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c ', $x1 * $this->k, ($h - $y1) * $this->k,
				$x2 * $this->k, ($h - $y2) * $this->k, $x3 * $this->k, ($h - $y3) * $this->k));
	}

	var $B    = 0;
	var $I    = 0;
	var $U    = 0;
	var $HREF = '';
	var $ALIGN= '';

	function WriteHTML($html){
		//HTML parser
		$html = str_replace("\n",' ',$html);
		$a    = preg_split('/<(.*)>/U',$html, - 1,PREG_SPLIT_DELIM_CAPTURE);
		foreach($a as $i=>$e){
			if($i % 2 == 0){
				//Text
				if($this->HREF)
				$this->PutLink($this->HREF,$e);
				elseif($this->ALIGN == 'center')
				$this->Cell(0,5,$e,0,1,'C');
				else
				$this->Write(5,$e);
			}
			else{
				//Tag
				if($e[0] == '/')
				$this->CloseTag(strtoupper(substr($e,1)));
				else{
					//Extract properties
					$a2   = explode(' ',$e);
					$tag  = strtoupper(array_shift($a2));
					$prop = array();
					foreach($a2 as $v){
						if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
						$prop[strtoupper($a3[1])] = $a3[2];
					}
					$this->OpenTag($tag,$prop);
				}
			}
		}
	}

	function OpenTag($tag,$prop){
		//Opening tag
		if($tag == 'B' || $tag == 'I' || $tag == 'U')
		$this->SetStyle($tag,true);
		if($tag == 'A')
		$this->HREF = $prop['HREF'];
		if($tag == 'BR')
		$this->Ln(5);
		if($tag == 'P')
		$this->ALIGN = $prop['ALIGN'];
		if($tag == 'HR'){
			if( !empty($prop['WIDTH']) )
			$Width = $prop['WIDTH'];
			else
			$Width = $this->w - $this->lMargin - $this->rMargin;
			$this->Ln(2);
			$x     = $this->GetX();
			$y     = $this->GetY();
			$this->SetLineWidth(0.4);
			$this->Line($x,$y,$x + $Width,$y);
			$this->SetLineWidth(0.2);
			$this->Ln(2);
		}
	}

	function CloseTag($tag){
		//Closing tag
		if($tag == 'B' || $tag == 'I' || $tag == 'U')
		$this->SetStyle($tag,false);
		if($tag == 'A')
		$this->HREF = '';
		if($tag == 'P')
		$this->ALIGN = '';
	}

	function SetStyle($tag,$enable){
		//Modify style and select corresponding font
		$this->$tag += ($enable ? 1 : - 1);
		$style = '';
		foreach(array('B','I','U') as $s)
		if($this->$s > 0)
		$style .= $s;
		$this->SetFont('',$style);
	}

	function PutLink($URL,$txt){
		//Put a hyperlink
		$this->SetTextColor(0,0,255);
		$this->SetStyle('U',true);
		$this->Write(5,$txt,$URL);
		$this->SetStyle('U',false);
		$this->SetTextColor(0);
	}

	//Cabecera de pÃ¡gina
	function Header(){
		global $gober;
		global $fechaemision;
		global $idnom;
		global $fecha1;
		global $fecha2;
		global $qr;
		$this->Image('../Recursos/Logo-gestin (1).png',10,16,75,25);
		$this->Image($qr,158,14,30,30);
		$this->SetFont('Arial','UB',16);
		$this->Ln(36);
		$this->SetX(75);
		$this->Cell(20,8,'CONSTANCIA DE TRABAJO',0,1,'L');
		$this->SetY(38);
		$this->SetFont('Times','',10);
		//$this->SetX(65);
		$this->SetFillColor(192);
		$this->RoundedRect(8, 14, 80, 30, 5, '1234', 'D');
		//$this->Cell(20,10,$gober,0,1,'J');
		$this->SetX(55);
		$this->Cell(20,4,"RIF: G-20000122-4",0,1,'L');


	}
	function Footer(){

		$this->Image('../Recursos/Pie Pagina.png',10,270,190,20);
	}

}

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




$conn   = new Conexion();
$connex = $conn->abrircon();


$query  = "SELECT DISTINCT
public.fichapersonal.id,
funcionario.documento,
funcionario.nombres,
funcionario.apellidos,
funcionario.direccion,
funcionario.telefonofijo,
funcionario.telefomovil,
public.tipopersonal.tipo,
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
public.tipopersonal.id =:tipop AND
public.frecuencianomina.id = 1 AND
fichapersonal.id =:id AND
public.conceptos.status = 'A' AND
public.conceptos.tipoconcepto <> 'P'  AND
public.conceptos.tipoconcepto <> 'D'
ORDER BY
public.conceptos.codigo";

$stmt   = $connex->prepare($query);
$stmt->bindParam(':id', $id, PDO::PARAM_STR);
$stmt->bindParam(':tipop', $tipop, PDO::PARAM_STR);
$stmt->execute();

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
	$tipoconcepto         = $row['tipoconcepto'];
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
	$cargo                = $row['cargo'];
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

	eval($formula);

	if($monto > 0){

		$sintegral += $monto;

	}

	$cargo        = $row['cargo'];
	$tipo         = $row['tipo'];
	$direccion    = utf8_decode($row['direccion']);
	$fechaingreso = date_create($row['fechaingreso']);
	$fecha        = date_format($fechaingreso,"d/m/Y");

	$telefonofijo = $row['telefonofijo'];
	$telefonomovil= $row['telefomovil'];

}
 $sintegral = $sintegral * 2;

$pdf      = new PDF();


if($telefonofijo != '' && $telefonomovil != ''){
	$telefono = $telefonomovil.', '.$telefonofijo;
}else{
	$telefono = $telefonofijo.$telefonomovil;
}


$fecha        = $fecha;

$fechaemision = date("d-m-Y", time ());
$dia          = date("j");
$mes          = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
$smes         = $mes[date('n') - 1];
$ano          = date("Y");

$texto1       = 'El suscrito Director de Personal de la Gobernación del Estado Anzoátegui hace constar por medio de la presente que los datos sobre el servicio que presta el funcionario abajo mencionado son ciertos: ';
$texto2       = 'Se expide el Presente documento, para los fines que el interesado crea conveniente, a los ' .$dia.' días del mes de '.$smes.' del '.$ano.'.';

$nombdirector = "Lcda. Rosa Inés Moreno";

$contenidoqr  = 'Cedula: '.$cedula.', Nombre y Apellido: '.$nombres.' '.$apellidos.', Fecha de Emision: '.$fechaemision.', Nomina:'.$tipo.', Salario: '.$sintegral;
QRcode::png($contenidoqr,"../TMP/qr.png",QR_ECLEVEL_L,10,2);
$qr           = '../TMP/qr.png';



$pdf->SetMargins(20, 20 , 20);
$pdf->SetAutoPageBreak(true,20);
$pdf->AddPage();
$pdf->Image('../Recursos/Marca de Agua.png',46,65,110,110);

$pdf->SetFont('Times','',14);
$pdf->Ln(35);
$pdf->SetX(20);

$pdf->MultiCell(0,8,$texto1,0,'J');

$pdf->Ln(4);
//$pdf->MultiCell(0,6,$funcionario,0,'J');
$pdf->MultiCell(0,8,$nombres.' '.$apellidos.' identificado con la cedula de identidad N° '.$cedula.', quien  desempeña el puesto de: '.$cargo.', desde el: '.$fecha.', en la Nomina de: '.$tipo.', devengando un salario Integral Mensual de: '.number_format($sintegral, 2, ',', '.').' Bs.',0,'J');
$pdf->Ln(4);
$pdf->MultiCell(0,8,$texto2,0,'J');

$pdf->SetY(175);
$pdf->SetX(98);
$pdf->Cell(20,5,'Atentamente.',0,1,'C');
$pdf->Image('../Recursos/firma.png',86,218,37,35);
$pdf->Ln(65);
$pdf->SetX(98);
$pdf->Cell(20,6,$nombdirector,0,1,'C');
$pdf->SetX(98);
$pdf->SetFont('Times','',9);
$pdf->Cell(20,5,'Directora de Personal del Ejecutivo Estadal.',0,1,'C');
$pdf->SetY(255);
$pdf->SetX(98); //129
$pdf->Cell(25,4,'Decreto N° 61 de fecha 05/12/2018',0,1,'C');
$pdf->SetX(98); //9
$pdf->Cell(20,4,"emitido por el Gobernador del Estado Anzoátegui.",0,1,'C');
$pdf->SetX(10); //14
$pdf->SetY(251);
$pdf->Cell(25,4,'Valído Por 6 Meses.',0,1,'C');
$pdf->Cell(25,4,'Los datos reflejados carecen de validez si',0,1,'C');
$pdf->Cell(25,4,'se presentan sin sello húmedo emitido por',0,1,'C');
$pdf->Cell(25,4,'la Dirección de Personal.',0,1,'C');
$archivo = '../TMP/constancia.pdf';
$pdf->SetDisplayMode('real');
$pdf->Output($archivo,'F');


?>