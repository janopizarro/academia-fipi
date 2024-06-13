<?php 
include('../../../wp-load.php');

$id_user = $_POST["id_user"];
$id_curso = $_POST["id_curso"];
$unidad = $_POST["unidad"];

global $wpdb;
$tableName = $wpdb->prefix . "estado_curso";

if($unidad == 1){
    $wpdb->update($tableName, array('unidad_1' => 1 ),array('id_user' => $id_user, 'id_curso' => $id_curso));
}

if($unidad == 2){
    $wpdb->update($tableName, array('unidad_2' => 1 ),array('id_user' => $id_user, 'id_curso' => $id_curso));
}

if($unidad == 3){
    $wpdb->update($tableName, array('unidad_3' => 1 ),array('id_user' => $id_user, 'id_curso' => $id_curso));
}

if($unidad == 4){
    $wpdb->update($tableName, array('unidad_4' => 1 ),array('id_user' => $id_user, 'id_curso' => $id_curso));
}


?>