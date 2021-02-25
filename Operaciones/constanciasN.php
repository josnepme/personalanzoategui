<?php
header('Content-Type: text/html;charset=ISO-8859-1');
//header('Content-Type: application/pdf');


include('../utilidades/fpdf.php');
include("../Conexion/conecta.php");
include('../utilidades/phpqrcode/qrlib.php'); 

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

$conn      = new Conexion();
$connex    = $conn->abrircon();
$id        = trim($_POST['id']);
$datosjson = array();

//$id = '832';
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
$qr='';

if(isset($_POST['id']) && $_POST['id'] != ''){


$query        = "SELECT
public.funcionario.nacioanlidad,
public.funcionario.documento,
public.funcionario.nombres,
public.funcionario.apellidos,
public.fichapersonal.fechaingreso,
public.funcionario.direccion,
public.funcionario.telefomovil,
public.funcionario.telefonofijo,
public.fichapersonal.cargo,
public.fichapersonal.salario,
public.tipopersonal.tipo
FROM
public.fichapersonal
INNER JOIN public.funcionario ON (public.fichapersonal.funcionario = public.funcionario.id)
INNER JOIN public.tipopersonal ON (public.fichapersonal.tiponomina = public.tipopersonal.id)
where fichapersonal.id =:id";
$stmt         = $connex->prepare($query);
$stmt->bindParam(':id', $id, PDO::PARAM_STR);
$stmt->execute();
$count        = $stmt->rowCount();
$row          = $stmt->fetch(PDO::FETCH_ASSOC);

if($count > 0 && !empty($row)){
	
$pdf       = new PDF();
	$cedula    = $row['documento'];
	$nombres   = utf8_decode($row['nombres']);
	$apellidos = utf8_decode($row['apellidos']);
	$cargo     = $row['cargo'];
	$tipo      = $row['tipo'];
	$direccion = utf8_decode($row['direccion']);

	if($row['telefonofijo'] != '' && $row['telefomovil'] != ''){
		$telefono = $row['telefomovil'].', '.$row['telefonofijo'];
	}else{
		$telefono = $row['telefonofijo'].$row['telefomovil'];
	}

	$salario      = number_format($row['salario'], 2, ',', '.');
	$fechaingreso = date_create($row['fechaingreso']);
	$fecha        = date_format($fechaingreso,"d/m/Y");
	/*$funcionario  = $nombres.' '.$apellidos.', identificado con la cedula de identidad N°. '.$cedula.', quien labora en la Institución desde el: '.
	$fecha.' desempeñando el puesto de: '.$cargo.', en la Nomina de: '.$tipo.', devengando un salario Mensual de: '.$salario.' Bs.'; // Dirección de Habitación: '.$direccion.', Teléfonos: '.$telefono.'.';*/
	$fechaemision = date("d-m-Y", time ());
	$dia          = date("j");
	$mes          = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
	$smes         = $mes[date('n') - 1];
	$ano          = date("Y");



	$texto1       = 'El suscrito Director de Personal de la Gobernación del Estado Anzoátegui hace constar por medio de la presente que los datos sobre el servicio que presta el funcionario abajo mencionado son ciertos: ';
	$texto2       = 'Se expide el Presente documento, para los fines que el interesado crea conveniente, a los ' .$dia.' días del mes de '.$smes.' del '.$ano.'.';
	
	$nombdirector = "Lcda. Rosa Inés Moreno";
	
	$contenidoqr='Cedula: '.$cedula.', Nombre y Apellido: '.$nombres.' '.$apellidos.', Fecha de Emision: '.$fechaemision.', Nomina:'.$tipo.', Salario: '.$salario;
    QRcode::png($contenidoqr,"../TMP/qr.png",QR_ECLEVEL_L,10,2);
    $qr='../TMP/qr.png';


	
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
	$pdf->MultiCell(0,8,$nombres.' '.$apellidos.' identificado con la cedula de identidad N° '.$cedula.', quien  desempeña el puesto de: '.$cargo.', desde el: '.$fecha.', en la Nomina de: '.$tipo.', devengando un salario Mensual de: '.$salario.' Bs.',0,'J');
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
    $archivo='../TMP/constancia.pdf';
	$pdf->SetDisplayMode('real');
    $pdf->Output($archivo,'F');
 
	//    $datosjson['Archivopdf'] = $exito;
	//  header('Content - type: application / json; chartset = utf - 8');
	//  echo json_encode($datosjson);


	//echo $exito;
}


}else{
header('location:../Formularios/menu.php');
}
?>
