<?php 
// load wordpress
include('../../../../wp-load.php');

// post send
if($_POST){

    // $get = getPercentaje($_POST['courseId'], $_POST['userId']);

    $id_user = $_POST["id_user"];
    $id_course = $_POST["id_curso"];

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

    if($percentage){

        echo number_format($percentage);

    } else {

        echo "error porcentaje";

    }























    // obtener el total de preguntas de las unidades


    // // unidad 01
    // $preguntas_1 = get_post_meta($courseId, 'preguntas_unidad_1', true);
    // foreach ($preguntas_1 as $key_1 => $preg_1) {
    //     $pregunta_1 = esc_html($preg_1['pregunta_unidad_1']);
    //     $total[] = $pregunta_1;
    // }

    // // unidad 02
    // $preguntas_2 = get_post_meta($courseId, 'preguntas_unidad_2', true);
    // foreach ($preguntas_2 as $key_2 => $preg_2) {
    //     $pregunta_2 = esc_html($preg_2['pregunta_unidad_2']);
    //     $total[] = $pregunta_2;
    // }

    // // unidad 03
    // $preguntas_3 = get_post_meta($courseId, 'preguntas_unidad_3', true);
    // foreach ($preguntas_3 as $key_3 => $preg_3) {
    //     $pregunta_3 = esc_html($preg_3['pregunta_unidad_3']);
    //     $total[] = $pregunta_3;
    // }

    // // unidad 04
    // $preguntas_4 = get_post_meta($courseId, 'preguntas_unidad_4', true);
    // foreach ($preguntas_4 as $key_4 => $preg_4) {
    //     $pregunta_4 = esc_html($preg_4['pregunta_unidad_4']);
    //     $total[] = $pregunta_4;
    // }

    // respondidas
    

}


?>