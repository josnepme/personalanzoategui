<?php
include("../Conexion/conecta.php");
require_once("../utilidades/class.phpmailer.php");
require_once("../utilidades/class.smtp.php");
$cedula = trim($_POST['cedula']);
$asunto = trim($_POST['asunto']);
$mensaje = trim($_POST['mensaje']);

/*
$cedula = "9671952";
$asunto = "asunto";
$mensaje= "mensaje";
*/


if(isset($_POST['cedula']) && $_POST['cedula'] != ''){

$conn   = new Conexion();
$connex = $conn->abrircon();
$query  = "select * from usuarios_nom where cedulas =:cedula";
$stmt   = $connex->prepare($query);
$stmt->bindParam(':cedula', $cedula, PDO::PARAM_STR);
$stmt->execute();
$count  = $stmt->rowCount();
$row    = $stmt->fetch(PDO::FETCH_ASSOC);

if($count == 1 && !empty($row)){
	$visitor_name    = $row['cedula'].", ".$row['nombres']." ".$row['apellidos'];
	$visitor_email   = $row['correo'];
	$email_title     = $asunto;
	$visitor_message = $mensaje;


	$mail            = new PHPMailer();
	$mail->CharSet = 'UTF-8';

	$mail->IsSMTP();
	$mail->Host = 'server.anzoategui.gob.ve';
	$mail->SMTPSecure = 'ssl';
	$mail->Port = 465;
	$mail->SMTPDebug = 1;
	$mail->SMTPAuth = true;
	$mail->Username = 'gobanz.personal.soporte@anzoategui.gob.ve';
	$mail->Password = 'procdatos052017';
	$mail->SetFrom('gobanz.personal.soporte@anzoategui.gob.ve', $visitor_name);
	$mail->AddReplyTo('reclamosgobanz@gmail.com','Departamento Legal');
	$mail->Subject = $visitor_email." --- ".$email_title;
	$mail->MsgHTML($visitor_message);

	$mail->AddAddress('gobanz.personal.soporte@anzoategui.gob.ve', 'Soporte');


	$exito = $mail->Send(); // Envía el correo.
	if($exito){

		echo "enviado";

	}else{
		echo "no enviado";
	}
}

}else{
header('location:../index.php');
}

?>
