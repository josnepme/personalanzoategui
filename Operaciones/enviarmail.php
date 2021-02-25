<?php
include("../Conexion/conecta.php");
include("../utilidades/seguridad.php");
require_once("../utilidades/class.phpmailer.php");
require_once("../utilidades/class.smtp.php");

ini_set("display_errors", 1);
error_reporting(E_WARNING | E_ERROR);

$conn       = new Conexion();
$connex     = $conn->abrircon();

$cedula     = $_POST['cedula'];
$respuestas = $_POST['respuestas'];
//$cedula = '18569267';
//$respuestas = 'azul';
if(isset($_POST['cedula'])){

$query      = "select * from usuarios_nom where cedulas =:cedula and respuesta = :respuesta";
$buscar     = $connex->prepare($query);
$buscar->bindParam(':cedula', $cedula, PDO::PARAM_STR);
$buscar->bindParam(':respuesta', $respuestas, PDO::PARAM_STR);
$buscar->execute();
$count      = $buscar->rowCount();
$row        = $buscar->fetch(PDO::FETCH_ASSOC);
if($count == 1 && !empty($row)){
	header('Content-Type: application/json');

	$nombres             = $row['nombres'];
	$apellidos           = $row['apellidos'];
	$cedula              = $row['cedulas'];
	$clave               = $row['clave'];
	$usuario             = $row['usuario'];
	$email               = $row['correo'];
	$clave_desencriptada = $desencriptar($clave);

	$asunto              = utf8_decode("Solicitud de recuperación de Datos.");
	$mensaje             = utf8_decode("Sr(a). $nombres $apellidos,<br><br>
	Hemos recibido una solicitud para enviarle los datos de ingreso a nuestra página.<br>
	Usuario: <strong>$usuario</strong>
	<br>
	Clave: <strong>$clave_desencriptada</strong>
	<br>
	<br>
	El presente correo es enviado automáticamente por nuestro sistema, por favor, no responda, ni reenvie mensajes a esta cuenta.");

	$mail                = new PHPMailer();
	$mail->IsSMTP();
	$mail->SMTPAuth = true;
	$mail->SMTPSecure = "ssl";
	$mail->Host = "server.anzoategui.gob.ve"; // SMTP a utilizar. Por ej. smtp.elserver.com
	$mail->Username = "gobanz.personal.soporte@anzoategui.gob.ve"; // Correo completo a utilizar
	$mail->Password = "procdatos052017"; // Contrase�a
	$mail->Port = 465; // Puerto a utilizar
	$mail->From = "gobanz.personal.soporte@anzoategui.gob.ve"; // Desde donde enviamos (Para mostrar)
	$mail->FromName = utf8_decode("Soporte Técnico Dirección de Personal-Gobernación de Anzoátegui");
	$mail->AddAddress($email); // Esta es la direcci�n a donde enviamos
	$mail->IsHTML(true); // El correo se env�a como HTML
	$mail->Subject = $asunto; // Este es el titulo del email.
	$mail->Body = $mensaje; // Mensaje a enviar
	// Texto sin html"

	$exito = $mail->Send(); // Env�a el correo.

 	$datos = array();
	$datos = null;

	if($exito){
		$datos = array(
			'estado'   => 'ok',
			'cedula'   => $cedula,
			'nombres'  => $nombres,
			'apellidos'=> $apellidos

		);

	}else{
		$datos = array(
			'estado'=> 'error'
		);
	}
}else{
	$datos = array(
		'estado'=> 'no'
	);
}
echo json_encode($datos, JSON_FORCE_OBJECT);

}else{
header('location:../index.php');
}


?>