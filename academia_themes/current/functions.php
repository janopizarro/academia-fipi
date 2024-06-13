<?php 

date_default_timezone_set('America/Santiago');

$GLOBALS['tipoCorreos'] = array(
    "bienvenida-usuario-nuevo",
    "curso-comprado-usuario-existente-flow",
    "notificacion-administrador-curso-comprado-usuario-nuevo-flow",
    "notificacion-administrador-curso-comprado-usuario-existente-flow",    
    "notificacion-administrador-curso-finalizado",
    "bienvenida-usuario-antiguo-curso-nuevo-excel",
    "formulario-contacto",
    "formulario-dudas-curso",
);

function insertarEnColaCorreo($tipo, $id_user, $id_curso, $data_rel){

    global $wpdb;
    $tableName = $wpdb->prefix . "cola_correos";

    // obtener fecha y hora actual de la inserción
    date_default_timezone_set('America/Santiago');
    $dateTime = date('d-m-Y h:i:s', time());

    // se inserta un nuevo error en la base de datos
    $insercion = $wpdb->insert($tableName, array(
        'id'        => 'null',
        'tipo'      => $tipo,
        'id_user'   => $id_user,
        'id_curso'  => $id_curso,
        'data_rel'  => $data_rel,
        'date_time' => $dateTime
    ));

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

// php mailer
// include 'vendor/autoload.php';

// end php mailer

add_action('admin_head', 'my_custom_fonts');

function my_custom_fonts() {
  echo '<style>
    #info_curso_presencial{
        background: #e6efde;
    }
    #info_curso_resenas{
        background: #f5e6cc87;
    }
    .cmb2-id-adicional-material-apoyo-01, .cmb2-id-adicional-material-apoyo-02, .cmb2-id-adicional-material-apoyo-03{
        padding-bottom: 60px !important;
        margin-bottom: 60px !important;
        border-bottom: 2px dashed orange !important;
    }
    #info_curso_preguntas_etapa_01{
        background: #00AEBC;
    }
    #info_curso_preguntas_etapa_02{
        background: #eab830;
    }
    #info_curso_preguntas_etapa_03{
        background: #b2d017;
    }
    #info_curso_preguntas_etapa_04{
        background: #eab4e1;
    }
  </style>';
}

/* * menu wordpress * */
function wpb_menu_principal() {
    register_nav_menu('menu_principal',__( 'Menú Principal' ));
}

add_action( 'init', 'wpb_menu_principal' );

function wpb_menu_lateral() {
    register_nav_menu('menu_lateral',__( 'Menú Lateral' ));
}

add_action( 'init', 'wpb_menu_lateral' );

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

require_once dirname( __FILE__ ) . '/table.php';
require_once dirname( __FILE__ ) . '/table-dinamics.php';

require_once dirname( __FILE__ ) . '/encuesta-sincronica-table.php';
require_once dirname( __FILE__ ) . '/encuesta-sincronica-table-chart.php';

require_once dirname( __FILE__ ) . '/encuesta-asincronica-table.php';
require_once dirname( __FILE__ ) . '/encuesta-asincronica-table-chart.php';

function add_author_support_to_posts() {
    add_post_type_support( 'transacciones', 'author' ); 
 }
 add_action( 'init', 'add_author_support_to_posts' );

 /*
 * Add Event Column 
 */
function users_events_column( $cols ) {
    $cols['transacciones'] = 'Transacciones';   
    return $cols;
  }
  
  /*
   * Print Event Column Value  
   */ 
  function user_events_column_value( $value, $column_name, $id ) {
    if( $column_name == 'transacciones' ) {
      global $wpdb;
      $count = (int) $wpdb->get_var( $wpdb->prepare(
        "SELECT COUNT(ID) FROM $wpdb->posts WHERE 
         post_type = 'transacciones' AND post_status = 'private' AND post_author = %d",
         $id
      ) );
      return $count;
    }
  }
  
  add_filter( 'manage_users_custom_column', 'user_events_column_value', 10, 3 );
  add_filter( 'manage_users_columns', 'users_events_column' );

// require_once dirname( __FILE__ ) . '/ajustes-sitio.php';

flush_rewrite_rules();

/*
 * * */

function verificarSesion(){
    if(!isset($_SESSION['user_fipi'])){
        echo "<script> location.href = '".home_url()."/login'; </script>";
    }
}

function getDataSession($tipo){
    if(isset($_SESSION['user_fipi'])){
        return $_SESSION['user_fipi'][$tipo];
    } else {
        return false;;
    }
}

// add_action( 'cmb2_admin_init', 'yourprefix_register_taxonomy_metabox' ); 
// /** 
//  * Hook in and add a metabox to add fields to taxonomy terms 
//  */ 
// function yourprefix_register_taxonomy_metabox() { 
//     $prefix = 'yourprefix_term_'; 
 
//     /** 
//      * Metabox to add fields to categories and tags 
//      */ 
//     $cmb_term = new_cmb2_box( array( 
//         'id'               => $prefix . 'edit', 
//         'title'            => esc_html__( 'Category Metabox', 'cmb2' ), // Doesn't output for term boxes 
//         'object_types'     => array( 'term' ), // Tells CMB2 to use term_meta vs post_meta 
//         'taxonomies'       => array( 'category', 'post_tag' ), // Tells CMB2 which taxonomies should have these fields 
//         // 'new_term_section' => true, // Will display in the "Add New Category" section 
//     ) ); 
 
//     $cmb_term->add_field( array( 
//         'name'     => esc_html__( 'Extra Info', 'cmb2' ), 
//         'desc'     => esc_html__( 'field description (optional)', 'cmb2' ), 
//         'id'       => $prefix . 'extra_info', 
//         'type'     => 'title', 
//         'on_front' => false, 
//     ) ); 
 
//     $cmb_term->add_field( array( 
//         'name' => esc_html__( 'Term Image', 'cmb2' ), 
//         'desc' => esc_html__( 'field description (optional)', 'cmb2' ), 
//         'id'   => $prefix . 'avatar', 
//         'type' => 'file', 
//     ) ); 
 
//     $cmb_term->add_field( array( 
//         'name' => esc_html__( 'Arbitrary Term Field', 'cmb2' ), 
//         'desc' => esc_html__( 'field description (optional)', 'cmb2' ), 
//         'id'   => $prefix . 'term_text_field', 
//         'type' => 'text', 
//     ) ); 
 
// } 

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

function redirect($tiempo,$page){
    echo "
    <script>
    setTimeout(function () {
        window.history.back();
    },".$tiempo.");
    </script>
    ";
}

/* Create Staff Member User Role */
add_role(
    'alumno', //  System name of the role.
    __( 'Alumno'  ), // Display name of the role.
    array(
        'read'  => false,
        'delete_posts'  => false,
        'delete_published_posts' => false,
        'edit_posts'   => false,
        'publish_posts' => false,
        'upload_files'  => false,
        'edit_pages'  => false,
        'edit_published_pages'  =>  false,
        'publish_pages'  => false,
        'delete_published_pages' => false, // This user will NOT be able to  delete published pages.
    )
);

function wpse66094_no_admin_access() {
    $redirect = isset( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : home_url( '/' );
    global $current_user;
    $user_roles = $current_user->roles;
    $user_role = array_shift($user_roles);
    if($user_role === 'alumno'){
        exit( wp_redirect( $redirect ) );
    }
}

add_action( 'admin_init', 'wpse66094_no_admin_access', 100 );

function marcarError($archivo, $accion, $error, $relacionador, $data){

    global $wpdb;
    $tableName = $wpdb->prefix . "errores_academia";

    // obtener fecha y hora actual del error
    date_default_timezone_set('America/Santiago');
    $date = date('Y-m-d h:i:s', time());

    // se inserta un nuevo error en la base de datos
    $insercion = $wpdb->insert($tableName, array(
        'id'      => 'null',
        'archivo' => $archivo,
        'accion'  => $accion,
        'error'   => $error,
        'relacionador' => $relacionador,
        'data'    => $data,
        'fecha'   => $date
    ));

}

require_once dirname( __FILE__ ) . '/send-email.php';
require_once dirname( __FILE__ ) . '/ajustes-sitio.php';

function letras(){
    $letras = array("","a","b","c","d","e","f","g","h","i","j");
    return $letras;
}

/*
 * función para obtener preguntas de la unidad */

function getPreguntasUnidad($unidad){

    // se obtienen las preguntas de la unidad
    $preguntas = get_post_meta(get_the_ID(), 'preguntas_unidad_'.$unidad, true);
    // se consulta si existen preguntas
    if(!empty($preguntas)){

        $html = '<form id="form_etp_0'.$unidad.'" method="post">';

        $docs = get_post_meta(get_the_ID(), 'curso_resena_0'.$unidad.'_docs', true);
        
        if($docs && count($docs) > 0){

            $html .= '<strong style="padding-left: 30px;">Lectura obligatoria:</strong><small style="display: block; font-size: 14px; padding-left: 30px;">'.get_post_meta(get_the_ID(), 'adicional_lectura_obligatoria_0'.$unidad, true).'</small><nav style="display: flex; padding-left: 18px;">';

            foreach($docs as $doc){
                $html .= '<li style="list-style:none; display:block; padding: 10px;"><a href="'.$doc.'" target="_blank" rel="noopener noreferrer"><img style="width:22px;" src="'.get_template_directory_uri().'/images/doc.png" /></a></li>';
            }

            $html .= '</nav>';

        }

        $html .= '<ul class="pregunta"><strong style="padding-left: 30px; color: #bd2bbd;">Evaluación formativa unidad 0'.$unidad.'</strong>';

        $x = 0;

        foreach ($preguntas as $key => $pregunta) { $x++;

            // print_r($preguntas);

            $i = $x - 1;

            // pregunta
            $pregunta_ = esc_html($pregunta['pregunta_unidad_'.$unidad]);

            // alternativas 
            $alternativas_ = $pregunta['alternativa_unidad_'.$unidad];

            // alternativas correctas 
            $alternativa_correcta = count($pregunta['alternativa_correcta_unidad_'.$unidad]);

            // verificar si la unidad ya está lista
            if(!verifyUnitEnd($unidad, getDataSession("id"), get_the_ID())){
                $classQuestion = checkStatusQuestion($unidad, $x, getDataSession("id"), get_the_ID(), $i, 'question');
            } else {
                $classQuestion = "";
            }

            $checkboxStatus = checkStatusQuestion($unidad, $x, getDataSession("id"), get_the_ID(), $i, 'checkbox');
            $selectedAlternative = checkStatusQuestion($unidad, $x, getDataSession("id"), get_the_ID(), $i, 'selected-alternative');
            $incorrectAlternative = checkStatusQuestion($unidad, $x, getDataSession("id"), get_the_ID(), $i, 'incorrect-alternative');
            $message = checkStatusQuestion($unidad, $x, getDataSession("id"), get_the_ID(), $i, 'message');

            if($message != ""){
                $helperClass = "helper_visible";
            } else {
                $helperClass = "";
            }
            
            $html .= '<li class="invoices--flex question_unidad_'.$unidad.'_'.$x.' '.$classQuestion.'">'.$pregunta_.' <b class="helper_grupo helper_grupo--info helper_grupo_unidad_'.$unidad.'_'.$x.' '.$helperClass.'" style="display: none;">'.$message.'</b></li>';

            $html .= '<ul>';

            $i = 0;

            foreach($alternativas_ as $alternativa){ $i++;

                if(strpos($selectedAlternative, $alternativa) !== false){
                    $checked_ = "checked";
                    $checked_style = "color: green";
                } else{
                    $checked_ = "";
                    $checked_style = "";
                }

                if(strpos($incorrectAlternative, $alternativa) !== false){
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
                
                    $html .= '<input type="checkbox" '.$checked_.' '.$checked_err.' id="alernativa_'.$i.'_preg_'.$x.'_unidad_'.$unidad.'" class="grupo_unidad_'.$unidad.'_'.$x.'" name="alternativa_seleccionada_'.$x.'[]" value="'.$alternativa.'" style="width: 20px; box-shadow: none; height: 15px; '.$checkboxStatus.'">';

                    $html .= '<label for="alernativa_'.$i.'_preg_'.$x.'_unidad_'.$unidad.'" style="'.$checkboxStatus.' '.$checked_style_.'"><span style="    background: #bc73bd;
                    min-width: 10px;
                    min-height: 10px;
                    display: inline-block;
                    text-align: center;
                    width: 20px;
                    border-radius: 10px;
                    color: #FFFFFF;">'.letras()[$i]."</span> ".$alternativa.'</label>';

                $html .= '</li>';

            }

            $html .= '</ul>';

            $html .= '<script>verificarLimiteAlertnativa('.$alternativa_correcta.',"grupo_unidad_'.$unidad.'_'.$x.'");</script>';
            $html .= '<input type="hidden" class="cantMinima_unidad_'.$unidad.'_'.$x.'" value="'.$alternativa_correcta.'">';
            $html .= '<input type="hidden" name="preg_unidad_'.$unidad.'[]" value="'.$pregunta_.'">';

        }

        $html .= '</ul>';

        if(checkStatusUnit($unidad, getDataSession("id"), get_the_ID())){

            $html .= '<button class="button finalizar_unidad finalizar_unidad_'.$unidad.'" type="button" data-id="'.$unidad.'" data-cant="'.count($preguntas).'" style="font-size: 15px; margin: 25px;">Finalizar</button>';

        } else {

            updateStatusCourse($unidad, getDataSession("id"), get_the_ID());

        }

        $html .= '<p id="load_unit_0'.$unidad.'" style="display:none; font-size: 13px; margin: 10px; color: #3ca4ce; font-family: inherit;"><img src="'.get_template_directory_uri().'/images/loading.svg" style="width: 20px; margin-right: 7px;"/> Actualizando estado...</p>';

        $html .= '
            <input type="hidden" class="idUser" value="'.getDataSession("id").'">
            <input type="hidden" class="idCurso" value="'.get_the_ID().'">
            <input type="hidden" class="template" value="'.get_template_directory_uri().'">
        ';

        $html .= '</form>';

        $docs_apoyo = get_post_meta(get_the_ID(), 'curso_resena_0'.$unidad.'_material_apoyo', true);
        $docs_apoyo_docs = get_post_meta(get_the_ID(), 'curso_resena_0'.$unidad.'_material_apoyo_docs', true);

        if(isset($docs_apoyo) || isset($docs_apoyo_docs)){

            $html .= '<strong style="padding-left: 30px; padding-top: 10px; border-top: 1px solid #dedede; margin-top: 15px; display: block;">Material de apoyo:</strong><small style="display: block; font-size: 14px; padding-left: 30px;">'.get_post_meta(get_the_ID(), 'adicional_material_apoyo_0'.$unidad, true).'</small><nav style="display: flex; padding-left: 18px;">';

            if(isset($docs_apoyo) && $docs_apoyo != ""){

                foreach($docs_apoyo as $doc_apoyo){
                    $html .= '<li style="list-style:none; display: block; padding: 10px;"><a href="'.$doc_apoyo.'" target="_blank" rel="noopener noreferrer"><img style="width:22px;" src="'.get_template_directory_uri().'/images/doc_apoyo.png" /></a></li>';
                }

            }

            if(isset($docs_apoyo_docs) && $docs_apoyo_docs != ""){

                foreach($docs_apoyo_docs as $doc){
                    $html .= '<li style="list-style:none; display:block; padding: 10px;"><a href="'.$doc.'" target="_blank" rel="noopener noreferrer"><img style="width:22px;" src="'.get_template_directory_uri().'/images/doc.png" /></a></li>';
                }

            }

            $html .= '</nav>';

        }

        return $html;

    } else {

        return '<i>No hay preguntas para la unidad: 0'.$unidad.'</i>';

    }

}

// require_once dirname( __FILE__ ) . '/includes/getPreguntasUnidad.php';

function checkStatusQuestion($unitCourse, $questionNumber, $userId, $courseId, $index, $type){

    switch ($type) {

        case 'checkbox':

            global $wpdb;
            $tableName = $wpdb->prefix . "estado_curso_respuestas";

            $consulta = $wpdb->get_results(" SELECT * FROM $tableName WHERE `id_user` = $userId AND `id_curso` = $courseId AND `n_preg` = $questionNumber AND `unidad` = $unitCourse LIMIT 0,1 ");

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
            $tableName = $wpdb->prefix . "estado_curso_respuestas";

            $consulta = $wpdb->get_results(" SELECT * FROM $tableName WHERE `id_user` = $userId AND `id_curso` = $courseId AND `n_preg` = $questionNumber AND `unidad` = $unitCourse LIMIT 0,1 ");

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
            $tableName = $wpdb->prefix . "estado_curso_respuestas";

            $consulta = $wpdb->get_results(" SELECT * FROM $tableName WHERE `id_user` = $userId AND `id_curso` = $courseId AND `n_preg` = $questionNumber AND `unidad` = $unitCourse LIMIT 0,1 ");

            foreach($consulta as $res){
                if($res->estado === "correcta"){
                    $ok_ = $res->respuesta_correcta;
                    return $ok_;
                }
                if($res->estado === "incorrecta" && $res->intento == 2){

                    $preguntas = get_post_meta($courseId, 'preguntas_unidad_'.$unitCourse.'', true);
                    foreach ($preguntas as $key => $pregunta) { 
                        $altCorrectas[] = $pregunta['alternativa_correcta_unidad_'.$unitCourse];
                    }

                    $correctas = implode(",",$altCorrectas[$index]);
                    return $correctas;

                }
            }

            break;

        case 'incorrect-alternative':

            // verificar cual fue el ultimo estado registrado
            global $wpdb;
            $tableName = $wpdb->prefix . "estado_curso_respuestas";

            $consulta = $wpdb->get_results(" SELECT * FROM $tableName WHERE `id_user` = $userId AND `id_curso` = $courseId AND `n_preg` = $questionNumber AND `unidad` = $unitCourse LIMIT 0,1 ");

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
            $tableName = $wpdb->prefix . "estado_curso_respuestas";

            $consulta = $wpdb->get_results(" SELECT * FROM $tableName WHERE `id_user` = $userId AND `id_curso` = $courseId AND `n_preg` = $questionNumber AND `unidad` = $unitCourse LIMIT 0,1 ");

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

function checkStatusUnit($unitCourse, $userId, $courseId){
    
    global $wpdb;
    $tableName = $wpdb->prefix . "estado_curso_respuestas";

    $consulta = $wpdb->get_results(" SELECT * FROM $tableName WHERE `id_user` = $userId AND `id_curso` = $courseId AND `unidad` = $unitCourse ");

    $status = array();

    foreach($consulta as $res){
        if($res->estado === "incorrecta" && $res->intento == 1){
            $status[] = 1;
        } elseif ($res->estado === "correcta" && $res->intento == 1){
            $status[] = 1;
        } else {
            $status[] = 0;
        }
    }

    if(in_array(0,$status)){
        return false;
    } else {
        return true;
    }

}

function checkStatusUnitNew($unitCourse, $userId, $courseId){
    
    global $wpdb;
    $tableName = $wpdb->prefix . "unidades_dinamico_respuestas";

    $consulta = $wpdb->get_results(" SELECT * FROM $tableName WHERE `id_user` = $userId AND `id_curso` = $courseId AND `unidad` = $unitCourse ");

    $status = array();

    foreach($consulta as $res){
        if($res->estado === "incorrecta" && $res->intento == 1){
            $status[] = 1;
        } elseif ($res->estado === "correcta" && $res->intento == 1){
            $status[] = 1;
        } else {
            $status[] = 0;
        }
    }

    if(in_array(0,$status)){
        return false;
    } else {
        return true;
    }

}

/*
 * Verificar alternativas seleccionadas con las correctas * */

function verificarAlternativas($correctas,$seleccionadas){

    /* * correctas * */
    $correctasArray = array();

    foreach($correctas as $correcta){
        $correctasArray[] = $correcta;
    }
    /* * end correctas * */

    /* * seleccionadas * */
    $seleccionadasArray = array();

    foreach($seleccionadas as $seleccionada){
        $seleccionadasArray[] = $seleccionada;
    }
    /* * end seleccionadas * */

    $seleccionadasImplode = implode(",",$seleccionadasArray);
    $correctasImplode = implode(",",$correctasArray);

    // se compara si son exactamente iguales para retornar 
    if($seleccionadasImplode === $correctasImplode){

        return "SI";

    } else {

        return "NO";

    }

}

/*
 * Acualización de estado de unidad en la base de datos * */

function updateStatusCourse($unidad, $userId, $courseId){

    global $wpdb;
    $tableName = $wpdb->prefix . "estado_curso";
    $consulta = $wpdb->get_results(" SELECT * FROM $tableName WHERE `id_user` = $userId AND `id_curso` = $courseId limit 0,1 ");

    if(count($consulta) > 0){

        // se actualiza
        switch ($unidad) {
            case '1':
                $wpdb->update($tableName, array('unidad_1' => 1),array('id_user' => $userId, 'id_curso' => $courseId));
                break;
            case '2':
                $wpdb->update($tableName, array('unidad_2' => 1),array('id_user' => $userId, 'id_curso' => $courseId));
                break;
            case '3':
                $wpdb->update($tableName, array('unidad_3' => 1),array('id_user' => $userId, 'id_curso' => $courseId));
                break;
            case '4':
                $wpdb->update($tableName, array('unidad_4' => 1),array('id_user' => $userId, 'id_curso' => $courseId));
                break;
        }

    }

}

function verifyUnitEnd($unidad, $userId, $courseId){

    global $wpdb;
    $tableName = $wpdb->prefix . "estado_curso";

    $unit = "unidad_".$unidad;

    $consulta = $wpdb->get_results(" SELECT * FROM $tableName WHERE `id_user` = $userId AND `id_curso` = $courseId AND $unit = 1 limit 0,1 ");

    if(count($consulta) > 0){

        return true;

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

function getNameCourse($id){

    global $wpdb;
    $tableName = $wpdb->prefix . "posts";

    $consulta = $wpdb->get_results(" SELECT * FROM $tableName WHERE `ID` = $id limit 0,1 ");

    if(count($consulta) > 0){

        foreach($consulta as $res){
            return $res->post_title;
        }

    } else {

        return 'no encontrado';

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



/*
 * carga alumnos * */
require_once( __DIR__ . '/carga-alumnos/index.php');

/*
 * data alumnos * */
require_once( __DIR__ . '/data-alumnos/index.php');

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


add_filter( 'gettext', 'register_text_01' );
add_filter( 'ngettext', 'register_text_01' );
function register_text_01( $translated ) {
    $translated = str_ireplace(
        'Nombre de Usuario o Correo electrónico',
        'Correo electrónico',
        $translated
    );
    return $translated;
}

add_filter( 'gettext', 'register_text_02' );
add_filter( 'ngettext', 'register_text_02' );
function register_text_02( $translated ) {
    $translated = str_ireplace(
        'Generate Password',
        'Generar contraseña',
        $translated
    );
    return $translated;
}

add_filter( 'gettext', 'register_text_03' );
add_filter( 'ngettext', 'register_text_03' );
function register_text_03( $translated ) {
    $translated = str_ireplace(
        'Save Password',
        'Actualizar',
        $translated
    );
    return $translated;
}

add_filter( 'gettext', 'register_text_04' );
add_filter( 'ngettext', 'register_text_04' );
function register_text_04( $translated ) {
    $translated = str_ireplace(
        'Enter your new password below or generate one.',
        'Ingresa una nueva contraseña o genera una.',
        $translated
    );
    return $translated;
}

add_filter( 'gettext', 'register_text_05' );
add_filter( 'ngettext', 'register_text_05' );
function register_text_05( $translated ) {
    $translated = str_ireplace(
        'Introduce tu nombre de usuario o dirección de correo electrónico. Recibirás un mensaje de correo electrónico con instrucciones sobre cómo restablecer su contraseña.',
        'Introduce tu dirección de correo electrónico. Recibirás un correo electrónico con instrucciones sobre cómo restablecer su contraseña.',
        $translated
    );
    return $translated;
}


function getUnitsCourse( $idCurso ){

    $args = array(
        'numberposts' => -1, 'post_type' => 'unidad', 'meta_query' => array(array('key' => 'unidad_curso', 'value' => $idCurso))
    );
    
    return get_posts($args);
}

function getUnitsCompleted($id_user, $id_course){

    global $wpdb;
    $tableName = $wpdb->prefix . "unidades_dinamico_respuestas";

    $completed = array();

    $consulta = $wpdb->get_results(" SELECT * FROM $tableName WHERE `id_user` = $id_user AND `id_curso` = $id_course GROUP BY `id_unidad` ");

    if(count($consulta) > 0){

        foreach($consulta as $res){

            $completed[] = $res->id_unidad;

        }

        // $res = implode(",",$completed);

        return $completed;

    } else {

        return array();

    }

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

function haveLibrary( $id_curso ){
    
    $args = array(
        'numberposts' => -1, 'post_type' => 'biblioteca', 'meta_query' => array(array('key' => 'biblioteca_curso', 'value' => $id_curso))
    );
    
    return get_posts($args);

}

function haveEvaluation( $id_curso, $id_user ){
    
    $args = array(
        'post_type' => 'evaluacion',
    );

    $res = new WP_Query($args);

    if($res->have_posts() > 0){
        return true;
    } else {
        return false;
    }

}

function haveFilesUnitCourse( $id ){

    $files = array();

    // OBTENER LOS ARCHIVOS RELACIONADOS DE TODAS LAS UNIDADES DINÁMICAS
    if(count(getUnitsCourse(get_post_meta( $id, 'biblioteca_curso', true ))) > 0){

        foreach(getUnitsCourse(get_post_meta( $id, 'biblioteca_curso', true )) as $res){
            $ID = $res->ID;
    
            $docs = get_post_meta($ID, 'unidad_resena_docs', true);
    
            foreach($docs as $doc){
                $files[] = $doc;
            }
    
        }

        return $files;

    } else {

        return array();

    }


}

function haveLinksUnitCourse( $id ){

    $links = array();

    if(count(getUnitsCourse(get_post_meta( $id, 'biblioteca_curso', true ))) > 0){
            
        // OBTENER LOS ARCHIVOS RELACIONADOS DE TODAS LAS UNIDADES DINÁMICAS
        foreach(getUnitsCourse(get_post_meta( $id, 'biblioteca_curso', true )) as $res){
            $ID = $res->ID;

            $docs_links = get_post_meta($ID, 'unidad_resena_docs', true);

            foreach($docs_links as $doc_link){
                $links[] = $doc_link;
            }

        }

        return $links;

    } else {

        return array();

    }

}

// Customize login header text.
add_filter( 'login_headertext', 'customize_login_headertext' );

function customize_login_headertext( $headertext ) {
  $headertext = esc_html__( 'Add custom login page text here', 'plugin-textdomain' );
  return $headertext;
}

function formatearFecha( $fecha ){
    setlocale(LC_TIME, 'es_ES', 'Spanish_Spain', 'Spanish');
    $formato = "Y-m-d";
    $fechaFormateada = date($formato, strtotime($fecha));
    return $fechaFormateada;
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

function estadoActualFecha( $dateInicial, $dateTermino, $type ){
    setlocale(LC_TIME, 'es_ES', 'Spanish_Spain', 'Spanish');

    $dateAInicial = date("Y-m-d", strtotime($dateInicial));
    $dateATermino = date("Y-m-d", strtotime($dateTermino));

    $startDateInicial = DateTime::createFromFormat('Y-m-d', $dateAInicial);
    $startDateTermino = DateTime::createFromFormat('Y-m-d', $dateATermino);

    $endDate = DateTime::createFromFormat('Y-m-d', date('Y-m-d'));

    $wait_icon = get_template_directory_uri()."/images/"."wait-course.svg";
    $wait_style = "width: 18px;margin-right: -1px;top: -2px;position: relative;";

    $calendar_icon = get_template_directory_uri()."/images/"."calendario.svg";
    $calendar_style = "width: 14px;top: -3px;left: 2px;position: relative;margin-right: 4px;";

    $finish_icon = get_template_directory_uri()."/images/"."finish-course.svg";
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

function verificarTutorial( $id_alumno, $id_tutorial ){

    // tutorial:
    // 1 = bienvenida

    global $wpdb;
    $tableName = $wpdb->prefix . "tutoriales";

    $query = $wpdb->get_results(" SELECT * FROM $tableName WHERE `id_alumno` = $id_alumno AND `id_tutorial` = $id_tutorial ");

    if(count($query) === 0){
        // se inserta en la bbdd

        // obtener fecha y hora actual
        date_default_timezone_set('America/Santiago');
        $date = date('Y-m-d h:i:s', time());

        // se inserta un nuevo error en la base de datos
        $insercion = $wpdb->insert($tableName, array(
            'id'          => 'null',
            'id_alumno'   => $id_alumno,
            'id_tutorial' => $id_tutorial,
            'fecha'       => $date
        ));

        return true;

    } else {

        return false;

    }

}

/*
 * obtener preguntas * */

function obtenerPreguntas($taxonomy_id, $post_type, $type){

    $args = array(
        'post_type' => 'encuesta_'.$post_type.'',
        'order'     => 'ASC',
        'orderby'   => 'ID',
        'showposts' => -1,
        'tax_query' => array(
            array(
                'taxonomy' => 'categoria_encuesta_'.$type.'', 
                'terms'    => array($taxonomy_id)
            )
        )
    );
    
    $loop = new WP_Query( $args );

    return $loop;

}

function obtenerPreguntasDesc($taxonomy_id, $post_type, $type){

    $args = array(
        'order'     => 'ASC',
        'orderby'   => 'ID',
        'showposts' => -1,
        'post_type' => 'encuesta_'.$post_type.'',
        'ignore_sticky_posts' => true,
        'tax_query' => array(
            array(
                'taxonomy' => 'categoria_encuesta_'.$type.'', 
                'terms'    => array($taxonomy_id)
            )
        )
    );
    
    $loop = new WP_Query( $args );

    // var_dump($loop->request);
    
    return $loop;

}

function slugify($text, $divider = '-'){
  // replace non letter or digits by divider
  $text = preg_replace('~[^\pL\d]+~u', $divider, $text);

  // transliterate
  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

  // remove unwanted characters
  $text = preg_replace('~[^-\w]+~', '', $text);

  // trim
  $text = trim($text, $divider);

  // remove duplicate divider
  $text = preg_replace('~-+~', $divider, $text);

  // lowercase
  $text = strtolower($text);

  if (empty($text)) {
    return 'n-a';
  }

  return $text;
}

/**
 * Proper ob_end_flush() for all levels
 *
 * This replaces the WordPress `wp_ob_end_flush_all()` function
 * with a replacement that doesn't cause PHP notices.
 */
remove_action( 'shutdown', 'wp_ob_end_flush_all', 1 );
add_action( 'shutdown', function() {
   while ( @ob_end_flush() );
} );

/*
 *
 * Visualizaciones una vez se termina el curso * * */

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

/*
 * resumen alumnos * */
require_once( __DIR__ . '/features/resumen-alumnos.php');

/*
 * reporte general encuestas * */
require_once( __DIR__ . '/features/reporte-general-encuestas.php');

/*
 * ex alumnos FIPI certficados * */
// require_once( __DIR__ . '/features/ex-alumnos-certificados.php');