<?php header('Content-Type: text/html;charset=ISO-8859-1'); ?><!--Para poner los acentos y ñ-->
<!DOCTYPE html>
<html>
	<head>
		<meta charset="ISO-8859-1">
		<title>
			Dirección de Personal
		</title>
		<meta name="description" content="Pagina de la Direccion de Recursos humanos de la Gobernacion de Anzoategui">
		<meta name="keywords" content="gobernacion del estado anzoategui, direccion de personal, gobernacion,anzoategui, direccion, personal, recibos, constancias, recibos de pago, constancia de trabajo, gobierno, empleados, docentes, obreros, jubilados, pensionados, trabajadores, personalanzoategui.org.ve, personalanzoategui">
		<meta name="author" content="Jose Meneses"/>
		<link href="Recursos/log.ico" rel="shortcut icon" type="image/x-icon"/>
		<link rel="stylesheet" type="text/css" href="Estilos/index.css">
		<link href="Bootstraps/css/bootstrap.css" rel="stylesheet" id="bootstrap-css" >
		<link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">

	</head>
	<body>

		<div   class="container">
			<div  class="row justify-content-center <!--h-100-->">

				<div class="col-md-offset-4 col-md-4 text-center">
					<h1 class='text-white'>
						Gobernacíon del Estado Anzoátegui
					</h1>
					<div class="form-login">
						</br>
						<h4 class='text-white'>
							Dirección de Personal
						</h4>
						</br>
						<form method="POST" id="login_form">
							<div class="form-group">
								<div class="input-group mb-2">
									<div class="input-group-prepend">
										<div class="input-group-text">
											<i class="fa fa-user text-dark">
											</i>
										</div>
									</div>
									<input type="text" id="userName" class="form-control input-sm chat-input" placeholder="Usuario"/>
								</div>
							</div>
							</br></br>
							<div class="form-group">
								<div class="input-group mb-2">
									<div class="input-group-prepend">
										<div class="input-group-text">
											<i class="fa fa-key text-dark">
											</i>
										</div>
									</div>
									<input type="password" id="userPassword" class="form-control input-sm chat-input" placeholder="Contraseña" />
								</div>
							</div>
							</br>
							<div class="wrapper">
								<span class="group-btn">
									<button
										<a href="#" type="submit" id="Bt_ingresar" class="boton botonxdefecto btn-md">
											<i class="fa fa-sign-in">
											</i> Ingreso
										</a>
									</button>
									<button
										<a href="#" type="reset" class="boton botoncancelar btn-md">
											<i class="fa fa-times">
											</i> Cancelar
										</a>
									</button>

								</span>

							</div>
							</br>
							<div class="wrapper">
								<div
									id="olvido"
									<span
										">
										<a href="Formularios/recuperarpass.php" style="color:#C3C3C3;">
										<strong>&#191 Olvido sus Datos de Ingreso ?</strong>
										</a>
									</span>
								</div>
								<div
									id="reg"
									<span>
										<a href="Formularios/registro.php" style="color:#C3C3C3;">
											<strong>Registro</strong>
										</a>
									</span>
								</div>
							</div>
						</form>
					</div>
				</div>

			</div>

			</br></br>
			<div class="row justify-content-center">

				<img src="Recursos/postgres.png" class="rounded" alt="" width="80" height="80">
				<img src="Recursos/php.png" class="rounded" alt="" width="100" height="100">
				<img src="Recursos/debian.png" class="rounded" alt="" width="100" height="100">
				<img src="Recursos/javascript-html5-css3.png" class="rounded" alt="" width="100" height="100">
				<img src="Recursos/pngegg.png" class="rounded" alt="" width="120" height="100">
			</div>

			</br></br>
			<!--footer-->
			<div class="footer text-secondary text-center">
				<p>
					<?php
					$date = date("Y");
					echo "&copy; $date. Todos Los Derechos Reservados. Gobernación del Estado Anzoátegui. Dirección de Personal-Dept. de Procesamiento de Datos.";
					?>
				</p>

			</div>
			<!--//footer-->
		</div>
		<script src="js/jquery.min.js">
		</script>
		<script src="js/jquery.redirect.min.js">
		</script>
		<script src="js/jquery-ui.min.js">
		</script>
		<script src="js/popper.min.js">
		</script>
		<script src="Bootstraps/js/bootstrap.min.js">
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
			$("#login_form").submit(function(e){
					e.preventDefault();
					var user = $("#userName").val();
					var pass = $("#userPassword").val();
					if($.trim(user) == ""){
						Toast.fire({
								icon: 'warning',
								title: 'No ha Ingresado un Usuario'
							});

						$("#userName").focus();
						return false;
					}else if($.trim(pass) == ""){
						Toast.fire({
								icon: 'warning',title: 'No ha Ingresado su Contraseña'
							})

						$("#userPassword").focus();
						return false;
					}else{
						$.ajax({
								url:'Operaciones/autenticacion_login.php',
								type:'POST',
								data: {
									username:$("#userName").val(), password:$("#userPassword").val()
								},
								beforeSend: function(){
									Toast.fire({
											icon: 'info',
											title: 'Procesando la Información Por favor espere'
										});
								},
								success: function(resp) {
									if(resp == "invalido") {
										Toast.fire({icon: 'error',title: 'Usuario o Contraseña errados Por favor verifique...'});
									} else {
										Toast.fire({icon: 'success',title: 'Bienvenido al Sistema'});
										window.location.href= resp;

									}
								}
							});
					}


				});
		</script>


	</body>
</html>