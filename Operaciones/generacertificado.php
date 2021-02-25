<?php
header('Content-Type: text/html;charset=ISO-8859-1');
include('../utilidades/fpdf.php');
include("../Conexion/conecta.php");

$gober     = 'Gobernación del Estado Anzoátegui';

$conn      = new Conexion();
$connex    = $conn->abrircon();

$anno       = trim($_POST['anno']);
$cedula       = trim($_POST['cedula']);
/*$anno      = 2020;
$cedula    = 8237366;*/
$anos=$anno;
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

	//Cabecera de página
	function Header(){
		global $gober;
		global $anos;

		$this->Image('../Recursos/Logo-gestin (1).png',10,8,40,20);
		$this->SetFont('Arial','U',12);
		$this->Ln(30);
		$this->SetX(85);
		$this->Cell(20,8,'Certificación de Ingresos',0,1,'L');
		$this->SetX(100);
		$this->Cell(20,8,'Año: '.$anos,0,1,'C');
		$this->SetY(10);
		$this->SetFont('Times','',10);
		$this->SetX(50);
		$this->SetFillColor(192);
		$this->RoundedRect(8, 8, 100, 20, 5, '1234', 'D');
		$this->Cell(20,8, $gober,0,1,'J');
		$this->SetX(50);
		$this->Cell(20,4,"RIF: G-20000122-4",0,1,'L');


	}

}
$total     = 0;
$mes;
$meses;
$monto     = 0;
$nombres   = '';
$apellidos = '';
$Empleado  = '';
$meses     = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");

$query        = "SELECT DISTINCT 
  public.funcionario.documento,
  public.funcionario.nombres,
  public.funcionario.apellidos
FROM
  public.funcionario
where 
public.funcionario.documento = :cedula";
$stmt         = $connex->prepare($query);
$stmt->bindParam(':cedula', $cedula, PDO::PARAM_STR);
$stmt->execute();
$count        = $stmt->rowCount();
$row          = $stmt->fetch(PDO::FETCH_ASSOC);

$nombres   = $row['nombres'];
$apellidos = $row['apellidos'];


$Empleado = $nombres.' '.$apellidos;

$pdf = new PDF();
$pdf->SetTopMargin(0,20);
$pdf->AddPage();
$pdf->Ln(35);
$pdf->SetFont('Times','',12);
$pdf->SetY(50);
$pdf->SetX(8);
$pdf->Cell(20,6,$pdf->WriteHTML('<b>Cedula:</b> '.$cedula),0,0,'L');
$pdf->SetY(50);
$pdf->SetX(50);
$pdf->Cell(20,6,$pdf->WriteHTML('<b>Nombre y Apellido:</b> '.$Empleado),0,0,'L');
$pdf->Ln();
$pdf->WriteHTML('<br><hr>');

$query     = "SELECT DISTINCT
sum(public.nominapagodetalleconceptos.asignacion) AS totalasig,
EXTRACT(month FROM nominapago.fhasta) as mes,
EXTRACT(year FROM nominapago.fhasta) AS ano
FROM
public.nominapagodetalle
INNER JOIN public.nominapago ON (public.nominapagodetalle.nominapago = public.nominapago.id)
INNER JOIN public.nominapagodetalleconceptos ON (public.nominapagodetalle.id = public.nominapagodetalleconceptos.nominapagodetalle)
WHERE
public.nominapago.status = 'PR' AND
public.nominapagodetalle.cedula = :cedula AND
nominapago.frecuencianomina <> 9 AND
nominapago.frecuencianomina <> 20 AND
nominapago.frecuencianomina <> 24 AND
nominapagodetalleconceptos.asignacion > 0 AND
EXTRACT(year FROM nominapago.fhasta) = :anno
GROUP BY
mes,
ano
ORDER BY
EXTRACT(month FROM nominapago.fhasta) asc";

$stmt      = $connex->prepare($query);
$stmt->bindParam(':anno', $anno, PDO::PARAM_STR);
$stmt->bindParam(':cedula', $cedula, PDO::PARAM_STR);
$stmt->execute();


while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
	$monto     = $row['totalasig'];
	$mes       = $meses[$row['mes'] - 1];
	$total += $monto;
	$pdf->Ln(10);
	$pdf->SetX(70);
	$pdf->SetFont('Times','',12);
	$pdf->Cell(20,3,$mes,0,0,'L');
	$pdf->SetX(110);
	$pdf->Cell(20,3, number_format($monto, 2, ',', '.'),0,0,'R');


}

$pdf->Ln(15);
$pdf->WriteHTML('<br><hr>');
$pdf->SetFont('Times','B',12);
$pdf->Ln(5);
$pdf->SetX(110);
$pdf->Cell(20,3,'TOTAL ANUAL:    '.number_format($total, 2, ',', '.'),0,0,'R');

$archivo='../TMP/certificado.pdf';
$pdf->SetDisplayMode('real');
$pdf->Output($archivo,'F');

?>