<?php 
$fecha_inicio = json_decode(obtenerFechaEstablecida("inicio", $id_curso, $email_alumno))->fecha_inicio;
$fecha_termino = json_decode(obtenerFechaEstablecida("termino", $id_curso, $email_alumno))->fecha_termino;
$fecha_estado_actual = estadoActualFecha($fecha_inicio, $fecha_termino, "string");

$cantAccesosCursoFinalizado = cantAccesosCursoFinalizado($id_curso,getDataSession("id"));
?>

<div class="dashboard-stat"
        style="<?php if($fecha_actual >= $fecha_termino && !$cantAccesosCursoFinalizado["status"]) { echo "opacity: 0.7; transition:none; transform:none"; } ?>">

        <?php 
        $fecha = get_post_meta( $id_curso, 'curso_fecha', true );
        ?>

        <?php 
        if(isset($_SESSION['user_fipi'])) {

            if(count(getUnitsCourse($id_curso)) > 0){

                if(getPercentajeNew($id_curso, getDataSession("id")) && !$cantAccesosCursoFinalizado["status"]) {
                        
                    if(getPercentajeNew($id_curso, getDataSession("id")) === 100){ 

                        echo "<p class='messageCourse'>Finalizado</p>";

                    } else {

                        if(getPercentajeNew($id_curso, getDataSession("id")) > 0){
        
                            echo "<p class='messageCourse'>Avance: ".getPercentajeNew($id_curso, getDataSession("id"))."%</p>";
        
                        }

                    }
        
                }

            } else {

                if(getPercentaje($id_curso, getDataSession("id")) && !$cantAccesosCursoFinalizado["status"]) {
                        
                    if(getPercentaje($id_curso, getDataSession("id")) == 100){ 

                        echo "<p class='messageCourse'>Finalizado</p>";

                    } else {

                        if(getPercentaje($id_curso, getDataSession("id")) > 0){
            
                            echo "<p class='messageCourse'>Avance: ".getPercentaje($id_curso, getDataSession("id"))."%</p>";
            
                        }

                    }
            
                }

            }

        }
        ?>

        <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/fipi-te-apana-02.svg" class="card-fipi-apana" />

        <!-- end here -->
        <?php if(get_post_meta( $id_curso, 'curso_imagen_pequena', true )){ ?>
        <img <?php if($fecha_actual >= $fecha_termino && !$cantAccesosCursoFinalizado["status"]) { ?> style="filter: grayscale(1);" <?php } ?>
            src="<?php echo get_post_meta( $id_curso, 'curso_imagen_pequena', true ) ?>">
            <?php } else {?>  
                <img <?php if($fecha_actual >= $fecha_termino && !$cantAccesosCursoFinalizado["status"]) { ?> style="filter: grayscale(1);" <?php } ?>
            src="<?php echo get_stylesheet_directory_uri(); ?>/images/imagen-en-proceso.jpg">
            <?php } ?>
    </div>

    <div class="item-new--inset">

        <h5>
            <?php 
            // if($fecha_actual <= $fecha_termino) {
                 ?>
            <a href="<?php echo get_the_permalink($id_curso) ?>"><?php echo get_the_title($id_curso); ?></a>
            <?php 
            // } else { echo get_the_title($id_curso); }
            ?>
        </h5>

        <?php 
        if($modalidd_){
            foreach($modalidd_ as $res){
                echo '<p>'.$res.'</p>';
            }
        } 
        ?>


        <?php 
        if(($cantAccesosCursoFinalizado["status"]) || ($fecha_actual <= $fecha_termino)) { ?>
        <div class="buttons-card">
        <a href="<?php echo get_the_permalink($id_curso) ?>" id="sc_button_2095599388" class="card-button sc_button color_style_dark sc_button_default sc_button_size_normal sc_button_icon_left sc_button_hover_slide_left sc_button_hover_style_dark"><span class="sc_button_text"><span class="sc_button_title">Ver detalle</span></span><!-- /.sc_button_text --></a>
        <a href="<?php echo get_the_permalink($id_curso) ?>" class="add-cart"><img src='<?php echo get_stylesheet_directory_uri(); ?>/images/cart-add.svg' width='24' /></a>
        </div>
        <?php } ?>

        <?php
        if($fecha_actual >= $fecha_termino && $cantAccesosCursoFinalizado["status"] && getDataSession("id")){ ?>
            <p class='visualizaciones' style='display:block'><img src='<?php echo get_stylesheet_directory_uri(); ?>/includes/informacion.png' width='16' /> El curso ya terminó pero cuentas con <?php echo $cantAccesosCursoFinalizado["n_visto"]; ?> visualización/es más.</p>
        <?php } ?>

</div>
