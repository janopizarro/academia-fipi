<?php 
session_start();

// se verifica si la fecha de termino ya se cumplió
$fechaTermino = get_post_meta( get_the_ID(), 'curso_fecha_termino', true );

setlocale(LC_TIME, 'es_ES', 'Spanish_Spain', 'Spanish'); 
$date = $date = str_replace("/","-",get_post_meta( get_the_ID(), 'curso_fecha_termino', true ));

$date_now = new DateTime();
$date2    = new DateTime($fechaTermino);
$date2->modify('+1 day');

if($date_now > $date2){ 

	echo "<script>alert('Curso finalizó el ".$date."');</script>"; redirect(100,'login'); 

} else {

	/* * functions single course * */
	require_once dirname( __FILE__ ) . '/single-course--functions.php';

	// verificarSesion();

	get_header();

	if(getUnitsCourse(get_the_ID())){ 

		$tableName = $wpdb->prefix . "unidades_dinamico";
		$id_user = getDataSession("id");
		$id_curso = get_the_ID();
		$consulta = $wpdb->get_results(" SELECT * FROM $tableName WHERE `id_user` = $id_user AND `id_curso` = $id_curso ");

	} else {

		$tableName = $wpdb->prefix . "estado_curso";
		$id_user = getDataSession("id");
		$id_curso = get_the_ID();
		$consulta = $wpdb->get_results(" SELECT * FROM $tableName WHERE `id_user` = $id_user AND `id_curso` = $id_curso ");

	}
	?>

	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/bootstrap.min.css" crossorigin="anonymous">

	<style>
	iframe{
		width: 100%;
		height: 711px;
	}
	/* #dashboard{
		padding-top: 0px !important;
	} */
	</style>

	<!-- Dashboard -->
	<div id="dashboard">

		<?php 
		/* * side * */
		require_once dirname( __FILE__ ) . '/includes/side.php';
		?>

		<div class="dashboard-content dashboard-content--curso">

			<!-- Titlebar -->
			<div id="titlebar">
				<div class="row">
					<div class="col-md-12">
						<?php 
						if(count($consulta) > 0){

							echo '<h2 style="width:100%">Bienvenido al Curso '.get_the_title().'</h2>';

						} else {

							echo '<h2 style="width:100%">'.get_the_title().'</h2>';

						}
						?>
						<small style="display: inline-block; font-size: 18px; color: orange; padding: 12px; padding-bottom: 0px; background: #FFFFFF; width: auto;">
						Curso disponible hasta el <strong><?php
						setlocale(LC_TIME, 'es_ES', 'Spanish_Spain', 'Spanish'); 
						$date = str_replace("/","-",get_post_meta( get_the_ID(), 'curso_fecha_termino', true ));
						echo strftime('%d %B %Y',strtotime($date)); 
						?></strong>
						</small>
					</div>
				</div>
			</div>

			<div class="row">
				
				<div class="col-lg-6 col-md-12">
					<div class="dashboard-list-box with-icons margin-top-20"> <div id="getVid"></div>
						
						<?php 
						if(getUnitsCourse(get_the_ID())){ 

							if($id_user){

								if(haveActivityInCourse( $id_user, $id_curso ) && !haveFinishedCourse( $id_user, $id_curso )){

									// ACTIVIDAD EN EL CURSO
									echo '<div class="embed-responsive embed-responsive-16by9" id="loadVideo">
											<iframe id="videoPlayer" allowfullscreen></iframe>
										</div>';

								} elseif (haveFinishedCourse( $id_user, $id_curso )){

									// CURSO TERMINADO
									echo '<img src="'.get_template_directory_uri().'/images/finalizada_img.jpg">';

								} else {

									// INTRO DEL CURSO
									if(get_post_meta( get_the_ID(), 'curso_imagen_grande', true )){

										echo '<img src="'.get_post_meta( get_the_ID(), 'curso_imagen_grande', true ).'" alt="">';

									} else {

										/* obtener el video intro */
										preg_match('/src="([^"]+)"/', get_post_meta( get_the_ID(), 'curso_intro_video_iframe', true), $match);
										$url = $match[1];
									
										$js = "    
											<script>
											let videoPlayer = document.getElementById('videoPlayer');
											let url_string = '".$url."';
											let adsURL = url_string+'&api=1&player_id=video&title=0&amp;byline=0&amp;portrait=0&amp;color=c9ff23;autoplay=1';
											videoPlayer.src = adsURL;
											</script>
										";

										echo $js;
										/* end obtener el video intro */

										echo '<div class="embed-responsive embed-responsive-16by9" id="loadVideo">
												<iframe id="videoPlayer" allowfullscreen></iframe>		
											</div>';
											// <iframe src="//player.vimeo.com/video/'.str_replace("https://vimeo.com/","",get_post_meta( get_the_ID(), 'curso_intro_video', true )).'?api=1&player_id=video&title=0&amp;byline=0&amp;portrait=0&amp;color=c9ff23;autoplay=1" width="640" height="360" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" id="video" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
									}

								}

							} else {

								// INTRO DEL CURSO
								if(get_post_meta( get_the_ID(), 'curso_imagen_grande', true )){

									echo '<img src="'.get_post_meta( get_the_ID(), 'curso_imagen_grande', true ).'" alt="">';

								} else {

									echo '<div class="embed-responsive embed-responsive-16by9" id="loadVideo">
											<iframe src="//player.vimeo.com/video/'.str_replace("https://vimeo.com/","",get_post_meta( get_the_ID(), 'curso_intro_video', true )).'?api=1&player_id=video&title=0&amp;byline=0&amp;portrait=0&amp;color=c9ff23;autoplay=1" width="640" height="360" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" id="video" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
										</div>';
								}

							}

						} else {

							if(introFinish($id_user, get_the_ID())){

								if(getPercentaje(get_the_ID(), getDataSession("id")) == 100){

									if(get_post_meta( get_the_ID(), 'curso_imagen_grande', true )){
	
										echo '<img src="'.get_post_meta( get_the_ID(), 'curso_imagen_grande', true ).'" alt="">';
		
									} else {
		
										echo '<div class="embed-responsive embed-responsive-16by9" id="loadVideo">
												<iframe id="videoPlayer" allowfullscreen></iframe>		
											</div>';
										// echo '<div class="embed-responsive embed-responsive-16by9" id="loadVideo">
										// 	<iframe src="//player.vimeo.com/video/'.str_replace("https://vimeo.com/","",get_post_meta( get_the_ID(), 'curso_intro_video', true )).'?api=1&player_id=video&title=0&amp;byline=0&amp;portrait=0&amp;color=c9ff23;autoplay=1" width="640" height="360" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" id="video" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
										// </div>';
									}
									
								} else {

									// echo '<div class="embed-responsive embed-responsive-16by9" id="loadVideo"></div>';
									echo '<div class="embed-responsive embed-responsive-16by9" id="loadVideo">
									<iframe id="videoPlayer" allowfullscreen></iframe>		
								</div>';

								}

							} else {
	
								if(get_post_meta( get_the_ID(), 'curso_imagen_grande', true )){
	
									echo '<img src="'.get_post_meta( get_the_ID(), 'curso_imagen_grande', true ).'" alt="">';
	
								} else {
	
									echo '<div class="embed-responsive embed-responsive-16by9" id="loadVideo">
										<iframe src="//player.vimeo.com/video/'.str_replace("https://vimeo.com/","",get_post_meta( get_the_ID(), 'curso_intro_video', true )).'?api=1&player_id=video&title=0&amp;byline=0&amp;portrait=0&amp;color=c9ff23;autoplay=1" width="640" height="360" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" id="video" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
									</div>';
								}
	
							}

						}
						?>
						
					</div>

					<?php 				
					if(count($consulta) > 0){
					
						/* * REVIEW COURSE * */
						if(getUnitsCourse(get_the_ID())){
							require_once dirname( __FILE__ ) . '/unit/review.php';
						} else {
							require_once dirname( __FILE__ ) . '/includes/review-course.php';							
						}

					} else {

						/* * TEACHER INFO * */
						require_once dirname( __FILE__ ) . '/includes/teacher-info.php';

					}
					?>

				</div>

				<?php
				if(count($consulta) > 0){
				?>
				<div class="col-lg-6 col-md-12">
					<div class="dashboard-list-box invoices margin-top-20">

						<!-- -->

						<?php 
						/* * DESCRIPTION COURSE SHORT * */
						require_once dirname( __FILE__ ) . '/includes/description-course--short.php';

						/* * TEACHER INFO * */
						require_once dirname( __FILE__ ) . '/includes/teacher-info.php';

						if(getUnitsCourse(get_the_ID())){

							/* * BIBLIOGRAPHY * */
							require_once dirname( __FILE__ ) . '/includes/bibliography.php';

						}
						?>

						<!-- -->

						<?php 
						$unidades = getUnitsCourse(get_the_ID());
						// UNIT STATUS · SE VERIFICA SI TIENE UNIDADES DINÁMICAS
						if(getUnitsCourse(get_the_ID())){

							if(!haveFinishedCourse( $id_user, $id_curso )){
							?>
								<h4 style="font-size: 14px; color: #797977;">Progreso del curso <strong id="porcentajeVal" style="color: purple; font-size: 16px; margin-top: 10px; font-family: system-ui;"><?php echo getPercentageDinamic(get_the_ID(), getDataSession("id")); ?> %</strong></h4>
							<?php 
							} else {
								echo '
								
									<h4 style="color: purple; font-size: 16px; margin-top: 0px; font-family: system-ui;">Curso Finalizado</h4>
								
								';
							}

							require_once dirname( __FILE__ ) . '/unit/content.php';							

							if(getUnitsCourse(get_the_ID()) && haveFinishedCourse( $id_user, $id_curso )){

								/* * library * */
								require_once dirname( __FILE__ ) . '/includes/library.php';
	
								/* * bibliography * */
								require_once dirname( __FILE__ ) . '/includes/bibliography.php';
	
							}

						} else {

							?>
							<h4 style="font-size: 14px; color: #797977;">Progreso del curso <strong id="porcentajeVal" style="color: purple; font-size: 16px; margin-top: 10px; font-family: system-ui;"><?php echo getPercentaje(get_the_ID(), getDataSession("id")); ?> %</strong></h4>
							<?php

							require_once dirname( __FILE__ ) . '/includes/unit-status.php';

						}
						?>

					</div>
				</div> 
				
				<?php 
		
				} else {
				
				?>

				<div class="col-lg-6 col-md-12">

					<?php 
					/* * description course * */
					require_once dirname( __FILE__ ) . '/includes/description-course.php';

					if(get_post_meta( get_the_ID(), 'curso_monto', true )){
						/* * form flow * */
						require_once dirname( __FILE__ ) . '/includes/form-flow.php';
					}
					?>

				</div>

				<?php 
				
				}
			
				?>


	<?php 
	get_footer();
	?>

	<script src="<?php echo get_template_directory_uri(); ?>/js/pagar-flow.js"></script>
	<script src="<?php echo get_template_directory_uri(); ?>/js/cleave.min.js"></script>
	<script src="<?php echo get_template_directory_uri(); ?>/js/chile.js"></script>

<?php 
}
?>