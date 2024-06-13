<?php 
session_start();
// load wordpress
include('../../../../wp-load.php');

if($_POST){

    $id_user = $_POST["id_user"];
    $id_curso = $_POST["id_curso"];

    global $wpdb;
    $data = array(
        'id'        => 'null',
        'id_user'   => $id_user,
        'id_curso'  => $id_curso,
        'n_unidad'  => 0,
        'tipo'      => 3,
        'status'    => 1
    );

    $tableName = $wpdb->prefix . "unidades_dinamico";
    $insercion = $wpdb->insert($tableName, $data);

    if($insercion){

        $query = $wpdb->get_results(" SELECT * FROM $tableName WHERE `id_user` = $id_user AND `id_curso` = $id_curso AND `tipo` = 3 ");

        if(count($query) > 0){

            /* * notificacion-administrador-curso-finalizado * */
            insertarEnColaCorreo($GLOBALS['tipoCorreos'][4], $id_user, $id_curso, '');

        }

    }

}
?>

