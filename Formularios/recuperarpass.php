<?php header('Content-Type: text/html;charset=ISO-8859-1'); ?><!--Para poner los acentos y ñ-->

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>
			Recuperar Contraseña
		</title>
		<meta charset="ISO-8859-1">
		<meta name="description" content="Pagina de la Direccion de Recursos humanos de la Gobernacion de Anzoategui">
		<meta name="keywords" content="gobernacion del estado anzoategui, direccion de personal, gobernacion,anzoategui, direccion, personal, recibos, constancias, recibos de pago, constancia de trabajo, gobierno, empleados, docentes, obreros, jubilados, pensionados, trabajadores, personalanzoategui.org.ve, personalanzoategui">
		<meta name="author" content="Jose Meneses"/>
		<link href="../Recursos/log.ico" rel="shortcut icon" type="image/x-icon"/>
		<link rel="stylesheet" type="text/css" href="../Estilos/registro.css">
		<link href="../Bootstraps/css/bootstrap.css" rel="stylesheet" id="bootstrap-css" >
		<link rel="stylesheet" href="../font-awesome/css/font-awesome.min.css">

	</head>
	<body>
		<div  class="container-sm-md-lg ">
			<br/><br/>
			<div class="row justify-content-center">
				<div class=" col-md-4 text-center">
					<h1 class='text-white'>
						Dirección de Personal
					</h1>

					<div class="form-login" >
						<br>
						<h4 class='text-white'>
							Recuperar Contraseña
						</h4>
						<br>

						<div class="input-group  justify-content-center text-center " >
							<div class="input-group-prepend">
								<div class="input-group-text">
									<i class="fa fa-address-card-o text-dark ">
									</i>
								</div>
								<input  id="cedula" name="cedula" type="text"  placeholder="Cedula"   class="form-control input-md  input-sm text-center chat-input"style="font-family: verdana;" data-toggle="tooltip" data-placement="top" title="Ingrese la Cedula a verificar">
							</div>
							
							<button
								id="bt_consulta" name="bt_consulta" type="submit"
								<a href="#" class="boton botonxdefecto btn-md">
									<i class="fa fa-search">
									</i> Verificar
								</a>
							</button>
						</div>
						<form action="" method="post" name="login_recuperar" id="login_recuperar"  >
							<div class="form-group ">
								<div class="justify-content-center">
									</br>
									<h5 class='text-left text-white'>
										Pregunta:
										<strong class="text-info">
											<div id="pregunta">
											</div>
										</strong>
									</h5>
								</div>
							</div>
							</br>
							<div class="form-group">
								<div class="input-group mb-2 justify-content-center">
									<div class="input-group-prepend">
										<div class="input-group-text">
											<i class="fa fa-key text-dark">
											</i>
										</div>
									</div>
									<input
										id="respuestas" name="respuestas" type="text" placeholder="Respuesta" class="form-control col-md-8"
									</input>

								</div>
							</div>
							<br>

							<br/>
							<div class="wrapper">
								<span class="group-btn">
									<button
										<a href="#" type="submit" class="boton botonxdefecto btn-md" id="bt_enviar" name="bt_enviar" >
											<i class="fa fa-check-circle">
											</i> Enviar
										</a>
									</button>
									<button
										<a  type="button" class="boton botonxdefecto btn-md" onClick="window.location.href='../index.php';">
											<i class="fa fa-arrow-left">
											</i> Regresar
										</a>
									</button>
								</span>

							</div>

						</form>

					</div>

				</div>

			</div>
			<input id = "cedula_trab" name="cedula_trab" type="hidden" value=""/>

		</div>
		<script src="../js/jquery.min.js">
		</script>
		<script src="../js/jquery.redirect.min.js">
		</script>
		<script src="../js/jquery-ui.min.js">
		</script>
		<script src="../js/popper.min.js">
		</script>
		<script src="../js/jquery.redirect.min.js">
		</script>
		<script src="../js/jquery-ui.min.js">
		</script>
		<script src="../Bootstraps/js/bootstrap.min.js">
		</script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10">
		</script>

		
		<script type="text/javascript">
			window.addEventListener("load", function() {

					$("#respuestas").prop("disabled", true);
					$("#bt_renviar").prop("disabled", true);

					$('[data-toggle="tooltip"]').tooltip(); //para los tooltips


					$("#cedula").focus();

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
		<script type="text/javascript">

			$("#bt_consulta").click(function(e) {

					e.preventDefault();
					var cedu = $("#cedula").val();
					if($.trim(cedu) == ""){
					Toast.fire({icon: 'warning',title: 'Tiene que ingresar su cedula de identidad'});
						
						$("#cedula").focus();
						return false;
						$("#pregunta").empty();
						$("#cedula_trab").attr("value", "");


					}else{

						datos = {
							"cedula":cedu
						};

						$.ajax({
								url: "../Operaciones/verifica_usuario.php",
								type: "POST",
								data: datos
							}).done(function(retorno){
								if (retorno.estado === "ok") {

									var pre = retorno.pregunta;
									var cedul = retorno.cedula;

									$('#login_recuperar').get(0).reset();//blanqear formulario
									$("#pregunta").empty();
									$("#pregunta").append("¿"+pre+"?");
									$("#cedula_trab").attr("value", "");
									$("#cedula_trab").attr("value", cedul);

									$("#pregunta").prop("disabled", false);
									$("#bt_enviar").prop("disabled", false);
									$("#respuestas").prop("disabled", false);



								}else{
									Toast.fire({icon: 'warning',title: 'No es empleado de la Gobernacíon de Anzoátegui'});

									$("#pregunta").empty();

									$('#login_recuperar').get(0).reset();//blanqear formulario
									$("#cedula_trab").attr("value", "");
									$("#pregunta").prop("disabled", true);
									$("#bt_enviar").prop("disabled", true);
									$("#respuestas").prop("disabled", true);

								}
							});
					}
				});
		</script>
		<script type="text/javascript">
			$("#login_recuperar").submit(function(e){
					e.preventDefault();
					var cedula = document.getElementById("cedula_trab").value;
					var respuestas = document.getElementById("respuestas").value;
					datos = {
						"cedula":cedula, "respuestas":respuestas
					};
					$.ajax({
							url: "../Operaciones/enviarmail.php",
							type: "POST",
							data: datos,
							beforeSend: function () {
							Toast.fire({icon: 'info',title: 'Procesando Solicitud...'});	
                        
                            $("#bt_enviar").prop("disabled", true);
                            $("#pregunta").prop("disabled", true);
                           }
						}).done(function(retorno){
							if (retorno.estado === "ok") {

								var ced = retorno.cedula;
								var nom = retorno.nombres;
								var ape = retorno.apellidos;
								Toast.fire({icon: 'success',title: 'Se envio su solicitud para recuperar sus datos de inicio de sesion, en breve le responderemos'});
				
								setTimeout('window.location.href= "../index.php"',3000);
								$("#pregunta").empty();
                                $("#bt_enviar").prop("disabled", false);
                                $("#pregunta").prop("disabled", false);
								$('#login_recuperar').get(0).reset();//blanqear formulario

							}else{
								Toast.fire({icon: 'warning',title: 'Su respuesta de seguridad no es correcta intente de nuevo'});
								
							}
						});

			});
		</script>


	</body>
</html>