<?php
header('Content-Type: text/html;charset=ISO-8859-1');
include('../utilidades/fpdf.php');
include("../Conexion/conecta.php");

$conn         = new Conexion();
$connex       = $conn->abrircon();
$id = trim($_POST['id']);
//$id           = '77076';
$idnom;
$time         = time();
$fechaemision = date("d-m-Y", $time);
$fecha1       ;
$fecha2       ;
$fechaingreso ;
$cedula       = trim($_POST['cedula']);
//$cedula       = 12913479;

$Empleado     = '';
$cargo        = '';
$gober        = 'GobernaciÛn del Estado Anzo·tegui';
$concepto     = '';
$descripcion  = '';
$salario      = '';
$asignaciones = '';
$deducciones  = '';
$aportes      = '';
$sector       = '';
$programa     = '';
$subprograma  = '';
$proyecto     = '';
$actividad    = '';
$undadm       = '';
$neto = 0;
$subasig= 0;
$subdedu=0;
$nomina='';
$frecuencia='';


$query        = 'SELECT DISTINCT
public.nominapagodetalle.cedula,
public.nominapagodetalle.nombres,
public.nominapagodetalle.apellidos,
public.nominapagodetalle.cargo,
public.nominapagodetalle.salariomensual,
public.unidadadministrativarrhh.sector,
public.unidadadministrativarrhh.programa,
public.unidadadministrativarrhh.subprograma,
public.unidadadministrativarrhh.proyecto,
public.unidadadministrativarrhh.actividad,
public.unidadadministrativarrhh.denominacion,
public.nominapagodetalle.diaspagonomina,
public.nominapagodetalle.tipopersona,
public.fichapersonal.fechaingreso,
public.nominapago.fdesde,
public.nominapago.fhasta,
public.nominapagodetalle.nominapago,
public.nominapagodetalle.neto,
public.tipopersonal.tipo,
public.frecuencianomina.frecuencia
FROM
public.nominapagodetalle
INNER JOIN public.unidadadministrativarrhh ON (public.nominapagodetalle.unidadadministrativa = public.unidadadministrativarrhh.id)
INNER JOIN public.fichapersonal ON (public.nominapagodetalle.fichapersonal = public.fichapersonal.id)
INNER JOIN public.nominapago ON (public.nominapagodetalle.nominapago = public.nominapago.id)
INNER JOIN public.tipopersonal ON (public.fichapersonal.tiponomina = public.tipopersonal.id)
INNER JOIN public.frecuencianomina ON (public.nominapago.frecuencianomina = public.frecuencianomina.id)

WHERE

public.nominapagodetalle.id =:id AND
public.nominapagodetalle.cedula = :cedula';


$stmt         = $connex->prepare($query);
$stmt->bindParam(':id', $id, PDO::PARAM_STR);
$stmt->bindParam(':cedula', $cedula, PDO::PARAM_STR);
$stmt->execute();
$count        = $stmt->rowCount();
$row          = $stmt->fetch(PDO::FETCH_ASSOC);

$idnom=$row['nominapago'];
$nombres      = utf8_decode($row['nombres']);
$apellidos    = utf8_decode($row['apellidos']);
$Empleado     = $nombres.' '.$apellidos;
$cargo        = $row['cargo'];
$salario      = number_format($row['salariomensual'], 2, ',', '.');

$fecha        = date_create($row['fechaingreso']);
$fechaingreso = date_format($fecha,"d/m/Y");

$fechai       = date_create($row['fdesde']);
$fecha1       = date_format($fechai,"d/m/Y");

$fechaf       = date_create($row['fhasta']);
$fecha2       = date_format($fechaf,"d/m/Y");

$sector       = $row['sector'];
$programa     = $row['programa'];
$subprograma  = $row['subprograma'];
$proyecto     = $row['proyecto'];
$actividad    = $row['actividad'];
$undadm       = $row['denominacion'];

$neto= number_format($row['neto'], 2, ',', '.');
$nomina=$row['tipo'];
$frecuencia=$row['frecuencia'];


/* x, y: top left corner of the rectangle.
w, h: width and height.
r: radius of the rounded corners.
corners: numbers of the corners to be rounded: 1, 2, 3, 4 or any combination (1=top left, 2=top right, 3=bottom right, 4=bottom left).
style: same as Rect(): F, D (default), FD or DF.*/



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

	//Cabecera de p√°gina
	function Header(){
		global $gober;
		global $fechaemision;
		global $idnom;
		global $fecha1;
		global $fecha2;
		$this->Image('../Recursos/Logo-gestin (1).png',10,8,40,20);
		$this->SetFont('Arial','U',12);
		$this->Ln(20);
		$this->SetX(92);
		$this->Cell(20,8,'Recibo de Pago',0,1,'L');
		$this->SetY(10);
		$this->SetFont('Times','',10);
		$this->SetX(50);
		$this->SetFillColor(192);
		$this->RoundedRect(8, 8, 100, 20, 5, '1234', 'D');
		$this->Cell(20,8,$gober,0,1,'J');
		$this->SetX(50);
		$this->Cell(20,4,"RIF: G-20000122-4",0,1,'L');
		$this->SetY(10);
		$this->SetX(140);
		$this->Cell(20,8,'Fecha de Emision: '.$fechaemision,0,1,'L');
		$this->SetY(14);
		$this->SetX(140);
		$this->Cell(20,8,'ID Nomina: '.$idnom,0,1,'L');
		$this->SetY(18);
		$this->SetX(140);
		$this->Cell(20,8,'Desde: '.$fecha1.' Hasta: '.$fecha2,0,1,'L');

	}

	function Footer(){

     
		$this->SetY( - 10);

		$this->SetFont('Arial','I',8);

		$this->Cell(0,10,'Pagina '.$this->PageNo().'/{nb}',0,0,'C');

	}
}

$pdf   = new PDF();

$pdf->AddPage();
$pdf->SetFont('Times','',11);
$pdf->Ln(25);
$pdf->SetFillColor(192);
$pdf->RoundedRect(8, 40, 192, 35, 5, '1234', 'D');//bordes
$pdf->SetX(10);
$pdf->SetY(42);
$pdf->Cell(20,6,$pdf->WriteHTML('<b>Cedula:</b> '.$cedula),0,1,'L');
$pdf->SetY(42);
$pdf->SetX(50);
$pdf->Cell(20,6,$pdf->WriteHTML('<b>Nombre y Apellido:</b> '.$Empleado),0,1,'L');
$pdf->SetX(10);
$pdf->SetY(48);
$pdf->Cell(20,6,$pdf->WriteHTML('<b>Salario:</b> '.$salario),0,1,'L');
$pdf->SetY(48);
$pdf->SetX(50);
$pdf->Cell(20,6,$pdf->WriteHTML('<b>Cargo:</b> '.$cargo.'.  <b>Fecha de Ingreso:</b> '.$fechaingreso),0,1,'L');
$pdf->SetX(10);
$pdf->Cell(20,6,$pdf->WriteHTML('<b>Sector:</b> '.$sector.'  <b>Programa:</b> '.$programa.'  <b>Subprograma:</b> '.$subprograma.'  <b>Proyecto:</b> '.$proyecto.'  <b>Actividad:</b> '.$actividad),0,1,'L');
$pdf->SetX(10);
$pdf->Cell(20,6,$pdf->WriteHTML('<b>Unidad Administrativa :</b> '.utf8_decode($undadm)),0,1,'L');
$pdf->SetX(10);
$pdf->Cell(20,6,$pdf->WriteHTML('<b>Nomina :</b> '.utf8_decode($nomina)),0,1,'L');
$pdf->SetFillColor(192);
$pdf->RoundedRect(8, 78, 190, 6, 3, '1234', 'D');//bordes
$pdf->SetY(78);
$pdf->SetX(10);
$pdf->SetFont('Times','B',10);
$pdf->Cell(20,6,utf8_decode('Concepto  Descripc√≠on                                                                                                                      Asignaciones        Deducciones'),0,1,'L');



$query = "SELECT DISTINCT
public.nominapagodetalleconceptos.concepto,
public.conceptos.concepto AS descri,
public.nominapagodetalleconceptos.asignacion,
public.nominapagodetalleconceptos.deduccion,
public.nominapagodetalle.neto
FROM
public.nominapagodetalle
INNER JOIN public.nominapago ON (public.nominapagodetalle.nominapago = public.nominapago.id)
INNER JOIN public.nominapagodetalleconceptos ON (public.nominapagodetalle.id = public.nominapagodetalleconceptos.nominapagodetalle)
INNER JOIN public.conceptos ON (public.nominapagodetalleconceptos.concepto = public.conceptos.codigo)
WHERE
public.nominapagodetalle.id = :id AND
public.nominapagodetalle.cedula = :cedula AND
public.nominapagodetalleconceptos.concepto < 20000 
ORDER BY public.nominapagodetalleconceptos.concepto ASC";
$stmt  = $connex->prepare($query);
$stmt->bindParam(':id', $id, PDO::PARAM_STR);
$stmt->bindParam(':cedula', $cedula, PDO::PARAM_STR);
$stmt->execute();

// $pdf->SetY(83);
//$pdf->SetX(8);
$pdf->SetFont('Times','',9);
$pdf->Ln(3);

while($row = $stmt->fetch(PDO::FETCH_ASSOC)){

	$concepto    = $row['concepto'];
	$descripcion = $row['descri'];
	$asignaciones= number_format($row['asignacion'], 2, ',', '.');
	$deducciones = number_format($row['deduccion'], 2, ',', '.');
	
    $subasig += $row['asignacion'];
    $subdedu += $row['deduccion'];
    
    

	$pdf->Cell(10,1,$concepto,0,0,'R');
	//$pdf->SetX(14);
	// $pdf->MultiCell(0,1,$concepto,0,'R');
	//$pdf->SetY(84);
	$pdf->SetX(26);
	//  $pdf->MultiCell(0,0,$descripcion,0,'J');
	$pdf->SetFont('Times','',8);
	$pdf->Cell(20,1,$descripcion,0,0,'L');
    $pdf->SetFont('Times','',9);
	// $pdf->SetY(83);
	$pdf->SetX(158);
	$pdf->Cell(10,1,$asignaciones,0,0,'R');
	//  $pdf->MultiCell(0,1,$asignaciones,0,'R');
	//$pdf->SetY(83);
	$pdf->SetX(184);
	$pdf->Cell(10,1,$deducciones,0,0,'R');

	$pdf->Ln(4);
}


$pdf->WriteHTML('<br><hr>');
$pdf->Ln(2);
$pdf->SetFont('Times','',11);
$pdf->SetX(10);
$pdf->Cell(20,2,$pdf->WriteHTML('Total Asignaciones: '.'<b>'.number_format($subasig, 2, ',', '.').'</b>'),0,0,'R');
$pdf->Ln(7);
$pdf->SetX(10);
$pdf->Cell(20,2,$pdf->WriteHTML('Total Deducciones: '.'<b>'.number_format($subdedu, 2, ',', '.').'</b>'),0,0,'R');
$pdf->Ln(7);
$pdf->SetX(10);
$pdf->Cell(10,2,$pdf->WriteHTML('Total a Pagar: '.'<b>'.$neto.'</b>'),0,0,'R');
$pdf->Ln(5);
$pdf->WriteHTML('<br><hr>');
$pdf->Ln(5);
$pdf->SetX(10);
$pdf->Cell(20,2,$pdf->WriteHTML('<b>OBSERVACIONES:</b> '.$frecuencia.' DE '.$nomina),0,0,'L');

$pdf->AliasNbPages();

$archivo='../TMP/recibo.pdf';
$pdf->SetDisplayMode('real');
$pdf->Output($archivo,'F');


?>