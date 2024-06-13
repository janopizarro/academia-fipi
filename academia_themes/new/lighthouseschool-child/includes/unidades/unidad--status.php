<?php 
// load wordpress
include('../../../../../wp-load.php');

// error_reporting(E_ALL);
// error_reporting(-1);
// ini_set('error_reporting', E_ALL);

// post send
if($_POST){

    $userId = $_POST["userId"];
    $courseId = $_POST["courseId"];
    $unidad = $_POST["unit"];

    global $wpdb;
    $tableName = $wpdb->prefix . "estado_curso";
    $consulta = $wpdb->get_results(" SELECT * FROM $tableName WHERE `id_user` = $userId AND `id_curso` = $courseId limit 0,1 ");

    if(count($consulta) > 0){

        // se actualiza
        if($unidad == 1) {
            $update = $wpdb->update($tableName, array('unidad_1' => 1),array('id_user' => $userId, 'id_curso' => $courseId));
        }
        if($unidad == 2) {
            $update = $wpdb->update($tableName, array('unidad_2' => 1),array('id_user' => $userId, 'id_curso' => $courseId));
        }
        if($unidad == 3) {
            $update = $wpdb->update($tableName, array('unidad_3' => 1),array('id_user' => $userId, 'id_curso' => $courseId));
        }
        if($unidad == 4) {
            $update = $wpdb->update($tableName, array('unidad_4' => 1),array('id_user' => $userId, 'id_curso' => $courseId));
        }

    }

    if($update){

        echo json_encode(array("ok" => "si"));

    } else {

        echo json_encode(array("error" => $wpdb->show_errors));

    }

}


?>