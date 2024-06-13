<?php

// variables principales
$id_course = get_the_ID();
$id_user = getDataSession("id");

$tableName = $wpdb->prefix . "unidades_dinamico";

$unitActual = 0;

// SE CONSULTA A LA BASE DE DATOS EN QUE UNIDAD ESTÁ ACTUALMENTE EL ALUMNO
$queryIntroCourse = $wpdb->get_results(" SELECT * FROM $tableName WHERE `id_user` = $id_user AND `id_curso` = $id_course AND `tipo` = 1 AND `status` = 1 ");
$queryStatusUnits = $wpdb->get_results(" SELECT * FROM $tableName WHERE `id_user` = $id_user AND `id_curso` = $id_course AND `tipo` = 2 ");

// SI LA INTRO YA ESTÁ COMPLETA ENTREGA LA UNIDAD ACTUAL
if(count($queryIntroCourse) > 0){

    if(count($queryStatusUnits) > 0){

        $unitActual = count($queryStatusUnits) + 1;

    } else {

        $unitActual = 1;

    }

}
?>

<script>
/*
 * VERIFICAR LIMITE DE ALTERNATIVAS PARA SELECCIONAR * */
function verificarLimiteAlertnativa(limit, grupoClase) {
    var helperGroup = document.querySelector('.helper_' + grupoClase + '');
    var checkboxgroup = document.querySelectorAll('.' + grupoClase + '');
    helperGroup.style.display = "none";
    for (var i = 0; i < checkboxgroup.length; i++) {
        checkboxgroup[i].onclick = function() {
            var checkedcount = 0;
            for (var i = 0; i < checkboxgroup.length; i++) {
                checkedcount += (checkboxgroup[i].checked) ? 1 : 0;
            }
            if (checkedcount > limit) {
                helperGroup.style.display = "inline-block";
                helperGroup.textContent = `Seleccionaste el máximo (${limit}) de alternativas`;
                this.checked = false;
            } else {
                if (helperGroup.textContent != "") {
                    helperGroup.style.display = "none";
                    helperGroup.textContent = "";
                }
            }
        }
    }
}
</script>

<?php 

if(haveFinishedCourse( $id_user, $id_curso )){
    echo "<div style='background: #f9f0cf;padding: 2px;text-align: center;'>Puedes volver a visualizar el video 5 veces. <small style='display:block;margin-top: -7px;'>Presionando los botones color lila</small></div>"; 
} 

// SE VERIFICA SI TIENE UNIDADES EL CURSO
if(count(getUnitsCourse($id_course)) > 0){

    $html = '';

    // INTRO CURSO
    require_once dirname( __FILE__ ) . '/content-intro.php';

    $u = 0;
    
    foreach(getUnitsCourse($id_course) as $res){ $u++;

        // ID DE LA UNIDAD
        $ID = $res->ID;

        // SE RESCATA EL VIDEO DE LA UNIDAD ACTUAL
        if($unitActual == $u){
            echo getVideoUnit($ID);
        }

        // POR DEFECTO TODAS SON DISABLED
        // $statusUnit = "data-toggle='collapse' data-target='#unidad_0".$u."' aria-expanded='false' aria-controls='unidad_0".$u."'";

        $showFirst = "";

        if(haveFinishedCourse( $id_user, $id_curso )){

            $showUnit = "";
            $statusUnit = "data-toggle='collapse' data-target='#unidad_0".$u."' aria-expanded='false' aria-controls='unidad_0".$u."'";
            $statusUnitCollapsed = "";

            if($u == 1){
                $showFirst = "show";
            } else {
                $showFirst = "";
            }

        } else {

            $statusUnit = "disabled";
            $statusUnitCollapsed = "collapsed";

            if($unitActual == $u){ 
                $showUnit = "show"; 
            } else { 
                $showUnit = "";
            }

        }

        $html .= '<div class="card">
                    <div class="card-header" id="h_unidad_0'.$u.'">
                        <h5 class="mb-0">
                            <button class="btn btn-link '.$statusUnitCollapsed.' unidad_0'.$u.'" '.$statusUnit.'>
                                Unidad 0'.$u.'';

                                if(haveFinishedCourse( $id_user, $id_curso )){
                                    $html .= '<button class="attempVideo" data-n-unidad="'.$u.'" data-type="2" data-ids="'.$ID.'-'.$id_curso.'-'.$id_user.'" style="font-size: 12px;color: #4f2acb;background: #f3c7ff;padding: 5px 11px;padding-bottom: 4px;border-radius: 23px;margin-left: 6px;position: relative;top: -2px; border: none;">Ver video nuevamente</button>';
                                }

        $html .= '</button>
                        </h5>
                    </div>
                    <div id="unidad_0'.$u.'" class="collapse '.$showUnit.' '.$showFirst.'" aria-labelledby="h_unidad_0'.$u.'" data-parent="#accordion">
                        <div class="card-body">';

        // PREGUNTAS & RESPUESTAS
        $question_unit = get_post_meta( $ID, 'preguntas_unidad', true);

        // SE COMIENZA A ARMAR EL HTML
        $html .= '<form id="form_etp_0'.$u.'" method="post">';

        // DOCUMENTOS DE RESEÑA
        $docs = get_post_meta($ID, 'unidad_resena_docs', true);
    
        if($docs && count($docs) > 0){
    
            $html .= '<strong>Lectura obligatoria:</strong><small style="display: block; font-size: 14px; padding-left: 30px;">'.get_post_meta($ID, 'adicional_lectura_obligatoria', true).'</small><nav style="display: flex; padding-left: 18px;">';
    
            foreach($docs as $doc){
                $html .= '<li style="list-style:none; display:block; padding: 10px;"><a href="'.$doc.'" target="_blank" rel="noopener noreferrer"><img style="width:22px;" src="'.get_stylesheet_directory_uri().'/images/doc.png" /></a></li>';
            }
    
            $html .= '</nav>';
    
        }

        // PREGUNTAS
        $html .= '<ul class="pregunta"><strong style="color: #BF2871;">Evaluación formativa unidad 0'.$u.'</strong>';

        $x = 0;

        foreach ($question_unit as $key => $question) { $x++;
    
            $i = $x - 1;
    
            // PREGUNTA
            $question_ = esc_html($question['pregunta_unidad']);
    
            // ALTERNATiVAS 
            $alternatives_ = $question['alternativa_unidad'];
    
            // ALTERNATIVAS CORRECTAS 
            $correct_alternative = count($question['alternativa_correcta_unidad']);
    
            // VERIFICAR SI LA UNIDAD YA ESTÁ LISTA
            if(!verifyUnitEndNew($ID, $id_user, $id_course)){
                $classQuestion = checkStatusQuestionNew($ID, $x, $id_user, $id_course, $i, 'question');
            } else {
                $classQuestion = "";
            }
    
            // echo $ID."-".$x."-".$id_user."-".$id_course."-".$x."<br/>";

            $checkboxStatus = checkStatusQuestionNew($ID, $x, $id_user, $id_course, $i, 'checkbox');
            $selectedAlternative = checkStatusQuestionNew($ID, $x, $id_user, $id_course, $i, 'selected-alternative');
            $incorrectAlternative = checkStatusQuestionNew($ID, $x, $id_user, $id_course, $i, 'incorrect-alternative');
            $message = checkStatusQuestionNew($ID, $x, $id_user, $id_course, $i, 'message');
    
            if($message != ""){
                $helperClass = "helper_visible";
            } else {
                $helperClass = "";
            }
            
            $html .= '<li class="invoices--flex question_unidad_'.$u.'_'.$x.' '.$classQuestion.'"><span class="question_">'.$question_.'</span> <b class="helper_grupo helper_grupo--info helper_grupo_unidad_'.$u.'_'.$x.' '.$helperClass.'" style="display: none;">'.$message.'</b></li>';
    
            $html .= '<ul>';
    
            $i = 0;
    
            foreach($alternatives_ as $alternative){ $i++;
    
                if(strpos($selectedAlternative, $alternative) !== false){

                    $checked_ = "checked";
                    $checked_style = "color: green";

                } else{

                    $checked_ = "";
                    $checked_style = "";

                }
    
                if(strpos($incorrectAlternative, $alternative) !== false){

                    $checked_err = "checked";
                    $checked_err_style = "color: #e42626";

                } else{

                    $checked_err = "";
                    $checked_err_style = "";

                }
    
                if($checked_err_style != ""){

                    $checked_style_ = $checked_err_style;

                } else {

                    $checked_style_ = $checked_style;

                }
    
                $html .= '<li>';
                
                    $html .= '<input type="checkbox" '.$checked_.' '.$checked_err.' id="alernativa_'.$i.'_preg_'.$x.'_unidad_'.$u.'" class="grupo_unidad_'.$u.'_'.$x.'" name="alternativa_seleccionada_'.$x.'[]" value="'.$alternative.'" style="width: 20px; box-shadow: none; height: 15px; '.$checkboxStatus.'">';
    
                    $html .= '<label for="alernativa_'.$i.'_preg_'.$x.'_unidad_'.$u.'" style="'.$checkboxStatus.' '.$checked_style_.'"><span style="background: #BF2871;min-width: 10px;min-height: 10px;display: inline-block;text-align: center;width: 25px;height: 25px;border-radius: 34px;color: #FFFFFF;padding-top: 0px;">'.letras()[$i]."</span> ".$alternative.'</label>';
    
                $html .= '</li>';
    
            }
   
            
            

            $html .= '</ul>';
    
            $html .= '<script>verificarLimiteAlertnativa('.$correct_alternative.',"grupo_unidad_'.$u.'_'.$x.'");</script>';
            $html .= '<input type="hidden" class="cantMinima_unidad_'.$u.'_'.$x.'" value="'.$correct_alternative.'">';
            $html .= '<input type="hidden" name="preg_unidad_'.$u.'[]" value="'.$question_.'">';
        
        }
    
        $html .= '</ul>';
        // END PREGUNTAS

        // VERIFICAR ESTADO DE UNIDAD
        if(!haveFinishedCourse( $id_user, $id_curso )){

            if(get_post_meta($id_course, 'curso_monto', true)){

                $disabled = "disabled";
                $style_btn = "";

            } else {

                $disabled = "no-disabled";
                $style_btn = "<style>.finalizar_unidad{ opacity: 1 !important; pointer-events: auto !important; }</style>";

            }

            echo $style_btn;

            $html .= '<button class="button finalizar_unidad finalizar_unidad_'.$u.'" type="button" data-nextunit="'.getNext($ID, $id_course).'" data-unit="'.$ID.'" data-id="'.$u.'" data-cant="'.count($question_unit).'" style="font-size: 15px; margin: 25px;">Finalizar Unidad</button>';
    
        }
    
        $html .= '<p id="load_unit_0'.$u.'" style="display:none; font-size: 13px; margin: 10px; color: #3ca4ce; font-family: inherit;"><img src="'.get_stylesheet_directory_uri().'/images/loading.svg" style="width: 20px; margin-right: 7px;"/> Actualizando estado...</p>';
    
        $html .= '
            <input type="hidden" class="id_user" value="'.$id_user.'">
            <input type="hidden" class="course_live" value="'.@$disabled.'">
            <input type="hidden" class="id_curso" value="'.$id_course.'">
            <input type="hidden" class="id_unidad" value="'.$ID.'">
            <input type="hidden" class="template" value="'.get_stylesheet_directory_uri().'">
        ';
    
        $html .= '</form>';
    
        $docs_apoyo = get_post_meta($ID, 'unidad_resena_material_apoyo', true);
        $docs_apoyo_docs = get_post_meta($ID, 'unidad_resena_material_apoyo_docs', true);

        if($docs_apoyo){

            if(count($docs_apoyo) > 0 || count($docs_apoyo_docs) > 0){

                $html .= '<strong style="padding-left: 30px; padding-top: 10px; border-top: 1px solid #dedede; margin-top: 15px; display: block;">Material de apoyo:</strong><small style="display: block; font-size: 14px; padding-left: 30px;">'.get_post_meta($ID, 'adicional_material_apoyo', true).'</small><nav style="display: flex; padding-left: 18px;">';
    
                if($docs_apoyo && count($docs_apoyo) > 0){
        
                    foreach($docs_apoyo as $doc_apoyo){
                        $html .= '<li style="list-style:none; display: block; padding: 10px;"><a href="'.$doc_apoyo.'" target="_blank" rel="noopener noreferrer"><img style="width:22px;" src="'.get_stylesheet_directory_uri().'/images/doc_apoyo.png" /></a></li>';
                    }
        
                }
        
                if($docs_apoyo_docs && count($docs_apoyo_docs) > 0){
        
                    foreach($docs_apoyo_docs as $doc){
                        $html .= '<li style="list-style:none; display:block; padding: 10px;"><a href="'.$doc.'" target="_blank" rel="noopener noreferrer"><img style="width:22px;" src="'.get_stylesheet_directory_uri().'/images/doc.png" /></a></li>';
                    }
        
                }
        
                $html .= '</nav>';
        
            }

        }
    
        $html .= '</div>
                </div>
            </div>
            
            <div class="card" id="f_course">
                <p id="load_finish" style="display:none; font-size: 13px; margin: 10px; color: #3ca4ce; font-family: inherit;"><img src="'.get_stylesheet_directory_uri().'/images/loading.svg" style="width: 20px; margin-right: 7px;"/> Actualizando estado...</p>
            </div>';
    
    }

    echo $html;

    if(haveFinishedCourse( $id_user, $id_curso )){ ?>

<div class="card">
    <div class="card-header" id="h_unidad_dudas">
        <h5 class="mb-0">
            <button class="btn btn-link unidad_dudas" data-toggle="collapse" data-target="#unidad_dudas"
                aria-expanded="false" aria-controls="unidad_dudas">
                Dudas / Comentario
            </button>
        </h5>
    </div>
    <div id="unidad_dudas" class="collapse" aria-labelledby="h_unidad_dudas" data-parent="#accordion">
        <div class="card-body">
            <p>Hola <?php echo getDataSession("nombre"); ?>, si quedaste con alguna duda especifica del curso y
                necesitas apoyo, contáctate con el equipo de la academia Fipi.</p>

            <!-- Form -->
            <div class="col-lg-6 col-md-12" style="padding: 40px 0px;">
                <div class="dashboard-list-box margin-top-0" style="box-shadow: none; margin: 0px;">
                    <div class="dashboard-list-box-static" style="box-shadow: none; padding: 0px;">

                        <form action="" id="form_duda">

                            <div class="my-profile">
                                <input type="hidden" name="nombre" id="nombre"
                                    value="<?php echo getDataSession("nombre"); ?>">
                                <input type="hidden" name="email" id="email"
                                    value="<?php echo getDataSession("email"); ?>">
                                <input type="hidden" name="telefono" id="telefono"
                                    value="<?php echo get_user_meta(getDataSession("id"), 'user_telefono' , true ); ?>">
                                <input type="hidden" name="curso" id="curso" value="<?php echo the_title(); ?>">

                                <label class="margin-top-0">Duda/Comentario</label>
                                <textarea id="duda_comentario" name="duda_comentario"
                                    placeholder="Dejanos acá tus dudas o comentarios.."></textarea>

                                <div class="status_message"></div>

                                <button class="button margin-top-15" type="button" id="enviar_duda">Enviar</button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>

            <style>
            .status_message p {
                padding: 7px;
                font-size: 13px;
                border-radius: 5px;
            }

            .warning {
                background: #d4c516;
                color: #6f4800;
            }

            .error {
                background: #d41616;
                color: #ffd0d0;
            }

            .info {
                background: #45c8e6;
                color: #0f5469;
            }

            .success {
                background: #a1bf42;
                color: #2d4a00;
            }
            </style>

            <script>
            document.getElementById("enviar_duda").addEventListener("click", function() {

                let duda_comentario = document.getElementById("duda_comentario");

                if (duda_comentario.value === "") {
                    alert("Por favor ingresa una duda/comentario para continuar");
                    return false;
                }

                const url = '<?php echo get_stylesheet_directory_uri(); ?>';

                const data = new FormData(document.getElementById('form_duda'));
                fetch(url + '/unit/dudas--ajax.php', {
                        method: 'POST',
                        body: data
                    })
                    .then(function(response) {
                        if (response.ok) {
                            return response.text()
                        } else {
                            document.querySelector(".status_message").innerHTML =
                                "<p class='error'>Problema en la llamada ajax!</p>";
                        }
                    })
                    .then(function(res) {

                        respuesta = JSON.parse(res);

                        let status = respuesta.status;
                        let message = respuesta.message;
                        let class_message = respuesta.class_message;

                        document.querySelector(".status_message").innerHTML = "<p class='" + class_message +
                            "'>" + message + "</p>";

                    })
                    .catch(function(err) {
                        console.log(err);
                    });

            });
            </script>
        </div>
    </div>
</div>

<!-- #BF2871 -->

<!-- NUEVO FEATURE ENCUESTA -->
<?php require_once dirname( __FILE__ ) . '../../includes/encuesta.php'; ?>
<!-- END NUEVO FEATURE ENCUESTA -->

<?php }

    // if(haveFinishedCourse( $id_user, $id_curso)){
    //     if(!haveEvaluation( $id_curso, $id_user )){
        ?>

<!-- <div class="card">
    <div class="card-header" id="h_unidad_evaluacion">
        <h5 class="mb-0">
            <button class="btn btn-link unidad_evaluacion" data-toggle="collapse" data-target="#unidad_evaluacion"
                aria-expanded="false" aria-controls="unidad_evaluacion">
                Evalua el curso realizado
            </button>
        </h5>
    </div>
    <div id="unidad_evaluacion" class="collapse" aria-labelledby="h_unidad_evaluacion" data-parent="#accordion">
        <div class="card-body">
            <p>En una escala del 1 al 5, donde 1 es muy malo y 5 excelente, que puntaje le darías al curso realizado?
            </p>

            <div class="col-lg-6 col-md-12" style="padding: 40px 0px;">
                <div class="dashboard-list-box margin-top-0" style="box-shadow: none; margin: 0px;">
                    <div class="dashboard-list-box-static" style="box-shadow: none; padding: 0px;">

                        <form action="" id="form_evaluacion">

                            <div class="my-profile">
                                <input type="hidden" name="id_curso" id="id_curso" value="<?php echo $id_curso ?>">
                                <input type="hidden" name="id_user" id="id_user"
                                    value="<?php echo getDataSession("id"); ?>">
                                <input type="hidden" name="email" id="email"
                                    value="<?php echo getDataSession("email"); ?>">
                                <input type="hidden" name="curso" id="curso" value="<?php echo the_title(); ?>">

                                <label class="margin-top-0">Evaluación</label>
                                <select name="evaluacion" id="evaluacion">
                                    <option value="">Selecciona</option>
                                    <option value="uno">1 estrella</option>
                                    <option value="dos">2 estrellas</option>
                                    <option value="tres">3 estrellas</option>
                                    <option value="cuatro">4 estrellas</option>
                                    <option value="cinco">5 estrellas</option>
                                </select>

                                <div class="status_message_ev"></div>

                                <button class="button margin-top-15" type="button" id="enviar_evaluacion">Enviar
                                    evaluación</button>


                            </div>

                        </form>

                    </div>
                </div>
            </div>

            <style>
            .status_message_ev p {
                padding: 7px;
                font-size: 13px;
                border-radius: 5px;
            }

            .warning {
                background: #d4c516;
                color: #6f4800;
            }

            .error {
                background: #d41616;
                color: #ffd0d0;
            }

            .info {
                background: #45c8e6;
                color: #0f5469;
            }

            .success {
                background: #a1bf42;
                color: #2d4a00;
            }
            </style>

            <script>
            document.getElementById("enviar_evaluacion").addEventListener("click", function() {

                let evaluacion = document.getElementById("evaluacion");

                if (evaluacion.value === "") {
                    alert("Por favor ingresa una evaluación para continuar");
                    return false;
                }

                const url = '<?php echo get_stylesheet_directory_uri(); ?>';

                const data = new FormData(document.getElementById('form_evaluacion'));
                fetch(url + '/unit/evaluacion--ajax.php', {
                        method: 'POST',
                        body: data
                    })
                    .then(function(response) {
                        if (response.ok) {
                            return response.text()
                        } else {
                            document.querySelector(".status_message_ev").innerHTML =
                                "<p class='error'>Problema en la llamada ajax!</p>";
                        }
                    })
                    .then(function(res) {

                        respuesta = JSON.parse(res);

                        let status = respuesta.status;
                        let message = respuesta.message;
                        let class_message = respuesta.class_message;

                        document.querySelector(".status_message_ev").innerHTML = "<p class='" +
                            class_message + "'>" + message + "</p>";

                        if (status) {
                            setTimeout(function() {
                                location.reload();
                            }, 600);
                        }

                    })
                    .catch(function(err) {
                        console.log(err);
                    });

            });
            </script>
        </div>
    </div>
</div> -->

<?php
    //     }
    // }

    echo '
    
    <script src="'.get_stylesheet_directory_uri().'/js/sweetalert2.all.min.js"></script>
    <script src="https://player.vimeo.com/api/player.js"></script>
    <script src="'.get_stylesheet_directory_uri().'/unit/content.js"></script>

    ';

} else {

    echo "<small>El curso ".get_the_title($id_course)." no tiene unidades dinámicas creadas.</small>";

}









































// // verificar unidades
// $args = array(
// 	'numberposts' => -1,
// 	'post_type'   => 'unidad',
// 	'meta_query'  => array(
// 		array(
// 			'key'   => 'unidad_curso',
// 			'value' => $cursoId,
// 		)
// 	)
// );

// $units = get_posts($args);

// if(count($units) > 0){

//     echo "<small>El curso ".get_the_title($cursoId)." tiene las siguientes unidades.</small>";

//     $x = 0;

//     foreach($units as $res){ $x++;

//         echo "UNIDAD N".$x."<br>";

//         $ID = $res->ID;

//         $unidad_resena = get_post_meta( $ID, 'unidad_resena', true );
//         $unidad_resena_video = get_post_meta( $ID, 'unidad_resena_video', true );
//         $unidad_resena_docs = get_post_meta( $ID, 'unidad_resena_docs', true ); // array
//         $adicional_lectura_obligatoria = get_post_meta( $ID, 'adicional_lectura_obligatoria', true );
//         $unidad_resena_material_apoyo = get_post_meta( $ID, 'unidad_resena_material_apoyo', true ); // array
//         $unidad_resena_material_apoyo_docs = get_post_meta( $ID, 'unidad_resena_material_apoyo_docs', true ); // array
//         $adicional_material_apoyo = get_post_meta( $ID, 'adicional_material_apoyo', true );

//         // preguntas
//         $question_unit = get_post_meta( $ID, 'preguntas_unidad', true);
//         print_r($question_unit);

//         echo "<hr>";

//     }

// } else {
//     echo "<small>El curso ".get_the_title($cursoId)." no tiene unidades creadas.</small>";
// }

?>