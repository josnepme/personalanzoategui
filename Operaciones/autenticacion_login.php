<?php 
session_start(); 
include("../Conexion/conecta.php");
include("../utilidades/seguridad.php");
if(isset($_POST['username']) && $_POST['username'] != '' && isset($_POST['password']) && $_POST['password'] != '') {
	$conn= new Conexion();
    $connex=$conn->abrircon();
	$username = trim($_POST['username']);
	$password = trim($_POST['password']);
	$clave_encriptada = $encriptar($password);
	if($username != "" && $password != "") {
		
		try {
			$query = "select * from usuarios_nom where usuario =:username and clave=:password";
			$stmt = $connex->prepare($query);
			$stmt->bindParam('username', $username, PDO::PARAM_STR);
			$stmt->bindValue('password', $clave_encriptada, PDO::PARAM_STR);
			$stmt->execute();
			$count = $stmt->rowCount();
			$row   = $stmt->fetch(PDO::FETCH_ASSOC);
			if($count == 1 && !empty($row)) {
				/******************** aqui lo que se quiere mandar a la otra pagina ***********************/
			    $_SESSION['sess_id'] = $row['id'];
			    $_SESSION['sess_ced'] = $row['cedulas'];
				$_SESSION['sess_username'] = $row['usuario'];
				$_SESSION['sess_pass'] = $row['clave'];
				$_SESSION['sess_name'] = $row['nombres'];
				$_SESSION['sess_last'] = $row['apellidos'];
				$_SESSION['sess_correo'] = $row['correo'];
				$_SESSION['sess_preg'] = $row['pregunta'];
				$_SESSION['sess_resp'] = $row['respuesta'];
				$_SESSION['sess_foto'] = $row['foto'];
				
				//echo "Formularios/menu.php";
				echo "./Formularios/menu.php";
				
			} else {
				echo "invalido";
			}
		} catch (PDOException $e) {
			echo "Error : ".$e->getMessage();
		}
	} else {
		echo "Campos Requeridos!";
	}
} else {
	header('location:../index.php');
}
?>
