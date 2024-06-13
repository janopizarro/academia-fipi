<?php 
session_start();

// session_destroy();

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

// Obtener la ID del post actual
$post_id = get_the_ID();

// Obtener la URL de la imagen destacada
$featured_image_url = get_the_post_thumbnail_url($post_id);

if($vigencia->status && !$cantAccesosCursoFinalizado["status"]){ 

	echo "<script>alert('Curso finalizó el ".$vigencia->date."');</script>"; redirect(100,'login'); 

} else {

    if(getDataSession("id")){
        // se inserta una nueva visualización
        // registraNuevaVisualizacion($id_alumno, $id_curso); // DESCOMENTAR DESPUES!!!!
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

<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/css/bootstrap.min.css" crossorigin="anonymous">

<style>
iframe {
    width: 100%;
    height: 711px;
}
/* .sc_layouts_hide_on_frontpage{display:none;} */
</style>

<!-- <div class="header-course">
    <img src="<?php echo get_post_meta( $id_curso, 'curso_imagen_grande', true ); ?>" alt="" />
</div> -->

<!-- Dashboard -->
<div id="dashboard">

    <?php 
    // require_once dirname( __FILE__ ) . '/includes/side.php';
    ?>

    <div class="dashboard-content dashboard-content--curso">

        <!-- nueva estructura -->

        <!-- <div class="title-course">
            <h1><?php echo get_the_title(); ?></h1>
        </div> -->

        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-8">

                    <?php 

                    $queryIntroCourse = $wpdb->get_results(" SELECT * FROM $tableName WHERE `id_user` = $id_alumno AND `id_curso` = $id_curso AND `tipo` = 1 AND `status` = 1 ");
                    $introFinished = count($queryIntroCourse) == 1 ? "si" : "no";
                                        
                    if($id_alumno){

                        if($introFinished == "no"){

                            require_once dirname( __FILE__ ) . '/unit/review.php';                            

                        } else {

                            ?>

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
                                        echo '<img src="'.get_stylesheet_directory_uri().'/images/finalizada_img.jpg">';

                                    } else {

                                        // INTRO DEL CURSO
                                        if(get_post_meta( $id_curso, 'curso_imagen_grande', true )){

                                            echo '<img style="width:100%" src="'.get_post_meta( $id_curso, 'curso_imagen_grande', true ).'" alt="" class="portada-imagen">';

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

                                        echo '<img src="'.get_post_meta( $id_curso, 'curso_imagen_grande', true ).'" alt="" class="portada-imagen">';

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

                                            echo '<img src="'.get_post_meta( $id_curso, 'curso_imagen_grande', true ).'" alt="" class="portada-imagen">';

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

                                        echo '<img src="'.get_post_meta( $id_curso, 'curso_imagen_grande', true ).'" alt="" class="portada-imagen">';

                                    } else {

                                        echo '<div class="embed-responsive embed-responsive-16by9" id="loadVideo">
                                            <iframe src="//player.vimeo.com/video/'.str_replace("https://vimeo.com/","",get_post_meta( $id_curso, 'curso_intro_video', true )).'?api=1&player_id=video&title=0&amp;byline=0&amp;portrait=0&amp;color=c9ff23;autoplay=1" width="640" height="360" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" id="video" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                        </div>';
                                    }

                                }

                            }

                            require_once dirname( __FILE__ ) . '/unit/content.php';	
                        
                        }

                    } else {
                        require_once dirname( __FILE__ ) . '/includes/description-course.php';
                    }
                    ?>

                    <div class="container m-0 p-0">
                        <?php 
                        if($introFinished == "no"){
                            require_once dirname( __FILE__ ) . '/includes/teacher-info-resume.php';
                        }
                        ?>
                    </div>

                    <div class="container m-0 p-0">
                        <?php 
                        require_once dirname( __FILE__ ) . '/includes/contact-us.php';
                        ?>
                    </div>

                </div>
                <div class="col-sm-4">

                    <?php
                    if($id_alumno){

                        if($introFinished == "no"){

                            require_once dirname( __FILE__ ) . '/unit/content.php';                            

                        } else {

                            ?>

                            <h4 style="font-size: 15px;color: #053255;font-weight: bold;">Progreso del curso:</h4>

                            <figure>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-success progress-bar-striped active" style="width: 10%;"></div>
                                </div>
                            </figure>

                            <!-- unidad_avance_terminada -->

                            <div class="unidades_avance">
                                <div class="unidad_avance"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/check.svg" /><p>Unidad 01</p></div>
                                <div class="unidad_avance"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/check.svg" /><p>Unidad 02</p></div>
                                <div class="unidad_avance"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/check.svg" /><p>Unidad 03</p></div>
                                <div class="unidad_avance"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/check.svg" /><p>Unidad 04</p></div>
                            </div>


                            <?php 
    
                            require_once dirname( __FILE__ ) . '/unit/review.php';

                            if($introFinished == "si"){
                                require_once dirname( __FILE__ ) . '/includes/teacher-info-resume.php';
                            }
                        
                        }
                    
                    } else {

                        require_once dirname( __FILE__ ) . '/includes/features.php';

                        require_once dirname( __FILE__ ) . '/includes/form-flow.php';

                    }

                    ?>

                </div>
            </div>
        </div>

        <!-- end nueva estructura -->

        <!-- Titlebar -->
        <!-- <div id="titlebar">
            <div class="row">
                <div class="col-md-12">
                    <?php 

                    // if(count($consulta) > 0){

                    //     echo '<h1 style="width:100%; margin-bottom: 20px;">Hola '.$nombre_alumno.', bienvenid@ al Curso</h1>';

                    //     /* info docente */
                    //     require_once dirname( __FILE__ ) . '/includes/teacher-info-resume.php';
                    //     /* end info docente */

                    // } else {

                    //     /* info docente */
                    //     require_once dirname( __FILE__ ) . '/includes/teacher-info-resume.php';
                    //     /* end info docente */

                    // }
                    

                    // $fecha_inicio = json_decode(obtenerFechaEstablecida("inicio", $id_curso, $email_alumno))->fecha_inicio;
                    // $fecha_termino = json_decode(obtenerFechaEstablecida("termino", $id_curso, $email_alumno))->fecha_termino;
                    // $fecha_estado_actual = estadoActualFecha($fecha_inicio, $fecha_termino, "string");

                    // echo "<ul class='fechas_info fechas_info__single'>".$fecha_estado_actual."</ul>";

                    ?>
                </div>
            </div>
        </div> -->

        <div class="row">

            <div class="col-lg-7 col-md-12">
                <div class="dashboard-list-box with-icons margin-top-20">
                    <div id="getVid"></div>

                    <?php 
                    
                    // if(getUnitsCourse($id_curso)){ 

                    //     if($id_alumno){

                    //         if(haveActivityInCourse( $id_alumno, $id_curso ) && !haveFinishedCourse( $id_alumno, $id_curso )){

                    //             // ACTIVIDAD EN EL CURSO
                    //             echo '<div class="embed-responsive embed-responsive-16by9" id="loadVideo">
                    //                     <iframe id="videoPlayer" allowfullscreen></iframe>
                    //                 </div>';

                    //         } elseif (haveFinishedCourse( $id_alumno, $id_curso )){

                    //             // CURSO TERMINADO
                    //             echo '<img src="'.get_stylesheet_directory_uri().'/images/finalizada_img.jpg">';

                    //         } else {

                    //             // INTRO DEL CURSO
                    //             if(get_post_meta( $id_curso, 'curso_imagen_grande', true )){

                    //                 echo '<img style="width:100%" src="'.get_post_meta( $id_curso, 'curso_imagen_grande', true ).'" alt="" class="portada-imagen">';

                    //             } else {

                    //                 /* obtener el video intro */
                    //                 preg_match('/src="([^"]+)"/', get_post_meta( $id_curso, 'curso_intro_video_iframe', true), $match);
                    //                 $url = $match[1];
                                
                    //                 $js = "    
                    //                     <script>
                    //                     let videoPlayer = document.getElementById('videoPlayer');
                    //                     let url_string = '".$url."';
                    //                     let adsURL = url_string+'&api=1&player_id=video&title=0&amp;byline=0&amp;portrait=0&amp;color=c9ff23;autoplay=1';
                    //                     videoPlayer.src = adsURL;
                    //                     </script>
                    //                 ";

                    //                 echo $js;
                    //                 /* end obtener el video intro */

                    //                 echo '<div class="embed-responsive embed-responsive-16by9" id="loadVideo">
                    //                         <iframe id="videoPlayer" allowfullscreen></iframe>		
                    //                     </div>';
                    //                     // <iframe src="//player.vimeo.com/video/'.str_replace("https://vimeo.com/","",get_post_meta( $id_curso, 'curso_intro_video', true )).'?api=1&player_id=video&title=0&amp;byline=0&amp;portrait=0&amp;color=c9ff23;autoplay=1" width="640" height="360" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" id="video" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                    //             }

                    //         }

                    //     } else {

                    //         // INTRO DEL CURSO
                    //         if(get_post_meta( $id_curso, 'curso_imagen_grande', true )){

                    //             echo '<img src="'.get_post_meta( $id_curso, 'curso_imagen_grande', true ).'" alt="" class="portada-imagen">';

                    //         } else {

                    //             echo '<div class="embed-responsive embed-responsive-16by9" id="loadVideo">
                    //                     <iframe src="//player.vimeo.com/video/'.str_replace("https://vimeo.com/","",get_post_meta( $id_curso, 'curso_intro_video', true )).'?api=1&player_id=video&title=0&amp;byline=0&amp;portrait=0&amp;color=c9ff23;autoplay=1" width="640" height="360" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" id="video" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                    //                 </div>';
                    //         }

                    //     }

                    // } else {

                    //     if(introFinish($id_alumno, $id_curso)){

                    //         if(getPercentaje($id_curso, getDataSession("id")) == 100){

                    //             if(get_post_meta( $id_curso, 'curso_imagen_grande', true )){

                    //                 echo '<img src="'.get_post_meta( $id_curso, 'curso_imagen_grande', true ).'" alt="" class="portada-imagen">';
    
                    //             } else {
    
                    //                 echo '<div class="embed-responsive embed-responsive-16by9" id="loadVideo">
                    //                         <iframe id="videoPlayer" allowfullscreen></iframe>		
                    //                     </div>';
                    //             }
                                
                    //         } else {

                    //             echo '<div class="embed-responsive embed-responsive-16by9" id="loadVideo">
                    //                 <iframe id="videoPlayer" allowfullscreen></iframe>		
                    //             </div>';

                    //         }

                    //     } else {

                    //         if(get_post_meta( $id_curso, 'curso_imagen_grande', true )){

                    //             echo '<img src="'.get_post_meta( $id_curso, 'curso_imagen_grande', true ).'" alt="" class="portada-imagen">';

                    //         } else {

                    //             echo '<div class="embed-responsive embed-responsive-16by9" id="loadVideo">
                    //                 <iframe src="//player.vimeo.com/video/'.str_replace("https://vimeo.com/","",get_post_meta( $id_curso, 'curso_intro_video', true )).'?api=1&player_id=video&title=0&amp;byline=0&amp;portrait=0&amp;color=c9ff23;autoplay=1" width="640" height="360" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" id="video" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                    //             </div>';
                    //         }

                    //     }

                    // }
     
                    ?>
                </div>

                <?php 			
     
                if(count($consulta) > 0){
                
                    /* * REVIEW COURSE * */
                    if(getUnitsCourse($id_curso)){
                
                        // require_once dirname( __FILE__ ) . '/unit/review.php';
                        // require_once dirname( __FILE__ ) . '/includes/teacher-info.php';
            
                    } else {
                
                        require_once dirname( __FILE__ ) . '/includes/review-course.php';							
                        // require_once dirname( __FILE__ ) . '/includes/teacher-info.php';
                        
                    }

                } else {

                    /* * TEACHER INFO * */
                    // require_once dirname( __FILE__ ) . '/includes/contact-us.php';

                }
                
                ?>

            </div>

            <?php
            if(count($consulta) > 0){
            ?>
   
            <div class="col-lg-5 col-md-12">
                <div class="dashboard-list-box invoices margin-top-20">

                    <?php 
                    /* * DESCRIPTION COURSE SHORT * */
                    // require_once dirname( __FILE__ ) . '/includes/description-course--short.php';

                    /* * TEACHER INFO * */
                    // require_once dirname( __FILE__ ) . '/includes/teacher-info.php';

                    if(getUnitsCourse($id_curso)){

                        /* * BIBLIOGRAPHY * */
                        require_once dirname( __FILE__ ) . '/includes/bibliography.php';

                    }

                    $unidades = getUnitsCourse($id_curso);
                    // UNIT STATUS · SE VERIFICA SI TIENE UNIDADES DINÁMICAS
                    if(getUnitsCourse($id_curso)){

                        if(!haveFinishedCourse( $id_alumno, $id_curso )){ ?>

                            <!-- <h4 style="font-size: 14px; color: #797977;">Progreso del curso <strong id="porcentajeVal" style="color: purple; font-size: 16px; margin-top: 10px; font-family: system-ui;"><?php echo getPercentageDinamic($id_curso, getDataSession("id")); ?>%</strong></h4> -->
                            <!-- <h4 style="font-size: 14px; color: #797977;">Progreso del curso <strong id="porcentajeVal" style="color: purple; font-size: 16px; margin-top: 10px; font-family: system-ui;">10%</strong></h4> -->
                            <!-- <h4 style="font-size: 15px;color: #053255;font-weight: bold;">Progreso del curso:</h4>

                            <figure>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-success progress-bar-striped active" style="width: 80%;"></div>
                                </div>
                            </figure>

                            <div class="unidades_avance">
                                <div class="unidad_avance unidad_avance_terminada"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/check.svg" /><p>Unidad 01</p></div>
                                <div class="unidad_avance unidad_avance_terminada"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/check.svg" /><p>Unidad 02</p></div>
                                <div class="unidad_avance unidad_avance_terminada"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/check.svg" /><p>Unidad 03</p></div>
                                <div class="unidad_avance"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/check.svg" /><p>Unidad 04</p></div>
                            </div> -->

                        <?php 
							
                        } else {
      
                            echo '<h4 style="color: purple; font-size: 16px; margin-top: 0px; font-family: system-ui;">Curso Finalizado</h4>';

                        }

                        // require_once dirname( __FILE__ ) . '/unit/content.php';	

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

            <?php 
		
				} else {

				?>

            <div class="col-lg-5 col-md-12">

                <?php 
                /* * description course * */
                require_once dirname( __FILE__ ) . '/includes/description-course.php';

                // if(get_post_meta( $id_curso, 'curso_monto', true )){
                    /* * form flow * */
                    require_once dirname( __FILE__ ) . '/includes/form-flow.php';
                // }
                ?>

            </div>

            <?php 
				
            }

            ?>

        </div>

            <?php
			
    get_footer();
	?>

    <script src="<?php echo get_stylesheet_directory_uri(); ?>/js/pagar-flow.js"></script>
    <script src="<?php echo get_stylesheet_directory_uri(); ?>/js/cleave.min.js"></script>
    <script src="<?php echo get_stylesheet_directory_uri(); ?>/js/chile.js"></script>

    <script>
    const urlBase = '<?php echo get_stylesheet_directory_uri(); ?>';
    </script>
    <script src="<?php echo get_stylesheet_directory_uri(); ?>/attemps/index.js"></script>

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

<a class="enlace-whatsapp" rel="nofollow noopener noreferrer" target="_blank" href="https://wa.me/56954214774?text=Hol@%20soy%20<?php echo $nombre_alumno; ?>,%20tengo%20algunas%20dudas%20acerca%20del%20curso%20*<?php echo get_the_title() ?>*,%20muchas%20gracias"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/whatsapp.png" alt="whatsapp" /></a>
<!-- end whatsapp -->
<?php 
}
?>