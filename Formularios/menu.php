<?php
session_start();
$cedula   = "";
$usuario  = "";
$pass     = "";
$nombre   = "";
$apellido = "";
$correo   = "";
$id       = "";
$correo   = "";
$preg     = '';
$resp     = '';
$foto     = '';

if(isset($_SESSION['sess_username']) && $_SESSION['sess_username'] != "" && isset($_SESSION['sess_pass']) && $_SESSION['sess_pass'] != ''){
	$nombre   = $_SESSION['sess_name'];
	$id       = $_SESSION['sess_id'];
	$cedula   = $_SESSION['sess_ced'];
	$usuario  = $_SESSION['sess_username'];
	$apellido = $_SESSION['sess_last'];
	$correo   = $_SESSION['sess_correo'];
	$preg     = $_SESSION['sess_preg'];
	$resp     = $_SESSION['sess_resp'];
	$foto     = $_SESSION['sess_foto'];
	$fotos    = '';

	if($foto == null || $foto == ''){
		$fotos = "../Recursos/user-png-icon-15.jpg";
	}else{
		$fotos = "../Recursos/fotos_personal/".$_SESSION['sess_foto'];

	}


}else{
	header('location:../index.php');
}

?>

<?php header('Content-Type: text/html;charset=ISO-8859-1'); ?><!--Para poner los acentos y ñ-->
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="es-419" xml:lang="es-419">

	<head>
		<meta charset="ISO-8859-1">
		<meta http-equiv="Content-Type" content="text/html"; charset="ISO-8859-1"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="author" content="Jose Meneses"/>
		<link href="../Recursos/log.ico" rel="shortcut icon" type="image/x-icon"/>

		<title>
			Menu
		</title>


		<link href="../Bootstraps/css/bootstrap.css" rel="stylesheet" id="bootstrap-css" >
		<link rel="stylesheet" href="../font-awesome/css/font-awesome.min.css">
		<link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
		<link href="../Estilos/menu.css" rel="stylesheet">
		<link href="https://raw.githubusercontent.com/daneden/animate.css/master/animate.css" rel="stylesheet">


	</head>

	<body>

		<!--guardar las variables-->
		<input id = "cedula_trab" name="cedula_trab" type="hidden" value="<?php echo $cedula; ?>"/>
		<input id = "nombre_trab" name="nombre_trab" type="hidden" value="<?php echo $nombre; ?>"/>
		<input id = "apellido_trab" name="apellido_trab" type="hidden" value="<?php echo $apellido; ?>"/>
		<input id = "correo_trab" name="correo_trab" type="hidden" value="<?php echo $correo; ?>"/>
		<!--mensajes modales-->
		<!--Mensajes-->
		<div class="modal fade" id="modalcontacto" name="modalcontacto" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content bg-dark text-white">
					<!--aqui cambio el color -->
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">
							<i class="fa fa-envelope">
							</i> Contactanos
						</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">
								&times;
							</span>
						</button>
					</div>
					<div class="modal-body">
						<p class="m-0">
							Con gusto te ayudaremos.
						</p>
						<form>
							<div class="form-group">
								<label for="recipient-name" class="col-form-label">
									Asunto:
								</label>
								<input type="text" class="form-control" id="recipient-name">
							</div>
							<div class="form-group">
								<label for="message-text" class="col-form-label">
									Mensaje:
								</label>
								<textarea class="form-control" rows="3" id="message-text">
								</textarea>
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="boton botonxdefecto btn-md" id="bt_enviarmensaje" name="bt_enviarmensaje">
							Enviar mensaje
						</button>
						<button type="button" class="boton botonxdefecto btn-md" id="bt_cerrar" name="bt_cerrar" data-dismiss="modal">
							Cerrar
						</button>


					</div>
				</div>
			</div>
		</div>
		<!--Constancias-->
		<div class="modal fade" data-backdrop="static" data-keyboard="false" role="dialog" tabindex="-1" id="modalconstancia" name="modalconstancia">
			<div class="modal-dialog">
				<div class="modal-content bg-dark text-white">
					<div class="modal-header">
						<h5 class="modal-title">
							Descargue su Constancia
						</h5>

					</div>
					<div class="modal-body">
						<div class="form-group">
							<label for="selectcargos">
								Cargos
							</label>
							<select class="form-control bg-dark text-white" id="selectcargos">

							</select>
						</div>

						<div class="form-check">
							<input class="form-check-input" type="radio" name="flexRadioDefault" id="radiocnormal" value="1" checked>
							<label class="form-check-label" for="radiocnormal">
								Constancia Normal
							</label>
						</div>
						<div class="form-check">
							<input class="form-check-input" type="radio" name="flexRadioDefault" id="radiocintegral" value="2">
							<label class="form-check-label" for="radiocintegral">
								<span class="badge badge-pill badge-warning">
									En Construcción
								</span>
								Constancia Integral
							</label>
						</div>

						<div class="d-flex justify-content-center">
							<div class="spinner-border text-info" role="status" id="loaderconstancia">
								<span class="sr-only text-white">
									Procesando...
								</span>
							</div>
						</div>


					</div>
					<div class="modal-footer">
						<button type="button" class="boton botonxdefecto btn-md" id="bt_dconstancia" name="bt_dconstancia">
							Descargar
						</button>

						<button type="button" class="boton botonxdefecto btn-md" id="bt_cerrar_c" name="bt_cerrar_c" data-dismiss="modal">
							Cerrar
						</button>

					</div>
				</div>
			</div>
		</div>
		<!--Recibos-->
		<div class="modal fade" data-backdrop="static" data-keyboard="false" role="dialog" tabindex="-1" id="modalrecibo" name="modalrecibo">
			<div class="modal-dialog modal-lg">
				<div class="modal-content bg-dark text-white">
					<div class="modal-header">
						<h5 class="modal-title">
							Descargue su Recibo de Pago
						</h5>
					</div>
					<div class="modal-body">

						<div class="form-group">
							<label for="selectnomina">
								Nominas:
							</label>
							<select class="form-control bg-dark text-white" id="selectnomina">

							</select>
						</div>
						<div class="form-group">
							<label for="selectnomina">
								Periodos de Pago:
							</label>
							<select class="form-control bg-dark text-white" id="selectperiodo">

							</select>
						</div>
						<!--<div id="loaderGif"> <img class="img-responsive center-block"  src="../Recursos/loader.gif"></div>-->
						<div class="d-flex justify-content-center">
							<div class="spinner-border text-info" role="status" id="loaderrecibo">
								<span class="sr-only text-white">
									Procesando...
								</span>
							</div>
						</div>

					</div>
					<div class="modal-footer">
						<button type="button" class="boton botonxdefecto btn-md" id="bt_drecibo" name="bt_drecibo">
							Descargar
						</button>
						<button type="button" class="boton botonxdefecto btn-md" id="bt_cerrar_r" name="bt_cerrar_r" data-dismiss="modal">
							Cerrar
						</button>

					</div>
				</div>
			</div>
		</div>

		<!--Certificados-->
		<div class="modal fade" data-backdrop="static" data-keyboard="false" role="dialog" tabindex="-1" id="modalcertificado" name="modalcertificado">
			<div class="modal-dialog modal-sm">
				<div class="modal-content bg-dark text-white">
					<div class="modal-header">
						<h5 class="modal-title">
							Certificación de Ingresos
						</h5>
					</div>
					<div class="modal-body">

						<div class="form-group">
							<label for="selectanno">
								Años Fiscales:
							</label>
							<select class="form-control bg-dark text-white" id="selectanno">

							</select>
						</div>
						<div class="d-flex justify-content-center">
							<div class="spinner-border text-info" role="status" id="loadercertificado">
								<span class="sr-only">
									Procesando...
								</span>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="boton botonxdefecto btn-md" id="bt_dcertificado" name="bt_dcertificado">
							Descargar
						</button>
						<button type="button" class="boton botonxdefecto btn-md" id="bt_cerrar_cer" name="bt_cerrar_r" data-dismiss="modal">
							Cerrar
						</button>

					</div>
				</div>
			</div>
		</div>


		<!--perfil-->
		<div class="modal fade" data-backdrop="static" data-keyboard="false" role="dialog" tabindex="-1" id="modalperfil" name="modalperfil">
			<div class="modal-dialog">
				<div class="modal-content bg-dark text-white">
					<div class="modal-header">
						<h5 class="modal-title">
							Perfil de usuario
						</h5>

					</div>
					<div class="modal-body">

						<div class="container d-flex justify-content-center">
							<div class="p-3 py-4 card-perfil">
								<div class="text-center">
									<img src="<?php echo $fotos;?>" width="100" height="100" class="rounded-circle" id="fotoperfil1">
									<h3 class="mt-2">
										<?php echo $nombre." ".$apellido; ?>
									</h3>
									<span class="mt-1 clearfix">
										<i class="fa fa-user mr-3 ml-2">
										</i><?php echo $usuario;?>
									</span>
									<small class="mt-4">
										<i class="fa fa-envelope mr-3 ml-2">
										</i><?php echo $correo;?>
									</small>

								</div>
							</div>
						</div>

					</div>
					<div class="modal-footer">
						<button type="button" class="boton botonxdefecto btn-md" id="bt_editarp" name="bt_editarp">
							Editar
						</button>

						<button type="button" class="boton botonxdefecto btn-md" id="bt_cerrar_p" name="bt_cerrar_p" data-dismiss="modal">
							Cerrar
						</button>

					</div>
				</div>
			</div>
		</div>
		<!--edicion de perfil-->

		<div class="modal fade" data-backdrop="static" data-keyboard="false" role="dialog" tabindex="-1" id="modaleditperfil" name="modaleditperfil">
			<div class="modal-dialog modal-lg">
				<div class="modal-content bg-dark text-white">
					<div class="modal-header">
						<h5 class="modal-title">
							Configuración de Perfil
						</h5>

					</div>
					<div class="modal-body">
						<div class="container rounded bg-dark text-white mt-5 mb-5">
							<div class="row">
								<div class="col-md-3 border-right">
									<div class="d-flex flex-column align-items-center text-center p-3 py-5">
										<img class="rounded-circle mt-5" width="150" height="150" src="<?php echo $fotos ?>" id="fotoperfil2" >
										<span class="font-weight-bold">
											<?php echo $nombre." ".$apellido; ?>
										</span>
										</br>
										<div class="d-flex justify-content-center">
											<div class="spinner-border text-info" role="status" id="loaderfoto">
												<span class="sr-only">
													Procesando...
												</span>
											</div>
										</div>
										<input type="file" name="buscarfoto" id="buscarfoto" class="buscafoto" >
										<label for="buscarfoto"  class="labelfoto btn-md" id="labelfoto">
											Cambiar Foto
										</label>
										<button type="button" class="boton botonxdefecto btn-md" id="bt_borrarimagen" name="bt_borrarimagen">
											Borrar Foto
										</button>
									</div>

								</div>

								<div class="p-3 py-5">
									<div class="d-flex justify-content-between align-items-center mb-3">
										<h4 class="text-center">
											Los cambios se haran efectivos al loguearse
										</h4>
									</div>
									<hr class="border-top mt-3 mb-3 text-white" />
									<div class="row mt-3">
										<div class="col-md-12">
											<label class="labels">
												Correo:
											</label><input type="email" id="txtcorreo" class="form-control" placeholder="Cambie su correo" value="<?php echo $correo; ?>">
										</div>

									</div>
									</br>

									<button type="button" class="boton botonxdefecto btn-md" id="bt_salvarcorreo" name="bt_salvarcorreo">
										Cambiar Correo
									</button>
									<br>
										<hr class="border-top mt-3 mb-3 text-white" />
										<div class="row mt-3">
											<div class="col-md-12">
												<label class="labels">
													Usuario:
												</label><input type="text" id="txtusuario" class="form-control" placeholder="Cambie su usuario" value="<?php echo $usuario; ?>">
											</div>
										</div>
									</br>
									<button type="button" class="boton botonxdefecto btn-md" id="bt_salvarusuario" name="bt_salvarusuario">
										Cambiar Usuario
									</button>
									<br>
										<hr class="border-top mt-3 mb-3 text-white" />
										<div class="row mt-3">
											<div class="col-md-6">
												<label class="labels">
													Contraseña Antigua:
												</label><input type="password" id="txtclave1" class="form-control" placeholder="Contraseña antigua" value="">
											</div>
											<div class="col-md-6">
												<label class="labels">
													Contraseña nueva:
												</label><input type="password" id="txtclave2" class="form-control" value="" placeholder="Contraseña nueva">
											</div>
										</div>
									</br>
									<button type="button" class="boton botonxdefecto btn-md" id="bt_salvarclave" name="bt_salvarclave">
										Cambiar Contraseña
									</button>
									<br>
										<hr class="border-top mt-3 mb-3 text-white" />
										<div class="row mt-3">
											<div class="col-md-12">
												<select id="combopre" name="combopre" class="form-control form-control-md-8 form-control-sm-12 form-control-lg-12 col-md-12" aria-label="select preguntas" >
													<option value="<?php echo $preg; ?>" selected >
														<?php echo $preg; ?>
													</option>
													<option value="Color Favorito">
														Color Favorito
													</option>
													<option value="Comida Favorita">
														Comida Favorita
													</option>
													<option value="Segundo Apellido de la madre">
														Segundo Apellido de la Madre
													</option>
													<option value="Nombre de Mascota favorita">
														Nombre de Mascota Favorita
													</option>
													<option value="Nombre Primer Hijo">
														Nombre Primer Hijo
													</option>
												</select>
												<label class="labels">
													Respuesta:
												</label><input type="text" id="txtrespuesta" class="form-control" placeholder="Cambie su respuesta" value="<?php echo $resp; ?>">
											</div>
										</div>
									</br>
									<button type="button" class="boton botonxdefecto btn-md" id="bt_salvarpregunta" name="bt_salvarpregunta">
										Cambiar Respuesta o pregunta
									</button>
									<br><hr class="border-top mt-3 mb-3 text-white" />

									<div class="mt-5 text-center">

										<button type="button" class="boton botonxdefecto btn-md" id="bt_cerrar_edicion" name="bt_cerrar_edicion" data-dismiss="modal">
											Cerrar
										</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!--Informacion personal-->

		<div class="modal fade" data-backdrop="static" data-keyboard="false" role="dialog" tabindex="-1" id="modalpersonal" name="modalpersonal">
			<div class="modal-dialog">
				<div class="modal-content bg-dark text-white">
					<div class="modal-header">
						<h5 class="modal-title">
							Datos Personales
						</h5>

					</div>
					<div class="modal-body">

						<i class="fa fa-address-card text-white">  <strong>EMPLEADO: </strong> 
							<label id="EMPLEADO" class="text-info">
							</label>
						</i>
						<br/>
						<i class="fa fa-venus-mars text-white">  <strong>SEXO: </strong> 
							<label id="sexo" class="text-info">
							</label>
						</i>
						<br/>
						<i class="fa fa-calendar text-white">  <strong>FECHA DE NACIMIENTO: </strong> 
							<label id="fechan" class="text-info">   
							</label>
						</i>
						<br/>
						<i class="fa fa-map-marker text-white">  <strong>LUGAR DE NACIMIENTO: </strong>
							<label id="lugarn" class="text-info">  
							</label>
						</i>
						<br/>
						<i class="fa fa-compass text-white">  <strong>DIRECCIÓN: </strong>
							<label id="dire" class="text-info">  
							</label>
						</i>
						<br/>
						<i class="fa fa-phone-square text-white">  <strong>TELEFONOS: </strong>
							<label id="tele" class="text-info">  
							</label>
						</i>
					</div>


					<div class="modal-footer">

						<button type="button" class="boton botonxdefecto btn-md" id="bt_cerrar_dp" name="bt_cerrar_dp" data-dismiss="modal">
							Cerrar
						</button>

					</div>
				</div>
			</div>
		</div>




		<!--fin de mensajes modales-->

		<div class="page-wrapper chiller-theme toggled">
			<a id="show-sidebar" class="btn btn-sm btn-dark" href="#">
				<i class="fa fa-bars">
				</i>
			</a>
			<nav id="sidebar" class="sidebar-wrapper">
				<div class="sidebar-content">
					<div class="sidebar-brand">

						<div id="close-sidebar">
							<i class="fa fa-caret-square-o-left">
							</i>
						</div>
					</div>

					<div class="container-fluid align-content-center">
						<div class="row justify-content-center">
							<img class="img-responsive rounded-circle center-block" width="96" height="96" src="<?php echo $fotos;?>" id="fotoperfil3">

						</div>
						<div class="sidebar-menu">
							<ul>
								<li>
									<li class="sidebar-dropdown justify-content-center text-center">

										<a href="#" >
											<strong>
												<?php
												echo $nombre." ".$apellido;
												?>
											</strong>
										</a>

										<div class="sidebar-submenu text-left">
											<ul>
												<li>
													<a href="#" id="perfil">
														Perfil
													</a>
												</li>
												<li>
													<a href="../Operaciones/cerrarsesion.php">
														Cerrar Sesion
													</a>
												</li>
											</ul>
										</div>
									</li>
								</li>

							</ul>
						</div>
					</div>


					<!-- sidebar-header  -->


					<div class="sidebar-menu">
						<ul>
							<li class="header-menu">
								<span>
									General
								</span>
							</li>

							<li class="sidebar-dropdown">
								<a href="#">
									<i class="fas fa-info-circle">
									</i>
									<span>
										Información
									</span>
								</a>
								<div class="sidebar-submenu">
									<ul>
										<li>
											<a href="#" id="datosp">
												Datos Personales
											</a>
										</li>
										<li>
											<a href="#">
												<span class="badge badge-pill badge-warning">
													En Construcción
												</span>
												Datos Laborales
											</a>
										</li>
									</ul>
								</div>
							</li>
							<li class="sidebar-dropdown">
								<a href="#">
									<i class="fa fa-newspaper-o">
									</i>
									<span>
										Servicios
									</span>

								</a>
								<div class="sidebar-submenu">
									<ul>
										<li>
											<a href="#" id="constancias">
												Constancias de Trabajo
											</a>
										</li>
										<li>
											<a href="#" id="recibos">
												Recibos de Pago
											</a>
										</li>
										<li>
											<a href="#" id="certificados">
												Certificación de Ingreso
											</a>
										</li>
									</ul>
								</div>
							</li>


							<li class="header-menu">
								<span>
									Ubicanos
								</span>
							</li>
							<li>
								<a href="#" id="contacto">
									<i class="fa fa-envelope">
									</i>
									<span>
										Contactanos
									</span>
								</a>
							</li>
							<li>
								<a href="#mapa">
									<i class="fa fa-map-marker">
									</i>
									<span>
										Donde Ubicarnos
									</span>
								</a>
							</li>
							<li class="header-menu">
								<span>
									Enlaces de Interés
								</span>
							</li>
							<li>
								<a href="http://anzoategui.gob.ve/personal/" target="_blank">
									<i class="fa fa-link">
									</i>
									<span>
										Recibos Anteriores
									</span>
								</a>
							</li>
							<li>
								<a href="https://www.anzoategui.gob.ve/" target="_blank">
									<i class="fa fa-link">
									</i>
									<span>
										Gobernación de Anzoátegui
									</span>
								</a>
							</li>
							<li>
								<a href="http://declaraciones.seniat.gob.ve/portal/page/portal/PORTAL_SENIAT" target="_blank">
									<i class="fa fa-link">
									</i>
									<span>
										SENIAT
									</span>
								</a>
							</li>
							<li>
								<a href="http://www.cgr.gob.ve/" target="_blank">
									<i class="fa fa-link">
									</i>
									<span>
										Contraloria General de la Republica
									</span>
								</a>
							</li>
						</ul>
					</div>
					<!-- sidebar-menu  -->
				</div>

			</nav>
			<!-- sidebar-wrapper  -->
			<!--aqui todo el contenido que se quiera poner-->
			<main id="principal"  class="page-content  h-100 bg-dark">
				<div class="container-fluid" id="tope">
					<div id="myCarousel" class="carousel slide carousel-fade shadow-lg p-3 mb-5 bg-dark rounded" data-ride="carousel">
						<div class="carousel-inner">
							<div class="carousel-item active">
								<div class="mask flex-center">
									<div class="container">
										<div class="row align-items-center">
											<div class="col-md-7 col-12 order-md-1 order-2">
												<h4>
													Gobernación del Estado Anzoátegui
												</h4>
												<p>
													Dirección de Personal-Depto. De Procesamiento de Datos.
												</p>
											</div>
											<div class="col-md-5 col-12 order-md-2 order-1">
												<img src="../Recursos/trabajadores15na.jpg" class="mx-auto" alt="slide">
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="carousel-item">
								<div class="mask flex-center">
									<div class="container">
										<div class="row align-items-center">
											<div class="col-md-7 col-12 order-md-1 order-2">
												<h4>
													Fe de Vida
												</h4>
												<p>
													Recepción de la fe de vida del Personal Jubilado y Pensionado.
												</p>
												<a href="#seccion1" id="leerseccion1">
													Leer mas
												</a>
											</div>
											<div class="col-md-5 col-12 order-md-2 order-1">
												<img src="../Recursos/fe de vida.jpg" class="mx-auto" alt="slide">
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="carousel-item">
								<div class="mask flex-center">
									<div class="container">
										<div class="row align-items-center">
											<div class="col-md-7 col-12 order-md-1 order-2">
												<h4>
													Cuidate Contra el Covid 19
												</h4>
												<p>
													La COVID-19 es la enfermedad infecciosa causada por el coronavirus que se ha descubierto más recientemente. Tanto este nuevo virus como la enfermedad que provoca eran desconocidos antes de que estallara el brote en Wuhan (China) en diciembre de 2019. Actualmente la COVID-19 es una pandemia que afecta a muchos países de todo el mundo.
												</p>
												<a href="#seccion2">
													Leer mas
												</a>
											</div>
											<div class="col-md-5 col-12 order-md-2 order-1">
												<img src="../Recursos/5330219.png" class="mx-auto" alt="slide">
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="carousel-item">
								<div class="mask flex-center">
									<div class="container">
										<div class="row align-items-center">
											<div class="col-md-7 col-12 order-md-1 order-2">
												<h4>
													Gobernador del <br>
													Estado Anzoátegui
												</h4>
												<p>
													La gestión del Gobernador Antonio Barreto Sira, está basada en la transformación del estado, a través del tra														bajo sectorial que realizara directamente en la calle con la ejecución del ...
												</p>
												<a href="#seccion3">
													Leer mas
												</a>
											</div>
											<div class="col-md-5 col-12 order-md-2 order-1">
												<img src="../Recursos/gobernador.png" class="mx-auto" alt="slide">
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<a class="carousel-control-prev" href="#myCarousel" role="button" data-slide="prev">
							<span class="carousel-control-prev-icon" aria-hidden="true">
							</span>
							<span class="sr-only">
								Previous
							</span>
						</a>
						<a class="carousel-control-next" href="#myCarousel" role="button" data-slide="next">
							<span class="carousel-control-next-icon" aria-hidden="true">
							</span>
							<span class="sr-only">
								Next
							</span>
						</a>
					</div>
					<!--slide end-->
				</div>
				<div class="container-fluid">
					<!--Mensajes-->

					<div class="span8 text-white">
						<hr>
						<div id="seccion1">
							<h1>
								Recepción de Fe de Vida, para el Personal Jubilado y Pensionado
							</h1>
							<p>
								<strong>
									Jornada de Recepción de la fe de vida.
								</strong><br/> En las Inmediaciones del estacionamiento del Edificio sede se estara recibiendo la fe de vida del personal jubilado y pensionado de la Gobernación del Estado Anzoategui.
								Desde 01/12/2020 Hasta el 28/02/2021.
							</p>

							<span class="badge badge-success">
								Posteado 11/12/2020
							</span>
						</div>
						<hr>
						<div id="seccion2">
							<h1>
								¡Cuidate contra el covid!
							</h1>
							<p lang="es-ES">
								Lávese periódica y cuidadosamente las manos con un gel hidroalcohólico o con agua y jabón. Esto elimina los gérmenes que pudieran estar en sus manos, incluidos los virus.
								Evite tocarse los ojos, la nariz y la boca. Las manos tocan muchas superficies en las que podrían coger el virus. Una vez contaminadas, pueden transportar el virus a los ojos, la nariz o la boca. Desde allí el virus puede entrar en el organismo e infectarlo.
								Al toser o estornudar cúbrase la boca y la nariz con el codo flexionado o con un pañuelo. Luego, tire inmediatamente el pañuelo en una papelera con tapa y lávese las manos. Con la observancia de buenas prácticas de ‘higiene respiratoria’ usted protege a las personas de su entorno contra los virus causantes de resfriados, gripe y COVID-19.
								Limpie y desinfecte frecuentemente las superficies, en particular las que se tocan con regularidad, por ejemplo, picaportes, grifos y pantallas de teléfonos.
							</p>

							<span class="badge badge-success">
								Posteado 11/12/2020
							</span>
						</div>
						<hr>
						<div id="seccion3">
							<h1>
								Gobernador del Estado Anzoátegui
							</h1>
							<p>
								La gestión del Gobernador Antonio Barreto Sira, está basada en la transformación del estado, a través del trabajo sectorial que realizara directamente en la calle con la ejecución del "Plan Anzoátegui en Movimiento", y garantizarles una mejor calidad de vida a todos anzoatiguenses.
								Su principal línea está enmarcada en el sector salud, vivienda y la educación asegurando que aplicará una gestión con vocación social, con compromiso gerencial con acciones sin tintes políticos, solo basta que sean los ciudadanos los que demanden atención.
							</p>

							<span class="badge badge-success">
								Posteado 11/12/2020
							</span>
						</div>
						<hr>

					</div>

					<br/>

					<!--Google map-->

					<div
						class="card-deck bg-dark shadow p-3 mb-5 bg-dark rounded" style="width:800px height="500px" id="mapa">

						<div class="card-body">
							<div class="google-maps ">
								<div class="card-header text-white">
								</div>
								<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d8560.806320609161!2d-64.69292670309086!3d10.13681941237713!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xbbf742e5f7915c2!2sGobernaci%C3%B3n%20del%20Estado%20Anzo%C3%A1tegui!5e1!3m2!1ses!2sus!4v1607647000786!5m2!1ses!2sus" width="600" height="450" frameborder="0" style="border:0;" tabindex="0">
								</iframe>
							</div>

						</div>
						<div class="card bg-dark">
							<div class="card-header text-white">
								<strong>
									Donde Ubicarnos
								</strong>
							</div>
							<div class="card-body justify-content-center">
								<p class="card-text text-white">
									Av. 5 de Julio Edif. General de Gobierno, Jose Antonio Anzoátegui, Frente al Palacio de Justicia, Barcelona Estado Anzoátegui.
								</p>
							</div>
						</div>
					</div>

					<!-- The scroll to top feature -->
					<div class="row justify-content-center">

						<a href="#tope" class="boton botonxdefecto">
							<span class="fa fa-chevron-up">
							</span>  Subir
						</a>

					</div>


				</div>

				</br></br></br></br>
				<div class="footer text-light text-center" style="margin-bottom:1">
					<p>
						<?php
						$date = date("Y");
						echo "&copy; $date. Todos Los Derechos Reservados. Gobernacion del Estado Anzoategui. Direccion de Personal-Dept. de Procesamiento de Datos.";
						?>
					</p>

				</div>

			</main>


		</div>


		<!-- page-wrapper -->
		<script src="../js/jquery.min.js">
		</script>
		<script src="../js/jquery.redirect.min.js">
		</script>
		<script src="../js/jquery-ui.min.js">
		</script>
		<script src="../js/popper.min.js">
		</script>
		<script src="../Bootstraps/js/bootstrap.min.js">
		</script>
		<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10">
		</script>

		<script type="text/javascript">
			window.addEventListener("load", function() {
					$('#loaderconstancia').hide();
					$('#loaderrecibo').hide();
					$('#loadercertificado').hide();
					$('#loaderfoto').hide();
					//	$('#principal').load('main.php');

				});
		</script>
		<script type="text/javascript">
			const Toast = Swal.mixin({
					toast: true,
					position: 'top',
					showConfirmButton: false,
					timer: 3000,
					timerProgressBar: false,
					didOpen: (toast) => {
						toast.addEventListener('mouseenter', Swal.stopTimer)
						toast.addEventListener('mouseleave', Swal.resumeTimer)
					}
				})

		</script>


		<script>

			jQuery(function ($)	{

					$(".sidebar-dropdown > a").click(function()
						{
							$(".sidebar-submenu").slideUp(200);
							if (
								$(this)
								.parent()
								.hasClass("active")
							)
							{
								$(".sidebar-dropdown").removeClass("active");
								$(this)
								.parent()
								.removeClass("active");
							} else
							{
								$(".sidebar-dropdown").removeClass("active");
								$(this)
								.next(".sidebar-submenu")
								.slideDown(200);
								$(this)
								.parent()
								.addClass("active");
							}
						});

					$("#close-sidebar").click(function()
						{
							$(".page-wrapper").removeClass("toggled");
						});
					$("#show-sidebar").click(function()
						{
							$(".page-wrapper").addClass("toggled");
						});

				});

		</script>
		<!--accion de los botones-->
		<script type="text/javascript">

			$('#ini').on('click', function(){
					//	$('#principal').load('main.php');
				});
			$('#perfil').on('click', function(){

					$('#modalperfil').modal('show');

				});
			$('#contacto').on('click', function(){

					$('#modalcontacto').modal({backdrop: 'static', keyboard: false});

				});
			$('#constancias').on('click', function(){
					$('#modalconstancia').modal({backdrop: 'static', keyboard: false});
					llenarcargo();

				});
			$('#recibos').on('click', function(){

					$('#modalrecibo').modal({backdrop: 'static', keyboard: false});
					var nom = document.getElementById("selectnomina").value;
					llenarnomina();

				});
			$("#bt_enviarmensaje").on('click',function(e){

					e.preventDefault();
					var cedula = document.getElementById("cedula_trab").value;
					var asunto = document.getElementById("recipient-name").value;
					var mensaje = document.getElementById("message-text").value;


					if($.trim(asunto) == ""){

						Toast.fire({icon: 'warning',title:'Escriba un Asunto'});
						$("#recipient-name").focus();
						return false;
					}else if($.trim(mensaje) == ""){

						Toast.fire({icon: 'warning',title:'Escriba un Mensaje'});
						$("#message-text").focus();
						return false;

					}else{
						$.ajax({
								url: "../Operaciones/enviarmensaje.php",
								type: "post",

								data: {
									"cedula":cedula,"asunto":asunto,"mensaje":mensaje
								},
								beforeSend: function(){
									Toast.fire({icon: 'info',title:'Procesando solicitud...'});
									$("#bt_enviarmensaje").prop("disabled", true);
								},

								success: function (resultado){

									Toast.fire({icon: 'success',title:'Gracias por contactarnos, recibira una respuesta las proximas 24 horas.'});
									$('#modalcontacto').modal('hide');
									$('#recipient-name').val('');
									$('#message-text').val('');
									$("#bt_enviarmensaje").prop("disabled", false);
								},
								error: function() {
									Toast.fire({icon: 'error',title:'No se Pudo enviar'});
								}

							});
					}
				});

			$('#bt_dconstancia').on('click', function(e){
					e.preventDefault();
					var combo = document.getElementById("selectcargos").value;
					var radio=  $('input:radio[name=flexRadioDefault]:checked').val();

					if(combo!=''){
						if(radio==1){
							$.ajax({
									type: 'POST',
									datatype: "html",
									url: '../Operaciones/constanciasN.php',
									data: {
										'id':combo
									},

									beforeSend: function(){
										Toast.fire({icon: 'info',title: 'Procesando solicitud...'});
										$('#modalconstancia').modal('hide');
									},
									success: function(resp) {
										window.location.href='../Operaciones/descargaconstancia.php';


									},
									fail:function(){

										Toast.fire({icon: 'error',title: resp});
									}


								});
							return false;
						}else{

							Toast.fire({icon: 'info',title:'En Construcción'});
						}

					}else{
						Toast.fire({icon: 'warning',title:'Seleccione un cargo'});
					}

				});
			$('#bt_drecibo').on('click', function(e){
					e.preventDefault();
					var per = document.getElementById("selectperiodo").value;

					descargarrecibo(per);
					$('#modalrecibo').modal('hide');
					$('#selectnomina').val('');
					$('#selectperiodo').val('');

				});
			$('#certificados').on('click', function(e){
					$('#modalcertificado').modal({backdrop: 'static', keyboard: false});
					llenaranno();
				});

			$('#bt_dcertificado').on('click', function(e){
					e.preventDefault();
					var combo = document.getElementById("selectanno").value;
					var cedu = $("#cedula_trab").val();

					if(combo!=''){

						$.ajax({
								type: 'POST',
								datatype: "html",
								url: '../Operaciones/generacertificado.php',
								data: {
									'anno':combo, 'cedula':cedu
								},

								beforeSend: function(){
									Toast.fire({icon: 'info',title:'Procesando solicitud...'});
									$('#modalcertificado').modal('hide');
								},
								success: function(resp) {

									window.location.href='../Operaciones/descargacertificado.php';

								},
								fail:function(){
									Toast.fire({icon: 'error',title:resp});
								}

							});
						return false;

					}else{

						Toast.fire({icon: 'warning',title:'Seleccione un Año'});
					}

				});


			$('#bt_editarp').on('click', function(e){
					$('#modaleditperfil').modal('show');
				});

			$('#bt_salvarcorreo').on('click', function(e){

					var correo = document.getElementById("txtcorreo").value;

					var expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
					if($.trim(correo) == ""){

						Toast.fire({icon: 'warning',title:'Ingrese un correo valido, no puede dejaer el campo vacio'});
						$("#txtcorreo").focus();
						return false;
					}else{
						if ( !expr.test(correo) ){
							Toast.fire({icon: 'warning',title: 'La dirección de correo es incorrecta, ejemplo: alguien@dominio.com'});
							$("#txtcorreo").focus();
							return false;
						}else{
							cambiarcorreo();

						}
					}
				});

			$('#bt_salvarusuario').on('click', function(e){
					var user = document.getElementById("txtusuario").value;

					if($.trim(user) == ""){
						Toast.fire({icon: 'warning',title:'El usuario no puede quedar vacio'});
						$("#txtusuario").focus();
						return false;
					}else{
						cambiarusuario()

					}

				});
			$('#bt_salvarclave').on('click', function(e){
					var clave1 = document.getElementById("txtclave1").value;
					var clave2 = document.getElementById("txtclave2").value;

					if($.trim(clave1) == ""){
						Toast.fire({icon: 'warning',title:'Introduzca su antigua contraseña...'});
						$("#txtclave1").focus();
						return false;
					}else{
						if($.trim(clave2) == ""){
							Toast.fire({icon: 'warning',title:'Introduzca su nueva contraseña...'});
							$("#txtclave2").focus();
							return false;
						}else{
							cambioclave();

						}

					}

				});

			$('#bt_salvarpregunta').on('click', function(e){

					var comb = document.getElementById("combopre").value;
					var resp = document.getElementById("txtrespuesta").value;
					if($.trim(resp) == ""){
						Toast.fire({icon: 'warning',title:'Introduzca la respuesta'});
						$("#txtrespuesta").focus();
						return false;

					}else{
						cambioresp();
					}
				});
			$("#buscarfoto").change(function(){
					//var files = $('#buscarfoto')[0].files[0];
					var file = this.files[0];
					var imagefile = file.type;
					var size = file.size;
					var match= ["image/jpeg","image/png","image/jpg","image/gif"];
					if(!((imagefile==match[0]) || (imagefile==match[1]) || (imagefile==match[2])|| (imagefile==match[3]))){
						Toast.fire({icon: 'warning',title:'Seleccione una imagen valida'});
						$("#buscarfoto").val('');
						return false;
					}else{
						if(size>2000000){
							Toast.fire({icon: 'warning',title:'El Tamaño tiene que ser menor de 2 MB'});
							$("#buscarfoto").val('');
							return false;
						}else{
							cambiofoto();
						}

					}
				});

			$('#bt_borrarimagen').on('click', function(e){
					borrarfoto();

				});



			$('#datosp').on('click', function(){

					$('#modalpersonal').modal({backdrop: 'static', keyboard: false});
					mostrardatosper();

				});

		</script>

		<!--/******************************************Funciones********************************************/-->
		<script type="text/javascript">
			/*Lleno el select de los cargos*/
			function llenarcargo(){
				$("#selectcargos option").remove();
				$("#selectcargos").append('<option value=""  selected disabled >-- Seleccione el Cargo --</option>');

				var cedu = $("#cedula_trab").val();

				$.ajax({
						type: 'POST',
						url: '../Operaciones/cargos_constancia.php',
						data: {
							'cedula':cedu
						},
						beforeSend: function(){

							$('#loaderconstancia').show();
							Toast.fire({icon: 'info',title:'Cargando los cargos...'});
						}

					})
				.done(function(datos){
						$('#loaderconstancia').hide();
						$("#selectcargos").append(datos.opcion);


					})
				.fail(function(){

						Toast.fire({icon: 'error',title:'Hubo un error Procesando solicitud'});
					})
			}
			/*lleno el select de las nominas*/
			function llenarnomina(){
				$("#selectnomina option").remove();
				$("#selectnomina").append('<option value=""  selected disabled >-- Seleccione una Nomina--</option>');
				$("#selectperiodo option").remove();
				$("#selectperiodo").prop('disabled',true);
				$("#bt_drecibo").prop('disabled',true);

				var cedu = $("#cedula_trab").val();

				$.ajax({
						type: 'POST',
						url: '../Operaciones/nominas_recibos.php',
						data: {
							'cedula':cedu
						},
						beforeSend: function(){
							Toast.fire({icon: 'info',title:'Cargando las Nominas...'});
							$('#loaderrecibo').show();
						}
					})
				.done(function(datos){
						$("#selectnomina").append(datos.opcion);
						$('#loaderrecibo').hide();
						$("#selectnomina").change(function(){
								var nom = document.getElementById("selectnomina").value;
								var per = document.getElementById("selectperiodo").value;
								if(nom!==''){

									$("#selectperiodo").prop('disabled',false);

									llenarperiodo(nom);
								}else{
									$("#selectperiodo").prop('disabled',true);

									$("#selectperiodo option").remove();
									//	$("#bt_drecibo").prop('disabled',true);
								}

							});
					})
				.fail(function(){

						Toast.fire({icon: 'error',title:'Hubo un error Procesando solicitud...'});
					})
			}
			/*lleno el select del periodo*/

			function llenarperiodo(tip){
				$("#selectperiodo option").remove();
				$("#selectperiodo").append('<option value=""  selected disabled >-- Seleccione el Periodo --</option>');

				var cedu = $("#cedula_trab").val();
				$.ajax({
						type: 'POST',
						url: '../Operaciones/periodo_recibos.php',
						data: {
							'tip':tip,'cedula':cedu
						},
						beforeSend: function(){
							$('#loaderrecibo').show();
							Toast.fire({icon: 'info',title:'LLenando los Periodos de Pago...'});
						}
					})
				.done(function(datos){
						$("#selectperiodo option").remove();
						$("#selectperiodo").append('<option value=""  selected disabled >-- Seleccione el Periodo --</option>');
						$("#selectperiodo").append(datos.opcion);
						$("#selectperiodo").change(function(){

								$("#bt_drecibo").prop('disabled',false);
							});

						$('#loaderrecibo').hide();

					})
				.fail(function(){

						Toast.fire({icon: 'error',title:'Hubo un error Procesando solicitud...'});
					})


			}
			function descargarrecibo(id){


				var cedu = $("#cedula_trab").val();
				$.ajax({
						type: 'POST',
						url: '../Operaciones/genera_recibo.php',
						data: {
							'id':id,'cedula':cedu
						},
						beforeSend: function(){

							Toast.fire({icon: 'info',title:'Procesando solicitud...'});
						}
					})
				.done(function(datos){


						window.location.href='../Operaciones/descarga_recibo.php';

					})
				.fail(function(){

						Toast.fire({icon: 'error',title:'Hubo un error Procesando solicitud...'});
					})
			}
			function llenaranno(){
				$("#selectanno option").remove();
				$("#selectanno").append('<option value=""  selected disabled >-- Seleccione un Año --</option>');

				var cedu = $("#cedula_trab").val();

				$.ajax({
						type: 'POST',
						url: '../Operaciones/busca_anno.php',
						data: {
							'cedula':cedu
						},
						beforeSend: function(){

							Toast.fire({icon: 'info',title:'Cargando los  Años...'});
							$('#loadercertificado').show();
						}
					})
				.done(function(datos){
						$("#selectanno").append(datos);
						$('#loadercertificado').hide();

					})
				.fail(function(){

						Toast.fire({icon: 'error',title:'Hubo un error Procesando solicitud...'});

					})
			}

			function cambiarcorreo(){

				var cedu = $("#cedula_trab").val();
				var correo =$("#txtcorreo").val();

				//Toast.fire({icon: 'info',title:'Cedula y correo'+cedu+' '+correo});

				Swal.fire({
						title: '¿Desea Cambiar su correo?',
						icon: 'question',
						showCancelButton: true,
						confirmButtonColor: '#3085d6',
						cancelButtonColor: '#d33',
						confirmButtonText: 'Si',
						cancelButtonText: 'No'
					}).then((result) => {
						if (result.isConfirmed) {


							$.ajax({
									type: 'POST',
									url: '../Operaciones/cambio_correo.php',
									data: {
										'cedula':cedu, 'correo':correo
									},
									beforeSend: function(){
										Swal.fire(
											'¡Procesando...',
											'Su correo esta siendo cambiado.',
											'info'
										)

									}
								})
							.done(function(datos){

									Swal.fire(
										'¡Cambios Completados!',
										'Su correo a sido cambiado.',
										'success'
									)
								})
							.fail(function(){

									Toast.fire({icon: 'error',title:'Hubo un error Procesando solicitud...'});

								})

						}
					})

			}
			function cambiarusuario(){
				var cedu = $("#cedula_trab").val();
				var user =$("#txtusuario").val();
				//Toast.fire({icon: 'info',title:'Usuario: '+user+ ' Cedula: '+cedu});


				Swal.fire({
						title: '¿Desea Cambiar su usuario?',
						icon: 'question',
						showCancelButton: true,
						confirmButtonColor: '#3085d6',
						cancelButtonColor: '#d33',
						confirmButtonText: 'Si',
						cancelButtonText: 'No'
					}).then((result) => {
						if (result.isConfirmed) {


							$.ajax({
									type: 'POST',
									url: '../Operaciones/cambiausuario.php',
									data: {
										'cedula':cedu, 'usuario':user
									},
									beforeSend: function(){
										Swal.fire(
											'¡Procesando...',
											'Su usuario esta siendo cambiado.',
											'info'
										)

									}
								})
							.done(function(datos){
									if (datos== 'encontrado'){
										Swal.fire(
											'¡No se Puede cambiar su Usuario!',
											'El usuario esta en uso, escriba otro.',
											'warning'
										)

									}else{
										Swal.fire(
											'¡Cambios Completados!',
											'Su usuario a sido cambiado. El cambio sera efectivo al volver a entrar',
											'success'
										)
									}

								})
							.fail(function(){

									Toast.fire({icon: 'error',title:'Hubo un error Procesando solicitud...'});

								})
						}
					})

			}
			function cambioclave(){
				var clave1 = document.getElementById("txtclave1").value;
				var clave2 = document.getElementById("txtclave2").value;
				var cedu = $("#cedula_trab").val();
				Swal.fire({
						title: '¿Desea Cambiar su clave?',
						icon: 'question',
						showCancelButton: true,
						confirmButtonColor: '#3085d6',
						cancelButtonColor: '#d33',
						confirmButtonText: 'Si',
						cancelButtonText: 'No'
					}).then((result) => {
						if (result.isConfirmed) {
							$.ajax({
									type: 'POST',
									url: '../Operaciones/cambioclave.php',
									data: {
										'cedula':cedu, 'clave1':clave1, 'clave2': clave2
									},
									beforeSend: function(){
										Swal.fire(
											'¡Procesando...',
											'Su contraseña esta siendo cambiada.',
											'info'
										)

									}
								})
							.done(function(datos){
									if (datos== 'no encontrada'){
										Swal.fire(
											'¡Contraseña antigua errada!',
											'la contraseña que escribio esta errada, escriba su contraseña antigua.',
											'warning'
										)

									}else{
										Swal.fire(
											'¡Cambios Completados!',
											'Su contraseña a sido cambiada. El cambio sera efectivo al volver a loguearse',
											'success'
										)

									}

								})
							.fail(function(){

									Toast.fire({icon: 'error',title:'Hubo un error Procesando solicitud...'});

								})
						}
					})

			}

			function cambioresp(){
				var comb = document.getElementById("combopre").value;
				var resp = document.getElementById("txtrespuesta").value;
				var cedu = $("#cedula_trab").val();

				Swal.fire({
						title: '¿Desea Cambiar su pregunta o respuesta?',
						icon: 'question',
						showCancelButton: true,
						confirmButtonColor: '#3085d6',
						cancelButtonColor: '#d33',
						confirmButtonText: 'Si',
						cancelButtonText: 'No'
					}).then((result) => {
						if (result.isConfirmed) {
							$.ajax({
									type: 'POST',
									url: '../Operaciones/cambioresp.php',
									data: {
										'cedula':cedu, 'pregunta':comb, 'respuesta': resp
									},
									beforeSend: function(){
										Swal.fire(
											'¡Procesando...',
											'Su datos estan siendo cambiados.',
											'info'
										)

									}
								})
							.done(function(datos){

									Swal.fire(
										'¡Cambios Completados!',
										'Su Pregunta o Respuesta han sido cambiados. Los cambios seran efectivos al volver a loguearse',
										'success'
									)

								})
							.fail(function(){

									Toast.fire({icon: 'error',title:'Hubo un error Procesando solicitud...'});

								})
						}
					})
			}
			function cambiofoto(){
				var formData = new FormData();
				var cedu = $("#cedula_trab").val();
				var files = $('#buscarfoto')[0].files[0];
				formData.append('file',files);
				formData.append('cedula',cedu);

				$.ajax({
						type: 'POST',
						url: '../Operaciones/mostrarimagen.php',
						data: formData,
						contentType: false,
						processData: false,
						Cache:false,

						beforeSend: function(){
							Toast.fire({icon: 'info',title:'Su imagen esta siendo subida al servidor por favor espere...'});
							$('#loaderfoto').show();
							$("#buscarfoto").prop('disabled',true);
							$("#bt_borrarimagen").prop('disabled',true);
						}
					})
				.done(function(datos){
						var img=datos+'?'+Date.now()//para poder refrescar la imagen al cambiarla se puso ? +datenow()
						$('#loaderfoto').hide();
						$("#fotoperfil1").attr("src", "");
						$("#fotoperfil2").attr("src", "");
						$("#fotoperfil3").attr("src", "");
						$("#buscarfoto").prop('disabled',false);
						$("#bt_borrarimagen").prop('disabled',false);
						$("#fotoperfil1").attr("src", img);
						$("#fotoperfil2").attr("src", img);
						$("#fotoperfil3").attr("src", img);

					})
				.fail(function(){

						Toast.fire({icon: 'error',title:'Hubo un error Procesando solicitud...'});
						$("#buscarfoto").prop('disabled',false);
						$("#bt_borrarimagen").prop('disabled',false);
					})

			}

			function borrarfoto(){
				var cedu = $("#cedula_trab").val();
				Swal.fire({
						title: '¿Desea borrar su Imagen?',
						icon: 'question',
						showCancelButton: true,
						confirmButtonColor: '#3085d6',
						cancelButtonColor: '#d33',
						confirmButtonText: 'Si',
						cancelButtonText: 'No'
					}).then((result) => {
						if (result.isConfirmed) {

							$.ajax({
									type: 'POST',
									url: '../Operaciones/borrarfoto.php',
									data: {
										'cedula':cedu
									},
									beforeSend: function(){
										Swal.fire(
											'¡Procesando...',
											'Su foto esta siendo borrada.',
											'info'
										)
									}
								})
							.done(function(datos){

									Swal.fire(
										'¡Cambios Completados!',
										'Su Imagen se ha borrado exitosamente',
										'success'
									)
									$("#fotoperfil1").attr("src", "../Recursos/user-png-icon-15.jpg");
									$("#fotoperfil2").attr("src", "../Recursos/user-png-icon-15.jpg");
									$("#fotoperfil3").attr("src", "../Recursos/user-png-icon-15.jpg");
								})
							.fail(function(){

									Toast.fire({icon: 'error',title:'Hubo un error Procesando solicitud...'});

								})
						}

					})
			}
			function mostrardatosper(){
				var cedu = $("#cedula_trab").val();
				datos = {
					"cedula":cedu
				};


				$.ajax({
						url: "../Operaciones/muestrapersonal.php",
						type: "POST",
						data: datos
					}).done(function(retorno){
						var nom = retorno.nombres;
						var ape = retorno.apellidos;
						var sex = retorno.sexo;
						var nacio = retorno.nacionalidad;
						var dire = retorno.direccion;

						if (sex === 'M') {
							sex  = 'Masculino';
						} else {
							sex = 'Femenino';
						}

     					var fnac = retorno.fechan;
						var lnac = retorno.lnac;
						var telef = retorno.telefonofijo;
						var telem = retorno.telefonomovil;
						var tele;
						
						if(telef != '' && telem != ''){
							tele = telef+', '+telem;
						}else{
							tele = telef+telem;
						}
						
						
						document.getElementById('EMPLEADO').innerHTML =nacio+cedu+ ', '+nom+' '+ape ;
						document.getElementById('sexo').innerHTML = sex ;
						document.getElementById('fechan').innerHTML = fnac ;
						document.getElementById('lugarn').innerHTML = lnac ;
						document.getElementById('dire').innerHTML = dire ;
						document.getElementById('tele').innerHTML = tele ;

					}).fail(function(){

						Toast.fire({icon: 'error',title:'Hubo un error Procesando solicitud...'});

					});



			}
		</script>

	</body>



</html>