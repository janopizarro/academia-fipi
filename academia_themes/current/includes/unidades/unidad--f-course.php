<?php 
session_start();
// load wordpress
include('../../../../../wp-load.php');

if($_POST){

    $userId = $_POST["userId"];
    $courseId = $_POST["courseId"];

    global $wpdb;
    $tableName = $wpdb->prefix . "estado_curso";
    $consulta = $wpdb->get_results(" SELECT * FROM $tableName WHERE `id_user` = $userId AND `id_curso` = $courseId LIMIT 0,1 "); 

    if(count($consulta) > 0){

        foreach($consulta as $res){
    
            $idEstado = $res->id;
            $email_admin = $res->email_admin;

        }

        if($email_admin == 0){

            /* * notificacion-administrador-curso-finalizado * */
            insertarEnColaCorreo($GLOBALS['tipoCorreos'][4], $userId, $courseId, '');
        
            $introEstado = $wpdb->update($tableName, array('email_admin' => 1),array('id' => $idEstado));
        
        }
        
    } else {

        echo 'aun-no';

    }

}
?>

