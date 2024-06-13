<?php 
    
include('../../../../wp-load.php');

$id_curso = $_POST["id_curso"];
$id_user = $_POST["id_user"];

$type = $_POST["type"];

$current = str_replace("form_0","",$_POST["current"]);
$total = $_POST["total"];

$pregs = array();
$resps = array();

$arr = array();

foreach($_POST as $res => $index){

    if(str_contains($res, 'preg')){

        $pregs[] = $index;

    }

    if(str_contains($res, 'resp')){

        $resps[] = $index;

    }

}

$i = 0;
while ($i <= count($pregs)) {
    if($pregs[$i] && $resps[$i]){
        $arr[] = array("pregunta" => $pregs[$i], "respuesta" => $resps[$i]);
    }
    $i++;
}

global $wpdb;
$tableNameEnc = $wpdb->prefix . "encuesta_satisfaccion";
$tableNameRes = $wpdb->prefix . "encuesta_satisfaccion_respuestas";

$jsonArrEncode = json_encode($arr, JSON_UNESCAPED_UNICODE);

// verificar si ya existe, si existe se actualiza el current y se obtiene el id
$query = $wpdb->get_results(" SELECT * FROM $tableNameEnc WHERE `id_user` = $id_user AND `id_curso` = $id_curso AND `type` = '$type' ");

if(count( $query ) > 0){

    // se actualiza y obtiene el id
    $lastid = $query[0]->id;
    $wpdb->update($tableNameEnc, array('current_step' => $current),array('id' => $lastid));

} else { 

    // se crea
    date_default_timezone_set('America/Santiago');
    $dateTime = date('d-m-Y h:i:s', time());

    $wpdb->insert($tableNameEnc, array(
        'id'        => 'null',
        'id_user'   => $id_user,
        'id_curso'  => $id_curso,
        'type'      => $type,
        'current_step' => $current,
        'total_steps'  => $total,
        'date_time' => $dateTime
    ));

    $lastid = $wpdb->insert_id;

}

if($lastid){
    // insertar data de encuesta
    global $wpdb;
    $res = $wpdb->insert($tableNameRes, array(
        'id'          => 'null',
        'id_encuesta' => $lastid,
        'step'        => $current,
        'data'        => $jsonArrEncode
    ));

    if($res){
        echo json_encode(array("status" => true));
    } else {
        echo json_encode(array("status" => false));
    }

} else {

    echo json_encode(array("status" => false));

}

?>