<?php 
session_start();

if(getDataSession("email")){

	$status = json_decode(checkear_si_tiene_curso(getDataSession("email"),get_the_ID()), true);

	if($status[0]['comprado']){

		if($status[0]['estado'] != "activo"){
			redirect(5,'login');
			die();
		}

	}

} else {

	$status = array();

}

// verificarSesion();

get_header();

function estadoUnidad($unidad,$id_user,$id_curso){

	$estado = array();

	global $wpdb;

	$tableName = $wpdb->prefix . "estado_curso_respuestas";
	$consulta = $wpdb->get_results(" SELECT * FROM $tableName WHERE `id_user` = $id_user AND `id_curso` = $id_curso AND `unidad` = $unidad ");

	if(count($consulta) > 0){

		foreach($consulta as $res){

			if($res->intento == 1 && $res->estado === "correcta" || $res->intento == 2 && $res->estado === "correcta"){

				return true;

			} elseif ($res->intento == 2 && $res->estado === "incorrecta"){

				return true;

			} elseif ($res->intento == 1 && $res->estado === "incorrecta"){

				return false;

			} else {

				return false;

			}

		}
	
		// if (in_array("incorrecta", $estado)) {

		// 	return false;

		// } else{

		// 	return true;

		// }

	} else {

		return false;

	}

}

function estadoPregunta($unidad,$id_user,$id_curso,$nPreg){

	global $wpdb;

	$tableName = $wpdb->prefix . "estado_curso_respuestas";
	$consulta = $wpdb->get_results(" SELECT * FROM $tableName WHERE `id_user` = $id_user AND `id_curso` = $id_curso AND `unidad` = $unidad AND `n_preg` = $nPreg ");

	if(count($consulta) > 0){

		foreach($consulta as $res){

			if($res->estado === "correcta"){
				return "ok";
			} elseif ($res->estado === "incorrecta" && $res->intento = 2){
				return "ok";
			} else {
				return false;
			}

		}

	}

}

function obtenerRespuestaCorrecta($unidad,$id_user,$id_curso,$nPreg){

	global $wpdb;

	$tableName = $wpdb->prefix . "estado_curso_respuestas";
	$consulta = $wpdb->get_results(" SELECT * FROM $tableName WHERE `id_user` = $id_user AND `id_curso` = $id_curso AND `n_preg` = $nPreg AND `estado` = 'correcta' AND `unidad` = $unidad ");

	if(count($consulta) > 0){

		foreach($consulta as $res){

			return $res->respuesta;

		}

	}

}

function obtenerRespuestaIncorrecta($unidad,$id_user,$id_curso,$nPreg){

	global $wpdb;

	$tableName = $wpdb->prefix . "estado_curso_respuestas";
	$consulta = $wpdb->get_results(" SELECT * FROM $tableName WHERE `id_user` = $id_user AND `id_curso` = $id_curso AND `n_preg` = $nPreg AND `estado` = 'incorrecta' AND `intento` = 2 AND `unidad` = $unidad ");

	if(count($consulta) > 0){

		foreach($consulta as $res){

			return $res->respuesta;

		}

	}

}

?>

<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/bootstrap.min.css" crossorigin="anonymous">

<style>
iframe{
	width: 100%;
    height: 711px;
}
</style>

<!-- Dashboard -->
<div id="dashboard">

	<!-- Navigation
	================================================== -->

	<!-- Responsive Navigation Trigger -->
	<a href="#" class="dashboard-responsive-nav-trigger"><i class="fa fa-reorder"></i> Dashboard Navigation</a>

	<div class="dashboard-nav">
		<div class="dashboard-nav-inner">

            <!--<ul data-submenu-title="FIPI">
				<li class="active"><a href="<?php echo home_url(); ?>/cursos-presenciales"><i class="sl sl-icon-user"></i> Cursos Presenciales</a></li>
				<li><a href="<?php echo home_url(); ?>/cursos-online"><i class="sl sl-icon-cursor"></i> Cursos Online</a></li>
				<li><a href="dashboard-wallet.html"><i class="sl sl-icon-wallet"></i> Wallet</a></li>
			</ul>
			
			<ul data-submenu-title="Listings">
				<li><a><i class="sl sl-icon-layers"></i> My Listings</a>
					<ul>
						<li><a href="dashboard-my-listings.html">Active <span class="nav-tag green">6</span></a></li>
						<li><a href="dashboard-my-listings.html">Pending <span class="nav-tag yellow">1</span></a></li>
						<li><a href="dashboard-my-listings.html">Expired <span class="nav-tag red">2</span></a></li>
					</ul>	
				</li>
				<li><a href="dashboard-reviews.html"><i class="sl sl-icon-star"></i> Reviews</a></li>
				<li><a href="dashboard-bookmarks.html"><i class="sl sl-icon-heart"></i> Bookmarks</a></li>
				<li><a href="dashboard-add-listing.html"><i class="sl sl-icon-plus"></i> Add Listing</a></li>
			</ul>	

			<ul data-submenu-title="Account">
				<li><a href="dashboard-my-profile.html"><i class="sl sl-icon-user"></i> My Profile</a></li>
				<li><a href="index.html"><i class="sl sl-icon-power"></i> Logout</a></li>
			</ul> -->
			
		</div>
	</div>
	<!-- Navigation / End -->


	<!-- Content
	================================================== -->
	<div class="dashboard-content">

		<!-- Titlebar -->
		<div id="titlebar">
			<div class="row">
				<div class="col-md-12">
					<?php 
					if(isset($status[0]['comprado'])){

						echo '<h2>Bienvenido al Curso '.get_the_title().'</h2>';

					} else {

						echo '<h2>'.get_the_title().'</h2>';

					}
					?>
				</div>
			</div>
		</div>

		<div class="row">
			
			<div class="col-lg-6 col-md-12">
				<div class="dashboard-list-box with-icons margin-top-20">
					<!-- <img src="<?php echo get_post_meta( get_the_ID(), 'curso_presencial_imagen_grande', true ); ?>" alt=""> -->
					<div class="embed-responsive embed-responsive-16by9">
						<?php echo get_post_meta( get_the_ID(), 'curso_presencial_intro_video', true ); ?>
					</div>
				</div>

				<?php 				
				if(isset($status[0]['comprado'])){
				?>
				<!-- reseñas --> 
				<div class="dashboard-list-box invoices margin-top-20">
					<h4>Reseñas del curso <i class="im im-icon-File-HorizontalText" style="position: relative; top: 3px;"></i></h4>
					<ul>
						
						<li>
							<strong>Unidad 01</strong>
							<p><?php echo get_post_meta( get_the_ID(), 'curso_presencial_resena_01', true ); ?></p>
						</li>
						
						<li>
							<strong>Unidad 02</strong>
							<p><?php echo get_post_meta( get_the_ID(), 'curso_presencial_resena_02', true ); ?></p>
						</li>

						<li>
							<strong>Unidad 03</strong>
							<p><?php echo get_post_meta( get_the_ID(), 'curso_presencial_resena_03', true ); ?></p>
						</li>

						<li>
							<strong>Unidad 04</strong>
							<p><?php echo get_post_meta( get_the_ID(), 'curso_presencial_resena_04', true ); ?></p>
						</li>

					</ul>
				</div>
				<!-- end reseñas --> 
				<?php 
				} else {
				?>

				<div class="dashboard-list-box invoices margin-top-20">
					<h4>Docente <i class="im im-icon-File-HorizontalText" style="position: relative; top: 3px;"></i></h4>
					<div class="bloque-docente">

						<img src="<?php echo get_template_directory_uri(); ?>/images/docente.png" alt="">

						<div class="bloque-docente__txt">
							<h5>Nombre Docente</h5>
							<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Delectus aliquid, tempore impedit maiores, accusantium vel praesentium labore velit cumque enim natus dicta beatae temporibus minus culpa! Ea reprehenderit hic totam!</p>
						</div>

					</div>

				</div>

				<?php 
				}
				?>

			</div>

			<?php
			if(isset($status[0]['comprado'])){
			?>
			<div class="col-lg-6 col-md-12">
				<div class="dashboard-list-box invoices margin-top-20">

					<!-- -->

					<div class="dashboard-list-box invoices margin-top-20">
						<ul>
							<li style="padding-left: 30px;"><strong>¿Qué vas vamos a ver en este curso?</strong> Lorem ipsum dolor sit amet consectetur, adipisicing elit. Esse mollitia veritatis aperiam, iste temporibus sint maiores repellendus saepe non sunt exercitationem, dolores rerum quis pariatur accusamus neque sit odit quo?</li>
							<li style="padding-left: 30px;"><strong>A quien está dirigido</strong> Lorem ipsum dolor sit amet consectetur, adipisicing elit. Esse mollitia veritatis aperiam, iste temporibus sint maiores repellendus saepe non sunt exercitationem, dolores rerum quis pariatur accusamus neque sit odit quo?</li>
						</ul>
					</div>

					<div class="dashboard-list-box invoices margin-top-20">
						<h4>Docente <i class="im im-icon-File-HorizontalText" style="position: relative; top: 3px;"></i></h4>
						<div class="bloque-docente">

							<img src="<?php echo get_template_directory_uri(); ?>/images/docente.png" alt="">

							<div class="bloque-docente__txt">
								<h5>Nombre Docente</h5>
								<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Delectus aliquid, tempore impedit maiores, accusantium vel praesentium labore velit cumque enim natus dicta beatae temporibus minus culpa! Ea reprehenderit hic totam!</p>
							</div>

						</div>

					</div>

					<!-- -->

					<h4 style="    font-size: 19px;
    color: #aa82e8;">¡Comenzemos el curso!</h4>

					<?php 
					// verificar en que unidad va el alumnno
					$id_user = getDataSession("id");
					$id_curso = get_the_ID();

					$tableName = $wpdb->prefix . "estado_curso";
				    $consulta = $wpdb->get_results(" SELECT * FROM $tableName WHERE `id_user` = $id_user AND `id_curso` = $id_curso LIMIT 0,1 "); 

					if(count($consulta) > 0){

						foreach($consulta as $res){

							$unidad_1 = $res->unidad_1;
							$unidad_2 = $res->unidad_2;
							$unidad_3 = $res->unidad_3;
							$unidad_4 = $res->unidad_4;

							//
							if($unidad_1 != 0){
								$status_02 = "";
							} else {
								$status_02 = "disabled";
							}

							if($unidad_2 != 0){
								$status_03 = "";
							} else {
								$status_03 = "disabled";
							}

							if($unidad_3 != 0){
								$status_04 = "";
							} else {
								$status_04 = "disabled";
							}

							if($status_02 === "disabled" && $status_03 === "disabled" && $status_04 === "disabled"){
								$show_01 = "show";
							} else {
								$show_01 = "";
							}

							if($status_02 === "" && $status_03 === "disabled" && $status_04 === "disabled"){
								$show_02 = "show";
							} else {
								$show_02 = "";
							}

							if($status_02 === "" && $status_03 === "" && $status_04 === "disabled"){
								$show_03 = "show";
							} else {
								$show_03 = "";
							}

							if($status_02 === "" && $status_03 === "" && $status_04 === ""){
								$show_04 = "show";
							} else {
								$show_04 = "";
							}
							//

						}

					}
					// end verificar en que unidad va el alumnno
					?>



					<!-- test --> 
					<div id="accordion" class="accordionStyle">

						<div class="card">
							<div class="card-header" id="h_unidad_01">
								<h5 class="mb-0">
									<button class="btn btn-link collapsed" data-toggle="collapse" data-target="#unidad_01" aria-expanded="false" aria-controls="unidad_01">
										Unidad 01 <?php if(estadoUnidad(1,$id_user,$id_curso)){ echo "<img src='".get_template_directory_uri()."/images/ok.png' style='width: 25px; position: relative; top: -1px; margin-left: 5px;'>"; } ?>
									</button>
								</h5>
							</div>
							<div id="unidad_01" class="collapse <?php echo $show_01; ?>" aria-labelledby="h_unidad_01" data-parent="#accordion">
								<div class="card-body">
									<?php 
										include('unidades/unidad01.php');
									?>
								</div>
							</div>
						</div>
						<div class="card">
							<div class="card-header" id="h_unidad_02">
								<h5 class="mb-0">
									<button class="btn btn-link collapsed" <?php echo $status_02; ?> data-toggle="collapse" data-target="#unidad_02" aria-expanded="false" aria-controls="unidad_02">
										Unidad 02 <?php if(estadoUnidad(2,$id_user,$id_curso)){ echo "<img src='".get_template_directory_uri()."/images/ok.png' style='width: 25px; position: relative; top: -1px; margin-left: 5px;'>"; } ?>
									</button>
								</h5>
							</div>
							<div id="unidad_02" class="collapse <?php echo $show_02; ?>" aria-labelledby="h_unidad_02" data-parent="#accordion">
								<div class="card-body">
									<?php 
										include('unidades/unidad02.php');
									?>
								</div>
							</div>
						</div>
						<div class="card">
							<div class="card-header" id="h_unidad_03">
								<h5 class="mb-0">
									<button class="btn btn-link collapsed" <?php echo $status_03; ?> data-toggle="collapse" data-target="#unidad_03" aria-expanded="false" aria-controls="unidad_03">
										Unidad 03 <?php if(estadoUnidad(3,$id_user,$id_curso)){ echo "<img src='".get_template_directory_uri()."/images/ok.png' style='width: 25px; position: relative; top: -1px; margin-left: 5px;'>"; } ?>
									</button>
								</h5>
							</div>
							<div id="unidad_03" class="collapse <?php echo $show_03; ?>" aria-labelledby="h_unidad_03" data-parent="#accordion">
								<div class="card-body">
									<?php 
										include('unidades/unidad03.php');
									?>
								</div>
							</div>
						</div>
						<div class="card">
							<div class="card-header" id="h_unidad_04">
								<h5 class="mb-0">
									<button class="btn btn-link collapsed" <?php echo $status_04; ?> data-toggle="collapse" data-target="#unidad_04" aria-expanded="false" aria-controls="unidad_04">
										Unidad 04 <?php if(estadoUnidad(4,$id_user,$id_curso)){ echo "<img src='".get_template_directory_uri()."/images/ok.png' style='width: 25px; position: relative; top: -1px; margin-left: 5px;'>"; } else { echo ""; } ?>
									</button>
								</h5>
							</div>
							<div id="unidad_04" class="collapse <?php echo $show_04; ?>" aria-labelledby="h_unidad_04" data-parent="#accordion">
								<div class="card-body">
									<?php 
										include('unidades/unidad04.php');
									?>
								</div>
							</div>
						</div>
					</div>
					<!-- end test --> 













					<!--<ul>
						
						<li><i class="list-box-icon fa fa-video-camera"></i>
							<strong class="ver_unidad" data-id="1" style="cursor:pointer;">Unidad 01</strong>

							<div id="unidad_01"></div>

						</li>
						
						<li class="course-disabled"><i class="list-box-icon fa fa-video-camera"></i>
							<strong>Unidad 02</strong>

							<div id="unidad_02"></div>

						</li>

						<li class="course-disabled"><i class="list-box-icon fa fa-video-camera"></i>
							<strong>Unidad 03</strong>

							<div id="unidad_03"></div>

						</li>

						<li class="course-disabled"><i class="list-box-icon fa fa-video-camera"></i>
							<strong>Unidad 04</strong>

							<div id="unidad_04"></div>

						</li>

					</ul>-->

				</div>
			</div> 
			<?php 
			} else {
			?>

			<div class="col-lg-6 col-md-12">
				<div class="dashboard-list-box invoices margin-top-20">
					<ul>
						<li><strong>¿Qué vas vamos a ver en este curso?</strong> Lorem ipsum dolor sit amet consectetur, adipisicing elit. Esse mollitia veritatis aperiam, iste temporibus sint maiores repellendus saepe non sunt exercitationem, dolores rerum quis pariatur accusamus neque sit odit quo?</li>
						<li><strong>A quien está dirigido</strong> Lorem ipsum dolor sit amet consectetur, adipisicing elit. Esse mollitia veritatis aperiam, iste temporibus sint maiores repellendus saepe non sunt exercitationem, dolores rerum quis pariatur accusamus neque sit odit quo?</li>
						<li><strong>Objetivos</strong> Lorem ipsum dolor sit amet consectetur, adipisicing elit. Esse mollitia veritatis aperiam, iste temporibus sint maiores repellendus saepe non sunt exercitationem, dolores rerum quis pariatur accusamus neque sit odit quo?</li>
						<li><strong>Contenidos</strong> Lorem ipsum dolor sit amet consectetur, adipisicing elit. Esse mollitia veritatis aperiam, iste temporibus sint maiores repellendus saepe non sunt exercitationem, dolores rerum quis pariatur accusamus neque sit odit quo?</li>
						<li><strong>Cantidad de horas</strong> Lorem ipsum dolor sit amet consectetur, adipisicing elit. Esse mollitia veritatis aperiam, iste temporibus sint maiores repellendus saepe non sunt exercitationem, dolores rerum quis pariatur accusamus neque sit odit quo?</li>
						<!-- <li><strong>Docente con su foto y breve cv</strong> Lorem ipsum dolor sit amet consectetur, adipisicing elit. Esse mollitia veritatis aperiam, iste temporibus sint maiores repellendus saepe non sunt exercitationem, dolores rerum quis pariatur accusamus neque sit odit quo?</li> -->
						<li><strong>Certifica</strong> Fundación Ideas para la Infancia y Otec Unes Chile</li>
					</ul>
				</div>


				<div class="pagar-con-flow">
					<strong>COMPRAR CURSO</strong>
					<form id="form-flow" method="post" action="<?php echo get_template_directory_uri(); ?>/webpay--procesar.php">
						<input type="hidden" name="id_curso" id="id_curso" value="<?php echo get_the_ID(); ?>">
						<input type="hidden" name="curso" id="curso" value="<?php echo the_title(); ?>">
						<input type="hidden" name="monto" id="monto" value="<?php echo get_post_meta( get_the_ID(), 'curso_presencial_monto', true ); ?>">
						<div style="display:flex">
							<input type="text" name="nombre" id="nombre" placeholder="Ingresa tu nombre">
							<input type="text" name="apellido" id="apellido" placeholder="Ingresa tu apellido">
						</div>
						<div style="display:flex">
							<input type="email" name="email" id="email" placeholder="Ingresa tu email">
							<input type="tel" name="telefono" id="telefono" placeholder="Ingresa tu teléfono">
						</div>
						<button type="button" id="pagar">IR A PAGAR</button>
					</form>
					<small><img src="<?php echo get_template_directory_uri(); ?>/images/logo-flow.svg" alt="" width="65"> Serás direccionado a una plataforma segura.</small>
				</div>


			</div>

			<?php 
			}
			?>


<?php 
get_footer();
?>

<script>
const ver_unidad = document.querySelectorAll(".ver_unidad")

for (const ver of ver_unidad) {

	ver.addEventListener('click', function(event) {

	    let unidad = this.getAttribute('data-id');

		const data = new FormData();
		data.append('unidad', unidad);
		data.append('curso', <?php echo get_the_ID(); ?>);

		fetch('<?php echo get_template_directory_uri(); ?>/verify__unity.php', {
			method: 'POST',
			body: data
		})
		.then(function(response) {
			if(response.ok) {
				return response.text()
			} else {
				throw "Error en la llamada Ajax";
			}
		})
		.then(function(texto) {

			document.getElementById("unidad_0"+unidad).innerHTML = texto;

			// let res = JSON.parse(texto);

			// console.log(res);

			// res.map(function(item){

			// 	if(item.resultado === "incorrecta"){
			// 		document.querySelector("."+item.class_error).style.color = "#c11d1d";
			// 		document.querySelector("."+item.class_ok).style.color = "#9bb730";
			// 	} else {
			// 		document.querySelector("."+item.class_ok).style.color = "#9bb730";
			// 	}

			// });

			// document.getElementById("cargando_"+etp).style.display = "none";

		})
		.catch(function(err) {
			console.log(err);
		});

	});
}


























function ValidateEmail(mail){
 	if (/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/.test(mail)){
    	return (true)
  	} else {
		return (false)
	}
}

jQuery(document).ready(function($){
	$('input[type="checkbox"]').on('change', function() {
		// alert(this.className);
		$('input[class="' + this.className + '"]').not(this).prop('checked', false);
	});
});

if(document.getElementById("pagar")){

	document.getElementById("pagar").addEventListener("click", function(){

		document.getElementById("pagar").innerHTML = "PROCESANDO...";

		let form = document.getElementById("form-flow");

		// campos 
		let nombre = document.getElementById("nombre");
		let apellido = document.getElementById("apellido");
		let email = document.getElementById("email");
		let telefono = document.getElementById("telefono");

		let curso = document.getElementById("curso");
		let monto = document.getElementById("monto");
		let id_curso = document.getElementById("id_curso");

		if(nombre.value === ""){
			alert("Por favor completa el campo nombre para continuar");
			return false;
		}

		if(apellido.value === ""){
			alert("Por favor completa el campo apellido para continuar");
			return false;
		}

		if(email.value === ""){
			alert("Por favor completa el campo email para continuar");
			return false;
		}

		if(telefono.value === ""){
			alert("Por favor completa el campo telefono para continuar");
			return false;
		}

		if(!ValidateEmail(email.value)){
			alert("Por favor ingresa un email válido para continuar");
			return false;
		}

		if(curso.value != "" && monto.value != "" && id_curso.value != ""){

			form.submit();

		}

	});

}

const finalizar_unidad = document.querySelectorAll(".finalizar_unidad")

for (const finalizar_btn of finalizar_unidad) {

	finalizar_btn.addEventListener('click', function(event) {

		var result = [];

		// if(!confirm('¿Estás seguro?')) {
		// 	return false;
		// }

		let etp = this.getAttribute('data-id');
		let cant = this.getAttribute('data-cant');

		document.getElementById("cargando_"+etp).style.display = "block";

		const data = new FormData(document.getElementById('form_etp_0'+etp));

		data.append('id_user', <?php echo getDataSession("id"); ?>);
		data.append('id_curso', <?php echo get_the_ID(); ?>);
		data.append('unidad', etp);
		data.append('cant', cant);

		let file = '<?php echo get_template_directory_uri(); ?>/verify_0'+etp+'.php';

		// alert(file);

		fetch(file, {
			method: 'POST',
			body: data
		})
		.then(function(response) {
			if(response.ok) {
				return response.text()
			} else {
				throw "Error en la llamada Ajax";
			}
		})
		.then(function(texto) {

			console.log("que trae: "+texto);

			let res = JSON.parse(texto);

			res.map(function(item){

				if(item.resultado === "incorrecta"){
					
					if(item.class_error){
						document.querySelector("."+item.class_error).style.color = "#c11d1d";
					}

				} else {

					if(item.class_ok){
						document.querySelector("."+item.class_ok).style.color = "#9bb730";
						document.querySelector("."+item.class_ok).parentElement.parentElement.classList.add("ok");;
					}

				}

				// console.log(item.intento+' '+item.resultado);

				if(item.intento == 1 && item.resultado === "correcta" || item.intento == 2 && item.resultado === "correcta"){
					result.push("si");
				} else if (item.intento == 2 && item.resultado === "incorrecta"){
					result.push("si intento");
				} else if (item.intento == 1 && item.resultado === "incorrecta") {
					result.push("no");
				} else {
					result.push("no");
				}

			});

			console.log(result);

			if(result.includes("si") && !result.includes("no") || result.includes("si intento")){
				
				location.reload();

				// se actualiza la unidad cursada
				const data = new FormData(document.getElementById('form_etp_0'+etp));

				data.append('id_user', <?php echo getDataSession("id"); ?>);
				data.append('id_curso', <?php echo get_the_ID(); ?>);
				data.append('unidad', etp);

				fetch('<?php echo get_template_directory_uri(); ?>/update-unidad.php', { method: 'POST', body: data })
				.then(function(response) { if(response.ok) { return response.text() } else { throw "Error en la llamada Ajax"; } })
				.then(function(texto) {} )
				.catch(function(err) { console.log(err); });

			} else {

				console.log("aún no se puede avanzar");

			}

			document.getElementById("cargando_"+etp).style.display = "none";

		})
		.catch(function(err) {
			console.log(err);
		});

	});

}

</script>