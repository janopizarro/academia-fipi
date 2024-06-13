<?php
ob_start();
/**
 * Child-Theme functions and definitions
 */

function lighthouseschool_child_scripts() {
    wp_enqueue_style( 'lighthouseschool-parent-style', get_template_directory_uri(). '/style.css' );
}
add_action( 'wp_enqueue_scripts', 'lighthouseschool_child_scripts' );

/* * cmb * */
if(file_exists( dirname( __FILE__ ) . '/cmb/init.php')){
    require_once dirname( __FILE__ ) . '/cmb/init.php';
    // require_once dirname( __FILE__ ) . '/cmb/usuarios.php';
    require_once dirname( __FILE__ ) . '/cmb/docentes.php';
    require_once dirname( __FILE__ ) . '/cmb/transacciones-flow.php';
    require_once dirname( __FILE__ ) . '/cmb/acceso-cursos.php';
    // require_once dirname( __FILE__ ) . '/cmb/status-cursos.php';
    require_once dirname( __FILE__ ) . '/cmb/cursos.php';
    require_once dirname( __FILE__ ) . '/cmb/unidades.php';
    require_once dirname( __FILE__ ) . '/cmb/slide.php';

    require_once dirname( __FILE__ ) . '/cmb/biblioteca.php';
    require_once dirname( __FILE__ ) . '/cmb/evaluacion-curso.php';

    require_once dirname( __FILE__ ) . '/cmb/encuesta-sincronico.php';
    require_once dirname( __FILE__ ) . '/cmb/encuesta-asincronico.php';

}
/* * end cmb * */

function getDataSession($tipo){
    if(isset($_SESSION['user_fipi'])){
        return $_SESSION['user_fipi'][$tipo];
    } else {
        return false;;
    }
}

function obtenerFechaEstablecida( $type, $id_curso, $email_alumno ){

    $args = array (
        'post_type' => 'accesos_curso',
        'meta_query' => array(
            array(
                'key' => 'accesos_email',
                'value' => $email_alumno,
                'compare' => '='
            ),
            array(
                'key' => 'accesos_curso_comprado',
                'value' => get_the_title( $id_curso ),
                'compare' => '='
            ),
        )
    );

    $res = new WP_Query( $args );

    if($res->post_count === 1){
        // si alumno figura en acceso-curso con fecha especial, se informa la fecha inicial y termino especial

        $id_acceso = $res->post->ID;

        if($type === "inicio"){

            if(get_post_meta( $id_acceso, 'accesos_fecha_inicio', true ) !== ""){
                return json_encode(array("status" => true, "fecha_inicio" => formatearFecha(get_post_meta( $id_acceso, 'accesos_fecha_inicio', true ))));
            } else { 
                // si no existe la fecha especial se va la fecha inicio por defecto del curso
                return json_encode(array("status" => true, "fecha_inicio" => formatearFecha(get_post_meta( $id_curso, 'curso_fecha', true ))));
            }

        } elseif ($type === "termino") {

            if(get_post_meta( $id_acceso, 'accesos_fecha_termino', true ) !== ""){
                return json_encode(array("status" => true, "fecha_termino" => formatearFecha(get_post_meta( $id_acceso, 'accesos_fecha_termino', true ))));
            } else { 
                // si no existe la fecha especial se va la fecha termino por defecto del curso
                return json_encode(array("status" => true, "fecha_termino" => formatearFecha(get_post_meta( $id_curso, 'curso_fecha_termino', true ))));
            }

        } else {

            // si no se obtiene el tipo de fecha a obtener se manda false
            return json_encode(array("status" => false));

        }

    } else {

        // alumno no figura en acceso-curso con fecha especial, se informa la fecha inicial y termino
        if($type === "inicio"){
    
            return json_encode(array("status" => true, "fecha_inicio" => formatearFecha(get_post_meta( $id_curso, 'curso_fecha', true ))));
    
        } elseif ($type === "termino") {
    
            return json_encode(array("status" => true, "fecha_termino" => formatearFecha(get_post_meta( $id_curso, 'curso_fecha_termino', true ))));
    
        } else {
    
            // si no se obtiene el tipo de fecha a obtener se manda false
            return json_encode(array("status" => false));
    
        }

    }

}

function formatearFecha( $fecha ){
    setlocale(LC_TIME, 'es_ES', 'Spanish_Spain', 'Spanish');
    $formato = "Y-m-d";
    $fechaFormateada = date($formato, strtotime($fecha));
    return $fechaFormateada;
}

function cantAccesosCursoFinalizado($courseId, $userId){

    global $wpdb;
    $tableName = $wpdb->prefix . "visitas_curso_finalizado";

    global $wpdb;
    $consulta = $wpdb->get_results(" SELECT * FROM $tableName WHERE `id_user` = $userId AND `id_curso` = $courseId ");

    if(count($consulta) > 0){
        // si existe

        $n_visto = $consulta[0]->n_visto;

        if($n_visto == 0){

            return array("status" => false, "n_visto" => 0);
            
        } else {

            return array("status" => true, "n_visto" =>  $n_visto);

        }

    } else {

        return array("status" => true, "n_visto" => 3);

    }

}

function verificarVigenciaDeCurso( $id_curso, $id_alumno, $fecha_termino_especial ){

    if($fecha_termino_especial !== ""){

        setlocale(LC_TIME, 'es_ES', 'Spanish_Spain', 'Spanish'); 
        $date = str_replace("/","-",$fecha_termino_especial);

        $date_now = new DateTime('now');
        $date2    = new DateTime($fecha_termino_especial);
        $date2->modify('+1 day');

        if($date_now > $date2){ 
            return json_encode(array("status" => true, "date" => $date));
        } else {
            return json_encode(array("status" => false));
        }

    } else {

        // si no tiene acceso con fecha de termino verifica la fecha de termino del curso
        $fechaTermino = get_post_meta( $id_curso, 'curso_fecha_termino', true );

        setlocale(LC_TIME, 'es_ES', 'Spanish_Spain', 'Spanish'); 
        $date = $date = str_replace("/","-",$fechaTermino);

        $date_now = new DateTime('now');
        $date2    = new DateTime($fechaTermino);
        $date2->modify('+1 day');

        if($date_now > $date2){ 
            return json_encode(array("status" => true, "date" => $fechaTermino));
        } else {
            return json_encode(array("status" => false));
        }

    }

}

function getUnitsCourse( $idCurso ){

    $args = array(
        'numberposts' => -1, 'post_type' => 'unidad', 'meta_query' => array(array('key' => 'unidad_curso', 'value' => $idCurso))
    );
    
    return get_posts($args);
}

function estadoActualFecha( $dateInicial, $dateTermino, $type ){
    setlocale(LC_TIME, 'es_ES', 'Spanish_Spain', 'Spanish');

    $dateAInicial = date("Y-m-d", strtotime($dateInicial));
    $dateATermino = date("Y-m-d", strtotime($dateTermino));

    $startDateInicial = DateTime::createFromFormat('Y-m-d', $dateAInicial);
    $startDateTermino = DateTime::createFromFormat('Y-m-d', $dateATermino);

    $endDate = DateTime::createFromFormat('Y-m-d', date('Y-m-d'));

    $wait_icon = get_stylesheet_directory_uri()."/images/"."wait-course.svg";
    $wait_style = "width: 18px;margin-right: -1px;top: -2px;position: relative;";

    $calendar_icon = get_stylesheet_directory_uri()."/images/"."calendario.svg";
    $calendar_style = "width: 14px;top: -3px;left: 2px;position: relative;margin-right: 4px;";

    $finish_icon = get_stylesheet_directory_uri()."/images/"."finish-course.svg";
    $finish_style = "width: 18px; position: relative; top: -2px";

    $diffInicial = date_diff($startDateInicial, $endDate);
    $diffTermino = date_diff($startDateTermino, $endDate);

    $daysInicial = $diffInicial->days;
    $daysTermino = $diffTermino->days;

    $monthTermino = $diffTermino->m;

    if($daysInicial === 0){

        $html = "<li>¡Comienza HOY!</li>";

        return $type === "string" ? $html : true;

    } else {

        if($diffInicial->invert !== 0){

            $days_inicial_str = $daysInicial > 1 ? 'días' : 'día';

            $html = "<li><img src='".$wait_icon."' style='".$wait_style."' /> Curso comienza en ".$daysInicial." ".$days_inicial_str."</li>";
        
            return $type === "string" ? $html : false;
    
        } else {
    
            if($diffTermino->invert !== 0){

                $days_termino_str = $daysTermino > 1 ? 'días' : 'día';
                $month_termino_str = $monthTermino > 1 ? 'meses' : 'mes';

                $html = "<li><img src='".$calendar_icon."' style='".$calendar_style."' /> Curso Inició el ".fechaEs($dateInicial)."</li>";
                
                if($daysTermino > 31){
                    $html .= "<li><img src='".$calendar_icon."' style='".$calendar_style."' /> Acceso a curso hasta: ".$monthTermino." ".$month_termino_str."</li>";
                } else {
                    $html .= "<li><img src='".$calendar_icon."' style='".$calendar_style."' /> Acceso a curso hasta: ".$daysTermino." ".$days_termino_str."</li>";
                }


            } else {

                $html = "<li><img src='".$finish_icon."' style='".$finish_style."' /> Curso Terminó ".fechaEs($dateTermino)."</li>";

            }

            return $type === "string" ? $html : true;
    
        }

    }

}

function fechaEs($fecha) {
    $fecha = substr($fecha, 0, 10);
    $numeroDia = date('d', strtotime($fecha));
    $dia = date('l', strtotime($fecha));
    $mes = date('F', strtotime($fecha));
    $anio = date('Y', strtotime($fecha));
    $dias_ES = array("Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo");
    $dias_EN = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
    $nombredia = str_replace($dias_EN, $dias_ES, $dia);
    $meses_ES = array("Ene.", "Feb.", "Mar.", "Abr.", "May.", "Jun.", "Jul.", "Ago.", "Sep.", "Oct.", "Nov.", "Dic.");
    $meses_EN = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
    $nombreMes = str_replace($meses_EN, $meses_ES, $mes);
    // return $nombredia." ".$numeroDia." de ".$nombreMes." de ".$anio;
    return $numeroDia." de ".$nombreMes." de ".$anio;
}

function introFinish($id_user, $id_curso){
    
    global $wpdb;
    $tableName = $wpdb->prefix . "estado_curso";
    $consulta = $wpdb->get_results(" SELECT * FROM $tableName WHERE `id_user` = $id_user AND `id_curso` = $id_curso AND unidad_intro = 1 LIMIT 0,1 "); 

    if(count($consulta)>0){
        return true;
    } else {
        return false;
    }

}

function getPercentaje($courseId, $userId){

	// obtener el total de preguntas de las unidades
	$total = array();

	// unidad 01
    if(get_post_meta($courseId, 'preguntas_unidad_1', true)){

        $preguntas_1 = get_post_meta($courseId, 'preguntas_unidad_1', true);
        foreach ($preguntas_1 as $key_1 => $preg_1) {
            $pregunta_1 = esc_html($preg_1['pregunta_unidad_1']);
            $total[] = $pregunta_1;
        }

    }

	// unidad 02
    if(get_post_meta($courseId, 'preguntas_unidad_2', true)){ 

        $preguntas_2 = get_post_meta($courseId, 'preguntas_unidad_2', true);
        foreach ($preguntas_2 as $key_2 => $preg_2) {
            @$pregunta_2 = esc_html($preg_2['pregunta_unidad_2']);
            $total[] = $pregunta_2;
        }

    }

    // unidad 03
    if(get_post_meta($courseId, 'preguntas_unidad_3', true)){

        $preguntas_3 = get_post_meta($courseId, 'preguntas_unidad_3', true);
        foreach ($preguntas_3 as $key_3 => $preg_3) {
            $pregunta_3 = esc_html($preg_3['pregunta_unidad_3']);
            $total[] = $pregunta_3;
        }

    }

	// unidad 04
    if(get_post_meta($courseId, 'preguntas_unidad_4', true)){

        $preguntas_4 = get_post_meta($courseId, 'preguntas_unidad_4', true);
        foreach ($preguntas_4 as $key_4 => $preg_4) {
            $pregunta_4 = esc_html($preg_4['pregunta_unidad_4']);
            $total[] = $pregunta_4;
        }
        
    }

    if(count($total) == 0){
        $total = array(0);
    }

	// respondidas
	global $wpdb;
    $tableName = $wpdb->prefix . "estado_curso_respuestas";
    $consulta = $wpdb->get_results(" SELECT * FROM $tableName WHERE `id_user` = $userId AND `id_curso` = $courseId ");

	$percentage = ( count($consulta) / count($total) ) * 100;
	
	return number_format($percentage);

}

function getPercentajeNew($id_course, $id_user){

    $total = array();

    foreach(getUnitsCourse($id_course) as $res){

        $id_unit = $res->ID;

        $questions_unit = get_post_meta( $id_unit, 'preguntas_unidad', true);

        foreach($questions_unit as $question){

            $total[] = $question;

        }

    }

    global $wpdb;
    $tableName = $wpdb->prefix . "unidades_dinamico_respuestas";
    $consulta = $wpdb->get_results(" SELECT * FROM $tableName WHERE `id_user` = $id_user AND `id_curso` = $id_course ");

    $percentage = ( count($consulta) / count($total) ) * 100;

    return number_format($percentage);

}

function registraNuevaVisualizacion($id_alumno, $id_curso){

    date_default_timezone_set('America/Santiago');
    $date = date('Y-m-d h:i:s', time());

    global $wpdb;

    $tableName = $wpdb->prefix . "visitas_curso_finalizado";

    $consulta = $wpdb->get_results(" SELECT * FROM $tableName WHERE `id_user` = $id_alumno AND `id_curso` = $id_curso ");

    if(count($consulta) > 0){

        $n_visto_rest = $consulta[0]->n_visto - 1;

        if($n_visto_rest >= 0){

            $wpdb->update($tableName, array('n_visto' => $n_visto_rest, 'fecha' => $date),array('id_user' => $id_alumno, 'id_curso' => $id_curso));

        }

    } else {

        $insercion = $wpdb->insert($tableName, array(
            'id'       => 'null',
            'id_user'  => $id_alumno,
            'id_curso' => $id_curso,
            'n_visto'  => 2,
            'fecha'    => $date
        ));

    }    

}

function checkear_si_tiene_curso( $email , $idCurso ){
    $args = array (
        'post_type' => 'accesos_curso',
        'meta_query' => array(
            array(
                'key' => 'accesos_email',
                'value' => $email,
                'compare' => '='
            ),
            array(
                'key' => 'accesos_curso_comprado',
                'value' => get_the_title( $idCurso ),
                'compare' => '='
            )
        )
    );
    $res = new WP_Query( $args );

    $acc = array();

    if($res->post_count > 0){

        if(get_post_meta( $res->post->ID, 'accesos_estado', true ) != "inactivo"){
        
            $acc[] = array("estado" => "activo", "comprado" => 1);

        } else {

            $acc[] = array("estado" => "inactivo", "comprado" => 1);
        }
                    
    } else {

        $acc[] = array("estado" => "", "comprado" => 0);

    }

    return json_encode($acc);

}

function haveActivityInCourse($id_user, $id_course){

    global $wpdb;
    $tableName = $wpdb->prefix . "unidades_dinamico";

    $query = $wpdb->get_results(" SELECT * FROM $tableName WHERE `id_user` = $id_user AND `id_curso` = $id_course AND `tipo` = 1 AND `status` = 1 OR `id_user` = $id_user AND `id_curso` = $id_course AND `tipo` = 2 AND `status` = 1 ");

    if(count($query) > 0){

        return true;

    } else {

        return false;

    }

}

function haveFinishedCourse($id_user, $id_course){

    // obtener las unidades del curso
    $units = getUnitsCourse($id_course);

    global $wpdb;
    $tableName = $wpdb->prefix . "unidades_dinamico";

    // se rescatan las unidades realizadas por el alumno
    $queryUnits = $wpdb->get_results(" SELECT * FROM $tableName WHERE `id_user` = $id_user AND `id_curso` = $id_course AND `tipo` = 2 AND `status`= 1 ");
    // se rescata si se envío el email al admin
    $queryEmail = $wpdb->get_results(" SELECT * FROM $tableName WHERE `id_user` = $id_user AND `id_curso` = $id_course AND `tipo` = 3 AND `status`= 1 ");

    if(count($queryUnits) > 0){

        // si el número de unidades es igual a las realizadas por el alumno y además si se envío el email al admin queda como finalizado oficialmente el curso.
        if(count($units) == count($queryUnits) && count($queryEmail) > 0){

            return true;

        } else {

            return false;

        }

    } else {

        return false;

    }

}

function verifyUnitEndNew($unidad, $userId, $courseId){

    global $wpdb;
    $tableName = $wpdb->prefix . "unidades_dinamico";

    $consulta = $wpdb->get_results(" SELECT * FROM $tableName WHERE `id_user` = $userId AND `id_curso` = $courseId AND `n_unidad` = $unidad AND `status`= 1 limit 0,1 ");

    if(count($consulta) > 0){

        return true;

    } else {

        return false;

    }

}

function checkStatusQuestionNew($unitCourse, $questionNumber, $userId, $courseId, $index, $type){

    switch ($type) {

        case 'checkbox':

            global $wpdb;
            $tableName = $wpdb->prefix . "unidades_dinamico_respuestas";

            $consulta = $wpdb->get_results(" SELECT * FROM $tableName WHERE `id_user` = $userId AND `id_curso` = $courseId AND `n_preg` = $questionNumber AND `id_unidad` = $unitCourse LIMIT 0,1 ");

            foreach($consulta as $res){
                if($res->estado === "correcta"){
                    return ' pointer-events: none; opacity: .5;';
                }
                if($res->estado === "incorrecta" && $res->intento == 2){
                    return ' pointer-events: none; opacity: .5;';
                }
            }
        
            break;
        
        case 'question':

            // verificar cual fue el ultimo estado registrado
            global $wpdb;
            $tableName = $wpdb->prefix . "unidades_dinamico_respuestas";

            $consulta = $wpdb->get_results(" SELECT * FROM $tableName WHERE `id_user` = $userId AND `id_curso` = $courseId AND `n_preg` = $questionNumber AND `id_unidad` = $unitCourse LIMIT 0,1 ");

            foreach($consulta as $res){
                if($res->estado === "correcta"){
                    return 'ok_question';
                }
                if($res->estado === "incorrecta" && $res->intento == 1){
                    return 'warning_question';
                }
                if($res->estado === "incorrecta" && $res->intento == 2){
                    return 'error_question';
                }
            }

            break;

        case 'selected-alternative':

            // verificar cual fue el ultimo estado registrado
            global $wpdb;
            $tableName = $wpdb->prefix . "unidades_dinamico_respuestas";

            $consulta = $wpdb->get_results(" SELECT * FROM $tableName WHERE `id_user` = $userId AND `id_curso` = $courseId AND `n_preg` = $questionNumber AND `id_unidad` = $unitCourse LIMIT 0,1 ");

            foreach($consulta as $res){
                if($res->estado === "correcta"){
                    $ok_ = $res->respuesta_correcta;
                    return $ok_;
                }
                if($res->estado === "incorrecta" && $res->intento == 2){

                    $preguntas = get_post_meta($unitCourse, 'preguntas_unidad', true);
                    foreach ($preguntas as $key => $pregunta) { 
                        $altCorrectas[] = $pregunta['alternativa_correcta_unidad'];
                    }

                    $correctas = implode(",",$altCorrectas[$index]);
                    return $correctas;

                }
            }

            break;

        case 'incorrect-alternative':

            // verificar cual fue el ultimo estado registrado
            global $wpdb;
            $tableName = $wpdb->prefix . "unidades_dinamico_respuestas";

            $consulta = $wpdb->get_results(" SELECT * FROM $tableName WHERE `id_user` = $userId AND `id_curso` = $courseId AND `n_preg` = $questionNumber AND `id_unidad` = $unitCourse LIMIT 0,1 ");

            $ret = array();

            foreach($consulta as $res){

                if($res->respuesta_incorrecta != ""){
                    $ret[] = $res->respuesta_incorrecta;
                }

            }

            $incorrectas = implode(",",$ret);
            return $incorrectas;

            break;
    

        case 'message':

            // verificar cual fue el ultimo estado registrado
            global $wpdb;
            $tableName = $wpdb->prefix . "unidades_dinamico_respuestas";

            $consulta = $wpdb->get_results(" SELECT * FROM $tableName WHERE `id_user` = $userId AND `id_curso` = $courseId AND `n_preg` = $questionNumber AND `id_unidad` = $unitCourse LIMIT 0,1 ");

            foreach($consulta as $res){
                if($res->estado === "incorrecta" && $res->intento == 1){
                    return '¡Te queda 1 intento';
                }
            }

            break;

        default: 

            return '';

    }

}

function letras(){
    $letras = array("","a","b","c","d","e","f","g","h","i","j");
    return $letras;
}

function redirect($tiempo,$page){
    echo "
    <script>
    setTimeout(function () {
        window.history.back();
    },".$tiempo.");
    </script>
    ";
}

function getNextUnit($id_course, $id_user, $ID){

    $unit_actual_ID = $ID;

    // obtener las unidades del curso
    $units = getUnitsCourse($id_course);

    $id_units = array();

    // rescatar sólo el ID de la unidad
    foreach($units as $res){
        $id_units[] = $res->ID;
    }

    $completed_units = array();

    if($unit_actual_ID){
        // se añade el ID terminado
        $completed_units = array_push($completed_units, $unit_actual_ID);
    } else {
        // obtener las unidades terminadas
        $completed_units = getUnitsCompleted($id_user, $id_course);        
    }

    // verificar si `completed_units` tiene 0 elementos para determinar si aún no hay nada iniciado
    if(count($completed_units) == 0){

        $arr = array_diff($id_units, $completed_units);

        // retorna el estado actual
        return next($arr);

    } else {

        // verificar si los dos arrays son iguales para ver si el curso está terminado
        if($id_units === $completed_units){

            $arr = array("curso-terminado");

            // retorna el estado actual
            return current($arr);
        
        } else {
        
            // revisar la diferencia entre arrays para obtener el siguiente `id_unit`
            $arr = array_diff($id_units, $completed_units);

            // retorna el estado actual
            return next($arr);

        }

    }

}

function getNext( $current, $id_course ){

    // obtener las unidades del curso
    $units = getUnitsCourse($id_course);

    $id_units = array();

    // rescatar sólo el ID de la unidad
    foreach($units as $res){
        $id_units[] = $res->ID;
    }

    $nextkey = array_search($current, $id_units) + 1;

    if($nextkey == count($id_units)) {
        $nextkey = 0;
    }
    
    if($id_units[$nextkey] != $id_units[0]){

        $next = $id_units[$nextkey];

    } else {

        $next = 'curso-terminado';

    }
    
    return $next;

}

function getVideoUnit($unitID){

    // $urlVideo = str_replace("https://vimeo.com/","",get_post_meta( $unitID, 'unidad_resena_video', true )); 

    // $urlVideo_ = explode("/",$urlVideo);
    // if(count($urlVideo_)>0){
    //     $urlVideo_ok = $urlVideo_[0]."?h=".$urlVideo_[1];
    // }

    // $iframe = '<iframe src="//player.vimeo.com/video/'.$urlVideo_ok.'&api=1&player_id=video&title=0&amp;byline=0&amp;portrait=0&amp;color=c9ff23;autoplay=1" width="640" height="360" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" webkitallowfullscreen mozallowfullscreen allowfullscreen id="video"></iframe>';
    // return "<script>document.getElementById('loadVideo').innerHTML = '".$iframe."'</script>";

    preg_match('/src="([^"]+)"/', get_post_meta( $unitID, 'unidad_resena_video_iframe', true), $match);
    $url = $match[1];

    $js = "    
        <script>
        let videoPlayer = document.getElementById('videoPlayer');
        let url_string = '".$url."';
        let adsURL = url_string+'&api=1&player_id=video&title=0&amp;byline=0&amp;portrait=0&amp;color=c9ff23;autoplay=1';
        videoPlayer.src = adsURL;
        </script>
    ";

    return $js;

}

function curos_home_shortcode() {

    $args = array(  
        'posts_per_page' => 3,
        'order' => 'DESC',
        'post_status' => 'publish',
        'post_type' => 'curso'
    );

    $html = "<style>
    
    .content-new-home {
        display: flex;
        flex-wrap: wrap;
        transition: all 0.4s;
        max-width: 1200px;
        margin: 0 auto 0 auto;
    }
    .item-new {
        background-color: #f0f0f0;
        padding: 0px;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 0 6px #00000017;
        flex: 1;
        margin: 24px;
    }

    .item-new .item-new--inset{
        padding: 15px;
    }

    .item-new .item-new--inset h5{
        text-transform: capitalize;
        color: #2A649D;
        font-size: 20px !important;
    }

    .item-new .dashboard-stat{
        height: auto;
        overflow: hidden;
        position: relative;
    }

    .item-new .dashboard-stat img{
        width: 100%;
        zoom: 2;
    }

    @media (max-width: 768px) {
      .container-new {
        display: block;
        padding: 0px;
        padding-top: 30px;
      }
      .content-new-home{
        display: block;  
      }
    }
    
    </style>";

    $loop = new WP_Query( $args );

    // si encuentra post imprime el card
    if($loop->post_count > 0){

        $html .= "<div class='content-new-home'>";
        
        while ( $loop->have_posts() ) : $loop->the_post();

            $id_curso = $loop->post->ID;

            $asd = get_the_terms($id_curso, 'modalidad_cursos');

            $modalidd_ = [];

            if($asd){
                foreach($asd as $re){
                    $modalidd_[] = $re->name;
                }    
            }

            $fecha_inicio = json_decode(obtenerFechaEstablecida("inicio", $id_curso, $email_alumno))->fecha_inicio;
            $fecha_termino = json_decode(obtenerFechaEstablecida("termino", $id_curso, $email_alumno))->fecha_termino;
            $fecha_estado_actual = estadoActualFecha($fecha_inicio, $fecha_termino, "string");

            $cantAccesosCursoFinalizado = cantAccesosCursoFinalizado($id_curso,getDataSession("id"));
            
            $html .= "<div class='item-new'>";
            $html .= "<div class='dashboard-stat'";
            if($fecha_actual >= $fecha_termino && !$cantAccesosCursoFinalizado["status"]) {
                $html .= "style='opacity: 0.7; transition:none; transform:none;'";
            }
            $html .= ">";
            
            $fecha = get_post_meta( $id_curso, 'curso_fecha', true );
            
            if(isset($_SESSION['user_fipi'])) {
                if(count(getUnitsCourse($id_curso)) > 0){
                    if(getPercentajeNew($id_curso, getDataSession("id")) && !$cantAccesosCursoFinalizado["status"]) {
                        if(getPercentajeNew($id_curso, getDataSession("id")) === 100){ 
                            $html .= "<p class='messageCourse'>Finalizado</p>";
                        } else {
                            if(getPercentajeNew($id_curso, getDataSession("id")) > 0){
                                $html .= "<p class='messageCourse'>Avance: ".getPercentajeNew($id_curso, getDataSession("id"))."%</p>";
                            }
                        }
                    }
                } else {
                    if(getPercentaje($id_curso, getDataSession("id")) && !$cantAccesosCursoFinalizado["status"]) {
                        if(getPercentaje($id_curso, getDataSession("id")) == 100){ 
                            $html .= "<p class='messageCourse'>Finalizado</p>";
                        } else {
                            if(getPercentaje($id_curso, getDataSession("id")) > 0){
                                $html .= "<p class='messageCourse'>Avance: ".getPercentaje($id_curso, getDataSession("id"))."%</p>";
                            }
                        }
                    }
                }
            }
            
            $html .= "<img src='".get_stylesheet_directory_uri()."/images/fipi-te-apana-02.svg' class='card-fipi-apana' />";
            
            if(get_post_meta( $id_curso, 'curso_imagen_pequena', true )) {
                $html .= "<img";
                if($fecha_actual >= $fecha_termino && !$cantAccesosCursoFinalizado["status"]) {
                    $html .= " style='filter: grayscale(1);'";
                }
                $html .= " src='".get_post_meta( $id_curso, 'curso_imagen_pequena', true )."'>";
            } else {
                $html .= "<img";
                if($fecha_actual >= $fecha_termino && !$cantAccesosCursoFinalizado["status"]) {
                    $html .= " style='filter: grayscale(1);'";
                }
                $html .= " src='".get_stylesheet_directory_uri()."/images/imagen-en-proceso.jpg'>";
            }
            
            $html .= "</div>";
            $html .= "<div class='item-new--inset'>";
            $html .= "<h5>";
            if($fecha_actual <= $fecha_termino) {
                $html .= "<a href='".get_the_permalink($id_curso)."'>".get_the_title($id_curso)."</a>";
            } else {
                $html .= get_the_title($id_curso);
            }
            $html .= "</h5>";

            if($modalidd_) {
                foreach($modalidd_ as $res){
                    $html .= "<p style='color: #2A649D;'>".$res."</p>";
                }
            }
            
            if(($cantAccesosCursoFinalizado["status"]) || ($fecha_actual <= $fecha_termino)) {
                $html .= "<div class='buttons-card'>";
                $html .= "<a href='".get_the_permalink($id_curso)."' id='sc_button_2095599388' class='card-button sc_button color_style_dark sc_button_default sc_button_size_normal sc_button_icon_left sc_button_hover_slide_left sc_button_hover_style_dark'><span class='sc_button_text'><span class='sc_button_title'>Ver detalle</span></span></a>";
                $html .= "<a href='".get_the_permalink($id_curso)."' class='add-cart'><img src='".get_stylesheet_directory_uri()."/images/cart-add.svg' width='24' /></a>";
                $html .= "</div>";
            }

            if($fecha_actual >= $fecha_termino && $cantAccesosCursoFinalizado["status"] && getDataSession("id")){
                $html .= "<p class='visualizaciones' style='display:block'><img src='".get_stylesheet_directory_uri()."/includes/informacion.png' width='16' /> El curso ya terminó pero cuentas con ".$cantAccesosCursoFinalizado["n_visto"]." visualización/es más.</p>";
            }
            
            $html .= "</div>";
            $html .= "</div>";

        endwhile;
        wp_reset_postdata();

    }

    $html .= "</div>";

    return $html;

}

add_shortcode('cursos_home', 'curos_home_shortcode');

function container_fipi_card_shortcode($atts = '', $content = null) { 

    $atributos = shortcode_atts([  'tipo' => 'ESTILO_UNO',  ], $atts);

    $html = "";

    $html .= "
        <style>
            .container{
                max-width: 1200px;
                padding: 0px;
            } 
            .container .row{
                display: flex;
                flex-wrap: wrap;
                margin: 0px;
            }
            .container br{
                display: none;
            }
        </style>
    ";

    $html .= "
    
    <div class='container'>
        <div class='row'>".do_shortcode($content)."</div>
    </div>
        
    ";

    return $html;
}

add_shortcode('container_fipi_card', 'container_fipi_card_shortcode');

function item_fipi_card_shortcode($atts = '', $content = null) { 

    $atributos = shortcode_atts([
        'type'              => '1',
        'background'        => 'FFFFFF',
        'background_circle' => 'EDE5C1',
        'color'             => 'FFFFFF',
        'title'             => 'lorem',
        'cargo'             => '',
        'description'       => 'lorem ipsum',
        'url_image'         => '',
        'link_button'       => '',
        'back_button'       => 'FFE000',
        'color_button'      => '2A649D',
        'text_button'       => '',
        'link_button_type'  => '_blank'
    ], $atts);

    $html = "
        <style>
            .col-sm {
                flex: 1 0 calc(33% - 30px); /* Restamos 30px para dejar espacio para los márgenes */
                max-width: 400px; /* Establecemos el tamaño máximo de la columna */
            }
            .col-sm_var_3{
                max-width: 100% !important;
                margin-top: -55px;
            }
            .col-sm--inset{
                border-radius: 24px;
                margin: 15px;
                padding: 15px 25px;
                text-align: center;
                min-height: 260px;
            }
            .col-sm--inset img{
                width: 70px;
            }
            .col-sm--inset h4{
                font-size: 18px !important;
                text-transform: capitalize;
                font-weight: 800 !important;
                font-family: 'Raleway', sans-serif !important;
                color: #2A649D;
            }
            .col-sm--inset p{
                color: #2A649D; 
            }
            @media only screen and (max-width: 600px) {
                .col-sm{
                    flex: 1 0 100%;
                }
            }
            .col_styles_1{
                background: #FFFFFF;
            }
            .col_styles_1 .img_cont{
                background: #2A649D;
                padding: 20px;
                border-radius: 50%;
                height: 34px;
                width: 34px;
                margin: 0 auto 0 auto;
            }
            .col_styles_1 img{
                height: 34px;
                width: 34px;
            }
            .col_styles_2{
                margin-bottom: 55px !important;
            }
            .col_styles_2 img{
                width: 120px !important;
                margin-top: -70px;
            }
            .col_styles_3 img{
                width: 100% !important;
            }
            .col_styles_4 img{
                width: 155px !important;
                margin-bottom: -10px;
                border-radius: 50%;
            }
            .col_styles_4 h4{
                margin-bottom: 5px;
            }
            .col_styles_4 h2{
                font-size: 13px !important;
                letter-spacing: 0px;
                color: #2A649D;
                font-family: 'Raleway', sans-serif !important;
                font-weight: bold;
                width: 200px;
                margin: 10px auto 20px auto;
            }
            .col_styles_5{
                background:none !important
            }
            .col_styles_5 a{
                background: #FFE000;
                color: #2A649D;
                text-decoration: none;
                padding: 5px 20px;
                border-radius: 20px;
            }
            .col_styles_5 a:hover{
                color: #2A649D;
                opacity: 0.7;
            }
            .col_styles_5 img{
                height: 70px;
            }
            .scheme_default table > tbody > tr > td{
                background: none !important;
                font-family: 'Raleway', sans-serif !important;
            }
            table.aligncenter{
                display: flex !important;
                justify-content: center;
                height: auto !important;
            }
        </style>
    ";

    $html .= "
        <div class='col-sm col-sm_var_".$atributos['type']."'>
            <div class='col-sm--inset col_styles_".$atributos['type']."' style='background-color:#".$atributos['background']."'>
                <div class='img_cont' "; 
                
                // if($atributos['background_circle'] != ""){
                    $html .= "                
                        style='background-color:#".$atributos['background_circle']."'
                    ";           
                // }
                
            $html .= ">
                <img src=".$atributos['url_image']." />
            </div>

                <h4 style='color:#".$atributos['color']."' data-type='".$atributos['type']."'>".$atributos['title']."</h4>";

                if($atributos['cargo'] != ""){
                    $html .= "<h2>".$atributos['cargo']."</h2>";           
                }

            $html .= "<p style='color:#".$atributos['color']."'>".$atributos['description']."</p>";

                if($atributos['link_button']){
                    $html .= "<a style='background-color:#".$atributos['back_button']."; color:#".$atributos['color_button']."' ".$atributos['link_type']." href='".$atributos['link_button']."'>".$atributos['text_button']."</a>";
                }

    $html .= "</div>
        </div>
    ";



    return $html;

}

add_shortcode('item_fipi_card', 'item_fipi_card_shortcode');

function contador_item_fipi_shortcode($atts = '', $content = null) { 

    $atributos = shortcode_atts([
        'id'        => '0',
        'title_1'   => '',
        'title_2'   => 'lorem',
        'color'     => '2A649D',
        'url_image' => '',
        'number'    => 0
    ], $atts);

    $html = '

        <style>
        .col-sm-stat {
            flex: 1 0 calc(22% - 30px); /* Restamos 30px para dejar espacio para los márgenes */
            max-width: 300px; /* Establecemos el tamaño máximo de la columna */
        }
        .container .row{
            justify-content: center;
        }
        .counter {
            font-size: 70px;
            padding: 0px;
            margin: 0px;
            height: auto;
            width: auto;
            color: #2A649D;
            position: relative;
            top: -20px;
            margin-bottom: -37px;
        }
        .counter_container{
            color: #2A649D;
            text-align:center;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .counter_container p{
            margin-bottom: 0px;
            font-weight: 700;
        }
        .counter_container img{
            width: 55px;
            margin-bottom: 5px;
        }
        @media only screen and (max-width: 600px) {
            .row{
                flex-direction: column;
                align-items: center;
            }
            .counter{
                top: 0px !important;
                margin-bottom: 20px !important;
                font-size: 40px !important;
                margin-top: 14px !important;
            }
            .counter_container{
                margin-bottom: 25px;
            }
            .vc_column-inner{
                padding-left: 0px;
                padding-right: 0px;
            }
        }
        
        </style>

        <div class="col-sm-stat counter_container">
            <img src="'.$atributos['url_image'].'" />';
            
            if($atributos['url_imagen']){
                $html .= '<p style="color: #'.$atributos['color'].'">'.$atributos['title_1'].'</p>';
            }

            $html .= '<div id="skills_counter_0'.$atributos['id'].'" class="counter" style="color: #'.$atributos['color'].'">0</div>
            <p style="color: #'.$atributos['color'].'">'.$atributos['title_2'].'</p>
        </div>
    
    ';

    // $html .= '<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script><script>$.fn.jQuerySimpleCounter=function(e){var t=$.extend({start:0,end:100,easing:"swing",duration:400,complete:""},e),n=$(this);$({count:t.start}).animate({count:t.end},{duration:t.duration,easing:t.easing,step:function(){var e=Math.ceil(this.count).toLocaleString("en-US");n.text(e.replace(/,/g,"."))},complete:t.complete})};$("#skills_counter_0'.$atributos['id'].'").jQuerySimpleCounter({end:$atributos.number,duration:3000});</script>';
    // $html .= '<script>$.fn.jQuerySimpleCounter=function(e){var t=$.extend({start:0,end:100,easing:"swing",duration:400,complete:""},e),n=$(this);$({count:t.start}).animate({count:t.end},{duration:t.duration,easing:t.easing,step:function(){var e=Math.ceil(this.count).toLocaleString("en-US");n.text(e.replace(/,/g,"."))},complete:t.complete})};$("#skills_counter_01").jQuerySimpleCounter({end:$atributos.number,duration:3000});</script>';

    $html .= '
    
    <script>
    function simpleCounter(element, options) {
        var defaultOptions = {
            start: 0,
            end: 100,
            easing: "swing",
            duration: 400,
            complete: ""
        };
    
        var settings = Object.assign({}, defaultOptions, options);
        var thisElement = document.querySelector(element);
    
        var count = settings.start;
        var startTime = null;
    
        function animateCounter(timestamp) {
            if (!startTime) startTime = timestamp;
            var progress = timestamp - startTime;
            var percentage = Math.min(progress / settings.duration, 1);
            count = Math.ceil(settings.start + percentage * (settings.end - settings.start));
    
            var formattedCount = count.toLocaleString("en-US");
            formattedCount = formattedCount.replace(/,/g, ".");
    
            thisElement.textContent = formattedCount;
    
            if (progress < settings.duration) {
                requestAnimationFrame(animateCounter);
            } else {
                if (typeof settings.complete === "function") {
                    settings.complete();
                }
            }
        }
    
        requestAnimationFrame(animateCounter);
    }
    
    simpleCounter("#skills_counter_0'.$atributos['id'].'", {
        end: '.$atributos['number'].',
        duration: 3000
    });

    </script>
    
    ';

    return $html;
}

add_shortcode('contador_item_fipi', 'contador_item_fipi_shortcode');

function filtros_cursos_fipi_shortcode($atts = '', $content = null) { 

    $taxCurso = 47;
    $childrenCategory = get_term_children($taxCurso, 'categoria');

    $html = '
    
    <style>
    .category__nav a{
        color: #a1a1a1;
    }
    a.checked{
        color: #2a649d !important;
    }
    a.filter_category{
        display: flex !important;
        align-items: center;
        color: #2A649D;
    }
    a.filter_category:before{
        background: url("'.get_stylesheet_directory_uri().'/images/checkbox-off.svg");
        content: "";
        width: 15px;
        height: 15px;
        display: block;
        background-size: 15px;
        margin-right: 5px;
    }
    a.filter_category.checked:before{
        background: url("'.get_stylesheet_directory_uri().'/images/checkbox.svg");
        background-size: 15px;
        filter: none;
    }
    .cableItem {
        color: #235d80;
        overflow: hidden;
        transition: all 0.4s;
        max-width: 320px;
        margin: 10px 15px;
        max-height: 440px;
    }
    .cableItem-hide {
        opacity: 0;
        width: 0;
        padding: 0;
        margin: 0;
    }
    </style>

    ';

    $html .= '
    
    <div class="sidebar-new--widget">
        <input type="search" id="searchInput" placeholder="Buscar por nombre" class="search-course-input" />
    </div>

    <div class="sidebar-new--widget">

    <!--<h3>Tags</h3>-->

    <nav class="widget-tags category__nav">';

        $childrenTags = get_terms([
            'taxonomy' => 'tags_cursos',
            'hide_empty' => false,
        ]);

        foreach($childrenTags as $tag){
            $html .= '<a href="#" data-target="'.$tag->slug.'">'.$tag->name.'</a>';
        }

    $html .= '</nav>

    </div>

    <div class="sidebar-new--widget">

        <h3>Categoría:</h3>

        <ul class="category__nav">';

        foreach($childrenCategory as $tax){

            $taxName = get_term( $tax )->name;
            $taxSlug = get_term( $tax )->slug;

            // $html .= '<li>
            //             <input style="display: none;" type="checkbox" id="cat_'.$tax.'" name="cat_'.$tax.'" value="'.$tax.'" />
            //             <label for="cat_'.$tax.'">'.$taxName.'</label>
            //           </li>';

            $html .= '<a class="filter_category" href="#" data-target="'.$taxSlug.'" style="width: 100%; display: block;">'.$taxName.'</a>';

        }

        $html .= '</ul>
    </div>';

    $taxModalidad = 53;
    $childrenModalidad = get_term_children($taxModalidad, 'categoria');

    $html .= '<div class="sidebar-new--widget">

        <h3>Tipo:</h3>

        <ul class="category__nav">';

        // foreach($childrenModalidad as $tax){

        //     $taxName = get_term( $tax )->name;
        //     $taxSlug = get_term( $tax )->slug;

        //     $html .= '<a href="#" data-target="'.$taxSlug.'" style="width: 100%; display: block;">'.$taxName.'</a>';

        // }

        $childrenTags = get_terms([
            'taxonomy' => 'modalidad_cursos',
            'hide_empty' => false,
        ]);

        foreach($childrenTags as $tag){
            $html .= '<a class="filter_category" href="#" data-target="'.$tag->slug.'" style="width: 100%; display: block;">'.$tag->name.'</a>';
        }

        $html .= '</ul>
    </div>

    ';

    $html .= "
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {

        var activeCriteria = [];

        var target = [];
        var categoryNavLinks = document.querySelectorAll('.category__nav a');
        var cableItems = document.querySelectorAll('.category .cableItem');
        var searchInput = document.querySelector('#searchInput'); 
        var matchCountElement = document.getElementById('matchCount'); 
        var notFound = document.getElementById('notFound');

        categoryNavLinks.forEach(function(link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
    
                var dataTarget = link.getAttribute('data-target');
    
                if (link.classList.contains('checked')) {
                    link.classList.remove('checked');
                    var currentIndex = target.indexOf(dataTarget);
                    target.splice(currentIndex, 1);
                    removeFromActiveCriteria(dataTarget); // Remover el criterio de búsqueda activo
                } else {
                    link.classList.add('checked');
                    target.push(dataTarget);
                    addToActiveCriteria(dataTarget); // Agregar el criterio de búsqueda activo
                }
    
                filterItems(target);
            });
        });
    
        searchInput.addEventListener('input', function() {
            var searchText = searchInput.value.toLowerCase().trim();
            var matchCount = 0; // Inicializar el contador de coincidencias
        
            if (searchText === '') {
                // Si el texto de búsqueda está vacío, contar el total de items
                matchCount = cableItems.length;
                showAllItems();
            } else {
                // Si hay texto en el input, contar las coincidencias
                cableItems.forEach(function(item) {
                    var dataTitle = item.getAttribute('data-title').toLowerCase();
        
                    if (dataTitle.includes(searchText)) {
                        item.classList.remove('cableItem-hide');
                        matchCount++; // Incrementar el contador de coincidencias si hay match
                    } else {
                        item.classList.add('cableItem-hide');
                    }
                });
            }
        
            // Actualizar el contenido del elemento en el DOM con el número de coincidencias
            matchCountElement.textContent = 'Resultados: ' + matchCount;
        });
        
        
    
        function filterItems(target) {
            var anyActive = false;
            var searchText = searchInput.value.toLowerCase().trim(); // Obtener el texto de búsqueda
        
            categoryNavLinks.forEach(function(link) {
                if (link.classList.contains('checked')) {
                    anyActive = true;
                }
            });
        
            if (!anyActive && searchText === '') {
                // Si no hay ningún elemento activo y el texto de búsqueda está vacío,
                // mostrar el total de items y restablecer el contador a cero
                showAllItems();
                matchCountElement.textContent = 'Resultados: ' + cableItems.length;
                return;
            }
        
            var matchCount = 0; // Inicializar el contador de coincidencias
        
            cableItems.forEach(function(item) {
                var dataTarget = item.getAttribute('data-target');
        
                var searchTerms = dataTarget.split(',').map(term => term.trim());
                var result = searchTerms.some(term => target.includes(term));
        
                // Verificar si hay match con el texto de búsqueda
                var dataTitle = item.getAttribute('data-title').toLowerCase();
                if (dataTitle.includes(searchText) && result) {
                    matchCount++;
                }
        
                if (!result) {
                    item.classList.add('cableItem-hide');
                } else {
                    item.classList.remove('cableItem-hide');
                }
            });
        
            // Actualizar el contenido del elemento en el DOM con el número de coincidencias
            matchCountElement.textContent = 'Resultados: ' + matchCount;


            if (matchCount === 0) {
                // Si no se encontraron coincidencias, mostrar un mensaje
                var message = 'No se encontró nada para el criterio de busqueda';
                notFound.textContent = message;
            } else {
                // Si se encontraron coincidencias, mostrar el número de resultados
                notFound.textContent = '';
                matchCountElement.textContent = 'Resultados: ' + matchCount;
            }
        }
        
        
        
    
        function showAllItems() {
            cableItems.forEach(function(item) {
                item.classList.remove('cableItem-hide');
            });
            notFound.textContent = '';
        }

        function addToActiveCriteria(criteria) {
            // Agregar el criterio de búsqueda activo a la lista
            activeCriteria.push(criteria);
        }
    
        function removeFromActiveCriteria(criteria) {
            // Remover el criterio de búsqueda activo de la lista
            var index = activeCriteria.indexOf(criteria);
            if (index !== -1) {
                activeCriteria.splice(index, 1);
            }
        }

    });               
    </script>
    
    ";

    return $html;

}

add_shortcode('filtros_cursos_fipi', 'filtros_cursos_fipi_shortcode');
?>