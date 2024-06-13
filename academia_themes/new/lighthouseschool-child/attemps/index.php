<?php 
include('../../../../wp-load.php');

$unit_id = $_POST["unit_id"];
$course_id = $_POST["course_id"];
$user_id = $_POST["user_id"];
$path_absolute = $_POST["path_absolute"];

$nUnidad = $_POST["n_unidad"];

$type = $_POST["type"];

$idUnidad = "";

if($type === "2"){
    $idUnidad = $unit_id;
} else {
    $idUnidad = $unit_id;
}

$MAXINTENTOS = 5;

$dt = new DateTime("now", new DateTimeZone('America/Santiago'));
$fechaHoraActual  = $dt->format('d-m-Y H:i:s');

// se consulta si existe en la base de datos
$tableName = $wpdb->prefix . "unidades_intentos";

$consulta = $wpdb->get_results(" SELECT * FROM $tableName WHERE `id_usuario` = $user_id AND `id_curso` = $course_id AND `id_unidad` = $unit_id ");

// si existe se obtiene el número de intento actual
if(count($consulta) > 0){

    $nIntento = $consulta[0]->n_intento + 1;
    
    if($nIntento < 6){
        global $wpdb;
        $wpdb->update($tableName, array('n_intento' => $nIntento, 'fecha_hora_ultimo_intento' => $fechaHoraActual),array('id_usuario' => $user_id, 'id_curso' => $course_id, 'id_unidad' => $unit_id));
    }

} else {
// si no existe se crea en la base de datos
    $nIntento = 1;
    
    global $wpdb;
    $insercion = $wpdb->insert($tableName, 
        array(
            'id'           => 'null',
            'id_usuario'   => $user_id,
            'id_curso'     => $course_id,
            'id_unidad'    => $idUnidad,
            'n_unidad'     => $nUnidad,
            'n_intento'    => $nIntento,
            'tipo_unidad'  => $type,
            'fecha_hora_ultimo_intento' => $fechaHoraActual
        )
    );
}

$error = "";

if($nIntento < 6){
    if($type === "1"){
        $url = get_post_meta( $course_id, 'curso_resena_0'.$unit_id.'_resena_video_iframe', true);
    } else {
        $url = get_post_meta( $unit_id, 'unidad_resena_video_iframe', true);
    }
} else {
    $url = "";
    $error = "<div style='text-align:center'><img src='".$path_absolute."/images/error-video.png' alt='error video' width='50' style='margin-top: 20px;margin-bottom: 20px;' /><strong style='text-align: center;display: block;font-size: 17px;font-weight: 200;'>¡Ya acabaste tus ".$MAXINTENTOS." visualizaciones!</strong></div>";
}

$arr = ["video" => $url, "n_intento" => $nIntento, "errorAttemp" => $error];


echo json_encode($arr);
?>