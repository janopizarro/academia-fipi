<!-- Item -->
<?php 
$fecha_inicio = json_decode(obtenerFechaEstablecida("inicio", $id_curso, $email_alumno))->fecha_inicio;
$fecha_termino = json_decode(obtenerFechaEstablecida("termino", $id_curso, $email_alumno))->fecha_termino;
$fecha_estado_actual = estadoActualFecha($fecha_inicio, $fecha_termino, "string");

$cantAccesosCursoFinalizado = cantAccesosCursoFinalizado($id_curso,getDataSession("id"));
?>

<div class="col-lg-3 col-md-6 card--fipi" style="margin-bottom: 50px;">
    <div class="dashboard-stat"
        style="<?php if($fecha_actual >= $fecha_termino && !$cantAccesosCursoFinalizado["status"]) { echo "opacity: 0.7; transition:none; transform:none"; } ?>">

        <?php 
        $fecha = get_post_meta( $id_curso, 'curso_fecha', true );
        ?>

        <!-- here -->
        <?php 
        if(($cantAccesosCursoFinalizado["status"]) || ($fecha_actual <= $fecha_termino)) { ?>
        <div class="dashboard-stat-content">
            <span>
                <a href="<?php echo get_the_permalink($id_curso) ?>"><img
                        src="<?php echo get_template_directory_uri(); ?>/images/ver-mas.svg"></a>
            </span>
        </div>
        <?php 
        }
        if($fecha_actual >= $fecha_termino && $cantAccesosCursoFinalizado["status"] && getDataSession("id")){
            echo "<p class='visualizaciones' style='display:block'><img src='".get_template_directory_uri()."/includes/informacion.png' width='16' />El curso ya terminó pero cuentas con ".$cantAccesosCursoFinalizado["n_visto"]." visualización/es más.</p>";
        }
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
        <!-- end here -->
        <?php if(get_post_meta( $id_curso, 'curso_imagen_pequena', true )){ ?>
        <img <?php if($fecha_actual >= $fecha_termino && !$cantAccesosCursoFinalizado["status"]) { ?> style="filter: grayscale(1);" <?php } ?>
            src="<?php echo get_post_meta( $id_curso, 'curso_imagen_pequena', true ) ?>">
            <?php } else {?>  
                <img <?php if($fecha_actual >= $fecha_termino && !$cantAccesosCursoFinalizado["status"]) { ?> style="filter: grayscale(1);" <?php } ?>
            src="<?php echo get_template_directory_uri(); ?>/images/imagen-en-proceso.jpg">
            <?php } ?>
    </div>

    <h2>

        <ul class="fechas_info" <?php if($fecha_actual >= $fecha_termino) { echo "style='background: #e2e2e2';"; } ?>>
            <?php echo $fecha_estado_actual; ?>
        </ul>

        <strong <?php if($fecha_actual >= $fecha_termino) { echo "style='color: #848484;'"; } ?>>
            <?php if($fecha_actual <= $fecha_termino) { ?>
            <a href="<?php echo get_the_permalink($id_curso) ?>"><?php echo get_the_title($id_curso); ?></a>
            <?php } else { echo get_the_title($id_curso); } ?>
        </strong>

    </h2>

</div>
<!-- end Item -->