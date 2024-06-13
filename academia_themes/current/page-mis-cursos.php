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
		<div class="row">
			<div class="col-md-12">
				<div class="notification success closeable margin-bottom-30">
					<p>Recuerda completar las preguntas dentro de cada curso para poder finalizar.</p>
					<a class="close" href="#"></a>
				</div>
			</div>
		</div>

		<!-- Content -->
		<div class="row row--new">

            <h4 class="title-row">ACTIVIDAD RECIENTE</h4>

			<?php 

			global $wpdb;
			$tableName = $wpdb->prefix . "estado_curso";
			$tableNameTwo = $wpdb->prefix . "unidades_dinamico";

			$id_user = getDataSession("id");

			$consulta = $wpdb->get_results(" SELECT * FROM $tableName WHERE `id_user` = $id_user GROUP BY `id_curso` ");
			$consultaTwo = $wpdb->get_results(" SELECT * FROM $tableNameTwo WHERE `id_user` = $id_user GROUP BY `id_curso` ");

			$arr = array();

			if(count($consulta) > 0){
				$res_1 = $consulta;
			} else {
				$res_1 = array();
			}

			if(count($consultaTwo) > 0){
				$res_2 = $consultaTwo;
			} else {
				$res_2 = array();
			}

			$arr = array_merge($res_1,$res_2);

			if(count($arr) > 0){

				foreach($arr as $res){

					$id_curso = $res->id_curso;

					include 'includes/card.php';

				}
				
			} else {

				echo "<p class='working-course'>Aún no tienes cursos, revisa nuestra galería</p>";

			}

			?>

		</div>

<?php 
get_footer();
?>