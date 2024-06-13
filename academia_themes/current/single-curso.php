<?php 
session_start();

/* * * */
$id_curso = get_the_ID();
$id_alumno = getDataSession("id");
$email_alumno = getDataSession("email");
$nombre_alumno = getDataSession("nombre");
/* * * */

$fecha_inicio = json_decode(obtenerFechaEstablecida("inicio", $id_curso, $email_alumno))->fecha_inicio;
$fecha_termino = json_decode(obtenerFechaEstablecida("termino", $id_curso, $email_alumno))->fecha_termino;

$cantAccesosCursoFinalizado = cantAccesosCursoFinalizado($id_curso,getDataSession("id"));

/*
 * se verifica la fecha de termino, si es la por defecto del
 * curso o si tiene una fecha de termino especial
 * 
 */

$vigencia = json_decode(verificarVigenciaDeCurso( $id_curso, $id_alumno, $fecha_termino ));

if($vigencia->status && !$cantAccesosCursoFinalizado["status"]){ 

	echo "<script>alert('Curso finalizó el ".$vigencia->date."');</script>"; redirect(100,'login'); 

} else {

    if(getDataSession("id")){
        // se inserta una nueva visualización
        registraNuevaVisualizacion($id_alumno, $id_curso);
    }

	require_once dirname( __FILE__ ) . '/single-course--functions.php';

	get_header();

    /*
     * se obtienen las unidades del curso, dinamicas o fijas
     */
     
	if(getUnitsCourse($id_curso)){ 

		$tableName = $wpdb->prefix . "unidades_dinamico";
		$consulta = $wpdb->get_results(" SELECT * FROM $tableName WHERE `id_user` = $id_alumno AND `id_curso` = $id_curso ");

	} else {

		$tableName = $wpdb->prefix . "estado_curso";
		$consulta = $wpdb->get_results(" SELECT * FROM $tableName WHERE `id_user` = $id_alumno AND `id_curso` = $id_curso ");

	}
	?>

<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/bootstrap.min.css" crossorigin="anonymous">

<style>
iframe {
    width: 100%;
    height: 711px;
}
</style>

<!-- Dashboard -->
<div id="dashboard">

    <?php 
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

                    $fecha_inicio = json_decode(obtenerFechaEstablecida("inicio", $id_curso, $email_alumno))->fecha_inicio;
                    $fecha_termino = json_decode(obtenerFechaEstablecida("termino", $id_curso, $email_alumno))->fecha_termino;
                    $fecha_estado_actual = estadoActualFecha($fecha_inicio, $fecha_termino, "string");

                    echo "<ul class='fechas_info fechas_info__single'>".$fecha_estado_actual."</ul>";

                    ?>
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-lg-6 col-md-12">
                <div class="dashboard-list-box with-icons margin-top-20">
                    <div id="getVid"></div>

                    <?php 
                    
                    if(getUnitsCourse($id_curso)){ 

                        if($id_alumno){

                            if(haveActivityInCourse( $id_alumno, $id_curso ) && !haveFinishedCourse( $id_alumno, $id_curso )){

                                // ACTIVIDAD EN EL CURSO
                                echo '<div class="embed-responsive embed-responsive-16by9" id="loadVideo">
                                        <iframe id="videoPlayer" allowfullscreen></iframe>
                                    </div>';

                            } elseif (haveFinishedCourse( $id_alumno, $id_curso )){

                                // CURSO TERMINADO
                                echo '<img src="'.get_template_directory_uri().'/images/finalizada_img.jpg">';

                            } else {

                                // INTRO DEL CURSO
                                if(get_post_meta( $id_curso, 'curso_imagen_grande', true )){

                                    echo '<img src="'.get_post_meta( $id_curso, 'curso_imagen_grande', true ).'" alt="">';

                                } else {

                                    /* obtener el video intro */
                                    preg_match('/src="([^"]+)"/', get_post_meta( $id_curso, 'curso_intro_video_iframe', true), $match);
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
                                        // <iframe src="//player.vimeo.com/video/'.str_replace("https://vimeo.com/","",get_post_meta( $id_curso, 'curso_intro_video', true )).'?api=1&player_id=video&title=0&amp;byline=0&amp;portrait=0&amp;color=c9ff23;autoplay=1" width="640" height="360" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" id="video" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                }

                            }

                        } else {

                            // INTRO DEL CURSO
                            if(get_post_meta( $id_curso, 'curso_imagen_grande', true )){

                                echo '<img src="'.get_post_meta( $id_curso, 'curso_imagen_grande', true ).'" alt="">';

                            } else {

                                echo '<div class="embed-responsive embed-responsive-16by9" id="loadVideo">
                                        <iframe src="//player.vimeo.com/video/'.str_replace("https://vimeo.com/","",get_post_meta( $id_curso, 'curso_intro_video', true )).'?api=1&player_id=video&title=0&amp;byline=0&amp;portrait=0&amp;color=c9ff23;autoplay=1" width="640" height="360" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" id="video" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                    </div>';
                            }

                        }

                    } else {

                        if(introFinish($id_alumno, $id_curso)){

                            if(getPercentaje($id_curso, getDataSession("id")) == 100){

                                if(get_post_meta( $id_curso, 'curso_imagen_grande', true )){

                                    echo '<img src="'.get_post_meta( $id_curso, 'curso_imagen_grande', true ).'" alt="">';
    
                                } else {
    
                                    echo '<div class="embed-responsive embed-responsive-16by9" id="loadVideo">
                                            <iframe id="videoPlayer" allowfullscreen></iframe>		
                                        </div>';
                                }
                                
                            } else {

                                echo '<div class="embed-responsive embed-responsive-16by9" id="loadVideo">
                                <iframe id="videoPlayer" allowfullscreen></iframe>		
                            </div>';

                            }

                        } else {

                            if(get_post_meta( $id_curso, 'curso_imagen_grande', true )){

                                echo '<img src="'.get_post_meta( $id_curso, 'curso_imagen_grande', true ).'" alt="">';

                            } else {

                                echo '<div class="embed-responsive embed-responsive-16by9" id="loadVideo">
                                    <iframe src="//player.vimeo.com/video/'.str_replace("https://vimeo.com/","",get_post_meta( $id_curso, 'curso_intro_video', true )).'?api=1&player_id=video&title=0&amp;byline=0&amp;portrait=0&amp;color=c9ff23;autoplay=1" width="640" height="360" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" id="video" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                </div>';
                            }

                        }

                    }
     
                    ?>
                </div>

                <?php 				
     
                if(count($consulta) > 0){
                
                    /* * REVIEW COURSE * */
                    if(getUnitsCourse($id_curso)){
                
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

                    <?php 
                    /* * DESCRIPTION COURSE SHORT * */
                    require_once dirname( __FILE__ ) . '/includes/description-course--short.php';

                    /* * TEACHER INFO * */
                    require_once dirname( __FILE__ ) . '/includes/teacher-info.php';

                    if(getUnitsCourse($id_curso)){

                        /* * BIBLIOGRAPHY * */
                        require_once dirname( __FILE__ ) . '/includes/bibliography.php';

                    }

                    $unidades = getUnitsCourse($id_curso);
                    // UNIT STATUS · SE VERIFICA SI TIENE UNIDADES DINÁMICAS
                    if(getUnitsCourse($id_curso)){

                        if(!haveFinishedCourse( $id_alumno, $id_curso )){ ?>

                            <h4 style="font-size: 14px; color: #797977;">Progreso del curso <strong id="porcentajeVal" style="color: purple; font-size: 16px; margin-top: 10px; font-family: system-ui;"><?php echo getPercentageDinamic($id_curso, getDataSession("id")); ?>%</strong></h4>
                            
                        <?php 
							
                        } else {
      
                            echo '<h4 style="color: purple; font-size: 16px; margin-top: 0px; font-family: system-ui;">Curso Finalizado</h4>';

                        }

                        require_once dirname( __FILE__ ) . '/unit/content.php';							

                        if(getUnitsCourse($id_curso) && haveFinishedCourse( $id_alumno, $id_curso )){

                            /* * library * */
                            require_once dirname( __FILE__ ) . '/includes/library.php';

                            /* * bibliography * */
                            require_once dirname( __FILE__ ) . '/includes/bibliography.php';

                        }

                    } else {

                    ?>
                    
                    <h4 style="font-size: 14px; color: #797977;">Progreso del curso <strong id="porcentajeVal" style="color: purple; font-size: 16px; margin-top: 10px; font-family: system-ui;"><?php echo getPercentaje($id_curso, getDataSession("id")); ?>%</strong></h4>
                    
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

                if(get_post_meta( $id_curso, 'curso_monto', true )){
                    /* * form flow * */
                    require_once dirname( __FILE__ ) . '/includes/form-flow.php';
                }
                ?>

            </div>

            <?php 
				
            }
			
    get_footer();
	?>

    <script src="<?php echo get_template_directory_uri(); ?>/js/pagar-flow.js"></script>
    <script src="<?php echo get_template_directory_uri(); ?>/js/cleave.min.js"></script>
    <script src="<?php echo get_template_directory_uri(); ?>/js/chile.js"></script>

    <script>
    const urlBase = '<?php echo get_template_directory_uri(); ?>';
    </script>
    <script src="<?php echo get_template_directory_uri(); ?>/attemps/index.js"></script>

<?php 
include 'attemps/modal.php';
}
?>

<?php 
if($id_alumno){
?>
<!-- whatsapp --> 
<style>
@keyframes wiggle {
    0% { transform: rotate(0deg); }
   80% { transform: rotate(0deg); }
   85% { transform: rotate(25deg); }
   95% { transform: rotate(-25deg); }
  100% { transform: rotate(0deg); }
}

.enlace-whatsapp{
    width: 49px;
    height: 49px;
    position: fixed;
    z-index: 100000;
    background: #FFFFFF;
    border-radius: 50%;
    right: 20px;
    bottom: 20px;
    animation: wiggle 7.5s infinite;
}
.enlace-whatsapp:hover{
    opacity: 0.8;
    animation: none;
}
</style>

tengo algunas dudas acerca del curso B1 Evaluación Estrés Parental Fundación Mi Casa, muchas gracias

<a class="enlace-whatsapp" rel="nofollow noopener noreferrer" target="_blank" href="https://wa.me/56954214774?text=Hol@%20soy%20<?php echo $nombre_alumno; ?>,%20tengo%20algunas%20dudas%20acerca%20del%20curso%20*<?php echo get_the_title() ?>*,%20muchas%20gracias"><img src="<?php echo get_template_directory_uri(); ?>/images/whatsapp.png" alt="whatsapp" /></a>
<!-- end whatsapp -->
<?php 
}
?>