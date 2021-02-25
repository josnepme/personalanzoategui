<?php header('Content-Type: text/html;charset=ISO-8859-1'); ?><!--Para poner los acentos y ñ-->

<!DOCTYPE html>
<html>
	<head>
		<meta charset="ISO-8859-1">
		<meta name="description" content="Pagina de la Direccion de Recursos humanos de la Gobernacion de Anzoategui">
		<meta name="keywords" content="gobernacion del estado anzoategui, direccion de personal, gobernacion,anzoategui, direccion, personal, recibos, constancias, recibos de pago, constancia de trabajo, gobierno, empleados, docentes, obreros, jubilados, pensionados, trabajadores, personalanzoategui.org.ve, personalanzoategui">
		<meta name="author" content="Jose Meneses"/>
		<link href="../Recursos/log.ico" rel="shortcut icon" type="image/x-icon"/>
		<link rel="stylesheet" type="text/css" href="../Estilos/registro.css">
		<link href="../Bootstraps/css/bootstrap.css" rel="stylesheet" id="bootstrap-css" >
		<link rel="stylesheet" href="../font-awesome/css/font-awesome.min.css">
        <link href="//cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">
		<title>
			Registro
		</title>
	</head>
	<body>
		<div  class="container-sm-md-lg ">
			<br></br>
			<div  class="row justify-content-center">
				<div class=" col-md-4 text-center">
					<h1 class='text-white'>
						Dirección de Personal
					</h1>

					<div class="form-login" >
						<br>
						<h4 class='text-white'>
							Registro
						</h4>
						<br>
						<div class="input-group  justify-content-center text-center " >
							<div class="input-group-prepend">
								<div class="input-group-text">
									<i class="fa fa-address-card-o text-dark ">
									</i>
								</div>
								<input  id="cedula" name="cedula" type="text"  placeholder="Cedula"   class="form-control input-md  input-sm text-center"style="font-family: verdana;" data-toggle="tooltip" data-placement="top" title="Ingrese la Cedula a verificar">
						</div>
						<button
								id="bt_consulta" name="bt_consulta" type="submit"
								<a href="#" class="boton botonxdefecto btn-md">
									<i class="fa fa-search">
									</i> Verificar
								</a>
							</button>
						
						</div>
						
						
						</br>
						<h5 class='text-left text-white-50'>
							Empleado:
							<strong class="text-white">
								<div id="empleados">
								</div>
							</strong>
						</h5>

						</br>

						<form action="" method="post" name="login_registro" id="login_registro"  >
							<div class="form-group ">
								<div class="input-group mb-2  justify-content-center">
									<div class="input-group-prepend">
										<div class="input-group-text">
											<i class="fa fa-user text-dark">
											</i>
										</div>
									</div>
									<input id="usuario" name="usuario" type="text" placeholder="Usuario" class="form-control col-md-8" >
								</div>
							</div>
							<br>
							<div class="form-group">
								<div class="input-group mb-2 justify-content-center">
									<div class="input-group-prepend">
										<div class="input-group-text">
											<i class="fa fa-key text-dark">
											</i>
										</div>
									</div>
									<input
										id="clave" name="clave" type="password" placeholder="Contraseña" class="form-control col-md-8"
									</input>
									
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
										id="reclave" name="reclave" type="password" placeholder="Confirme la Contraseña" class="form-control col-md-8"
									</input>
								</div>
							</div>
							<br>
							<div class="form-group">
								<div class="input-group mb-2 justify-content-center">
									<div class="input-group-prepend">
										<div class="input-group-text">
											<i class="fa fa-question text-dark">
											</i>

										</div>
									</div>
									<select id="combo" name="combo" class="form-control form-control-md-8 form-control-sm-12 form-control-lg-8 col-8" aria-label="select preguntas" >
										<option value="" selected disabled>
											-- Preguntas de Seguridad --
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

								</div>
							</div>
							</br>
							<div class="form-group">
								<div class="input-group mb-2 justify-content-center">
									<div class="input-group-prepend">
										<div class="input-group-text">
											<i class="fa fa-reply text-dark">
											</i>
										</div>
									</div>

									<input
										id="respuestas" name="respuestas" type="text" placeholder="Ingrese su respuesta" class="form-control col-md-8"
									</input>
								</div>
							</div>
							</br>
							<div class="form-group">
								<div class="input-group mb-2  justify-content-center">
									<div class="input-group-prepend">
										<div class="input-group-text">
											<i class="fa fa-envelope text-dark">
											</i>
										</div>
									</div>
									<input type="email" class="form-control col-md-8" id="email" name="email" placeholder="Correo Electronico" >
								</div>
							</div>
							<br/>
							<div class="wrapper">
								<span class="group-btn">
									<button
										<a href="#" type="submit" class="boton botonxdefecto btn-md" id="bt_registrar" name="bt_registrar" >
											<i class="fa fa-check-circle">
											</i> Registrar
										</a>
									</button>
									<button
										<a  type="button" class="boton botonxdefecto btn-md" onClick="window.location.href='../index.php';">
											<i class="fa fa-arrow-left">
											</i> Regresar
										</a>
									</button>

									<input id = "cedula_trab" name="cedula_trab" type="hidden" value=""/>
									<input id = "nombre_trab" name="nombre_trab" type="hidden" value=""/>
									<input id = "apellido_trab" name="apellido_trab" type="hidden" value=""/>
								</span>

							</div>

						</form>

					</div>

				</div>

			</div>
			<br>
			<div class="footer text-secondary text-center">
				<p>
					<?php
					$date = date("Y");
					echo "&copy; $date. Todos Los Derechos Reservados. Gobernacíon del Estado Anzoátegui. Direccion de Personal-Dept. de Procesamiento de Datos.";


					?>
				</p>

			</div>
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
			
			$("#usuario").prop("disabled", true);
			$("#clave").prop("disabled", true);
			$("#reclave").prop("disabled", true);
			$("#email").prop("disabled", true);
			$("#combo").prop("disabled", true);
			$("#respuestas").prop("disabled", true);
			$("#bt_registrar").prop("disabled", true);

			$('[data-toggle="tooltip"]').tooltip(); //para los tooltips


			jQuery(document).ready(function(){
					// Listen for the input event.
					jQuery("#cedula").on('input', function (evt) {
							// Allow only numbers.
							jQuery(this).val(jQuery(this).val().replace(/[^0-9]/g, ''));
						});
				});


			$("#cedula").focus();



		</script>
		
		<script type="text/javascript">

			$("#bt_consulta").click(function(e) {

					e.preventDefault();
					var cedu = $("#cedula").val();
					if($.trim(cedu) == ""){

						Toast.fire({icon: 'warning',title: 'Tiene que ingresar su cedula de identidad'});
						$("#cedula").focus();
						return false;
						$("#empleados").empty();
						$("#cedula_trab").attr("value", "");
						$("#nombre_trab").attr("value", "");
						$("#apellido_trab").attr("value", "");

					}else{
						//"nombre del parámetro POST":valor (el cual es el objeto guardado en las variables de arriba)
						datos = {
							"cedula":cedu
						};

						$.ajax({
								url: "../Operaciones/verifica_registro.php",
								type: "POST",
								data: datos
							}).done(function(retorno){
								if (retorno.estado === "ok") {

									var ced = retorno.cedula;
									var nom = retorno.nombres;
									var ape = retorno.apellidos;
									var empleado = ced+", "+nom+" "+ape;
									Toast.fire({icon: 'info',title: 'Puede Proceder a Registrarse'});
									$("#empleados").empty();
									$("#empleados").append(empleado);
									$("#cedula_trab").attr("value", "");
									$("#nombre_trab").attr("value", "");
									$("#apellido_trab").attr("value", "");

									$("#cedula_trab").attr("value", ced);
									$("#nombre_trab").attr("value", nom);
									$("#apellido_trab").attr("value", ape);

									$("#usuario").prop("disabled", false);
									$("#clave").prop("disabled", false);
									$("#reclave").prop("disabled", false);
									$("#email").prop("disabled", false);
									$("#combo").prop("disabled", false);
									$("#respuestas").prop("disabled", false);
									$("#bt_registrar").prop("disabled", false);


									$('#login_registro').get(0).reset();//blanqear formulario

								}else{
									Toast.fire({icon: 'error',title: 'No pertenece a la nomina de la Gobernacíon de Anzoátegui'});
									$("#empleados").empty();
									$("#cedula_trab").attr("value", "");
									$("#nombre_trab").attr("value", "");
									$("#apellido_trab").attr("value", "");
									$('#login_registro').get(0).reset();//blanqear formulario

									$("#usuario").prop("disabled", true);
									$("#clave").prop("disabled", true);
									$("#reclave").prop("disabled", true);
									$("#email").prop("disabled", true);
									$("#combo").prop("disabled", true);
									$("#respuestas").prop("disabled", true);
									$("#bt_registrar").prop("disabled", true);



								}
							});
					}
				});
		</script>
		<script type="text/javascript">
			$("#login_registro").submit(function(e){
					e.preventDefault();

					var user = document.getElementById("usuario").value;
					var pass = document.getElementById("clave").value;
					var repass = document.getElementById("reclave").value;
					var correo = document.getElementById("email").value;
					var combo = document.getElementById("combo").value;
					var respuestas = document.getElementById("respuestas").value;

					if($.trim(user) == ""){
						Toast.fire({icon: 'warning',title: 'No ha Ingresado el Usuario'});
						$("#usuario").focus();
						return false;
					}else{
						if($.trim(pass) == ""){
							Toast.fire({icon: 'warning',title: 'No ha ingresado la Contraseña'});
							$("#clave").focus();
							return false;
						}else{
							if($.trim(repass) == ""){
								Toast.fire({icon: 'warning',title: 'No ha repetido su contraseña'});
								$("#reclave").focus();
								return false;
							}else{
								if($.trim(combo) == ""){
									Toast.fire({icon: 'warning',title: 'Tiene que seleccionar una pregunta'});
									$("#combo").focus();
									return false;
								}else{
									if($.trim(respuestas) == ""){
										Toast.fire({icon: 'warning',title: 'No ingresado una respuesta'});
										$("#respuestas").focus();
										return false;
									}else{
										if($.trim(correo) == ""){
											Toast.fire({icon: 'warning',title: 'No ha ingresado el correo'});
											$("#email").focus();
											return false;
										}else{
											var expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
											if ( !expr.test(correo) ){
												Toast.fire({icon: 'warning',title: 'La direccíon de correo es incorrecta, ejemplo: alguien@dominio.com'});
												
												$("#email").focus();
												return false;
											}else{
												if($("#clave").val() !== $("#reclave").val()){
													Toast.fire({icon: 'warning',title: 'Las contraseñas no son iguales'});
													return false;
												}else{

													registrar();
												}
											}
										}

									}
								}
							}
						}
					}
				});
		</script>
		<script type = "text/javascript">

			function registrar(){

				var cedula = document.getElementById("cedula_trab").value;
				var user = document.getElementById("usuario").value;
				var pass = document.getElementById("clave").value;
				var nomb = document.getElementById("nombre_trab").value;
				var apel = document.getElementById("apellido_trab").value;
				var correo = document.getElementById("email").value;
				var combo = document.getElementById("combo").value;
				
				var respuestas = document.getElementById("respuestas").value;
					
				datos = {
					"cedula":cedula,"user":user, "password":pass, "nombre":nomb,"apellido":apel, "correo":correo, "combo":combo, "respuestas":respuestas
				};

				$.ajax({
						url:'../Operaciones/registrar.php',
						type:'POST',
						data: datos,
						 beforeSend: function () {

						Toast.fire({icon: 'info',title: 'Procesando...'});
						},
						success: function(resp) {
							if(resp=='insertado'){
								Toast.fire({icon: 'success',title: 'Registrado Satisfactoriamente será redirigido para que pueda iniciar sesion...'});
								setTimeout('window.location.href= "../index.php"',3000);
							}
							if(resp=='encontrado'){
								Toast.fire({icon: 'warning',title: 'Usted ya esta registrado o el usuario esta en uso...'});
								
								$("#usuario").focus();
								return false;
							}

						}
					});
			}
		</script>

	</body>
</html>