<?php 
session_start();
verificarSesion();

get_header();

/* * * * * * */
$id_alumno = getDataSession("id");
$email_alumno = getDataSession("email");

$fecha_actual = date("Y-m-d");
/* * * * * * */
?>

<!-- Dashboard -->
<div id="dashboard">

	<?php
    require_once dirname( __FILE__ ) . '/includes/side.php';

	require_once dirname( __FILE__ ) . '/features/link-generar-certificado-functions.php';
    ?>
	
	<div class="dashboard-content">

		<!-- Titlebar -->
		<div id="titlebar">
			<div class="row">
				<div class="col-md-12">
					<h2>Hol@, <?php echo getDataSession("nombre"); ?>!</h2>
				</div>
			</div>
		</div>

		<!-- Notice -->
		<!-- <div class="row">
			<div class="col-md-12">
				<div class="notification success closeable margin-bottom-30">
					<p>Recuerda completar las preguntas dentro de cada curso para poder finalizar.</p>
					<a class="close" href="#"></a>
				</div>
			</div>
		</div> -->

		<style>
		h4.title-row {
			padding-left: 15px;
		}
		.itemList{
			list-style: none;
			padding: 9px 11px;
			background: #f7f7f7;
			border-radius: 15px;
			box-shadow: 0px 0px 10px #b9b6b6;
			max-width: 364px;
		}
		.itemList h4{
			font-size: 19px;
		    margin-bottom: 23px;
		}
		.buttons a{
			color: #FFFFFF;
			background: #B0358A;
			padding: 5px 13px;
			border-radius: 30px;
			padding-top: 6px;
			margin-right: 10px;
			display: flex;
			margin-bottom: 10px;
			font-size: 10px;
			text-transform: uppercase;
			align-items: center;
			max-width: max-content;
		}
		.buttons a.post{
			background: #FFEB68 !important;
			color: #121212 !important
		}
		.buttons a img{
			width: 21px;
			margin-right: 3px;
		}
		.itemList .imagen-curso{
			border-radius: 7px;
			margin-bottom: 19px;
			height: 70px;
			width: 100%;
			object-fit: cover;
		}
		</style>

		<!-- Content -->
		<div class="row">

            <h4 class="title-row">MIS CERTIFICADOS</h4>

			<?php 
			function mesEnPalabras($numeroMes) {
				switch ($numeroMes) {
					case 1:
						return "Enero";
					case 2:
						return "Febrero";
					case 3:
						return "Marzo";
					case 4:
						return "Abril";
					case 5:
						return "Mayo";
					case 6:
						return "Junio";
					case 7:
						return "Julio";
					case 8:
						return "Agosto";
					case 9:
						return "Septiembre";
					case 10:
						return "Octubre";
					case 11:
						return "Noviembre";
					case 12:
						return "Diciembre";
					default:
						return "Mes inválido";
				}
			}

			global $wpdb;
			$tableName = $wpdb->prefix . "unidades_dinamico";

			$id_user = getDataSession("id");

			$consulta = $wpdb->get_results(" SELECT * FROM $tableName WHERE `id_user` = $id_user AND `tipo` = 3 AND `status` = 1 AND `show_cert` = 'SI' ");

			if(count($consulta) > 0){

				echo "<ul>";

					$i = 0;

					foreach($consulta as $res){ $i++;

						$user_info = get_userdata($id_user);
						$user_name = @$user_info->display_name;

						$multiply = $i*3;
						$multiply2 = $i*10;

						$user_email = @$user_info->user_email;

						$hoursExploid = explode(" ",get_post_meta( $res->id_curso, 'curso_horas', true ));
						$cursoFecha = explode("-",get_post_meta($res->id_curso, 'curso_fecha', true ));
		
						$month = mesEnPalabras($cursoFecha[1]);
						$year = $cursoFecha[2];

						$typeCourse = "";
					
						$post_id = $res->id_curso;
						$taxonomy = 'categoria'; 
						$terms = get_the_terms($post_id, $taxonomy);
					
						if (!is_wp_error($terms) && !empty($terms)) {
							foreach ($terms as $term) {
								$typeCourse = $term->name;
							}
						}

						echo certificadoCurso($multiply, $res->id_curso, $user_name, $month, $year, get_the_title($res->id_curso), $typeCourse, $hoursExploid[0]);
						echo certificadoDiploPost($multiply2, $res->id_curso, $user_name, $month, $year, get_the_title($res->id_curso), $typeCourse, $hoursExploid[0]);

						$texto_minusculas = strtolower($user_name);
						$texto_formateado = str_replace(' ', '-', $texto_minusculas);
			
						date_default_timezone_set('America/Santiago');
						$date = date('d-m-Y');

						$html = '<li class="itemList">';

								if(get_post_meta( $res->id_curso, 'curso_imagen_pequena', true )){
									$html .= '<img class="imagen-curso" src="'.get_post_meta( $res->id_curso, 'curso_imagen_pequena', true ).'" />';
								}

								$html .= '<h4>'.get_the_title($res->id_curso).'</h4>
								<div class="buttons">
									
									<a href="#" onclick="generarPDF(\'pdf_curso_' . $res->id_curso . '\', \'' . $texto_formateado . '\', \'' . $date . '\')"><img src="'.get_template_directory_uri().'/features/img/pdf-document-svgrepo-com.svg" /> Descargar certificado Curso</a>
									<a href="#" class="post" onclick="generarPDF(\'pdf_diplo_post_' . $res->id_curso . '\', \'' . $texto_formateado . '\', \'' . $date . '\')"><img src="'.get_template_directory_uri().'/features/img/pdf-document-svgrepo-com.svg" /> Descargar certificado Diplomado/Post titulo</a>

								<div>
							  </li>';

							  echo $html;
					}


				echo "</ul>";

				?>

				<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
				<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.8.0/html2pdf.bundle.min.js"></script>
				<script src="<?php echo get_template_directory_uri(); ?>/features/index.js"></script>

				<script>
				jQuery.noConflict();

				function generarPDF(id, nombre, fecha) {
					var element = document.getElementById(id);
					
					var opciones = {
					margin: 10,
					filename: `${nombre}-${fecha}.pdf`,
					image: { type: 'jpeg', quality: 0.98 },
					html2canvas: { scale: 3 },
					jsPDF: { unit: 'mm', format: 'a4', orientation: 'landscape' } // Aquí se establece la orientación
					};

					html2pdf(element, opciones);
				}
				</script>

				<?php 

			} else {
				echo "<p style='padding: 20px;'>Aún no tienes certificados disponibles para visualizar.</p>";
			}

			?>

		</div>

<?php 
get_footer();
?>