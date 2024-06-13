<?php 
// load wordpress
include('../../../../wp-load.php');

error_reporting(E_ALL);
error_reporting(-1);
ini_set('error_reporting', E_ALL);

// post send
if($_POST){

    $id_user = $_POST["id_user"];
    $id_curso = $_POST["id_curso"];
    // $id_unidad = $_POST["id_unidad"];
    $unidad = $_POST["unidad"];

    // $total_unidades = count(getUnitsCourse($id_curso));

    global $wpdb;
    $tableName = $wpdb->prefix . "unidades_dinamico";
    $consulta = $wpdb->get_results(" SELECT * FROM $tableName WHERE `id_user` = $id_user AND `id_curso` = $id_curso AND `n_unidad` = $unidad AND `tipo` = 2 limit 0,1 ");

    if(count($consulta) == 0){

        $data = array(
            'id'        => 'null',
            'id_user'   => $id_user,
            'id_curso'  => $id_curso,
            'n_unidad'  => $unidad,
            'tipo'      => 2,
            'status'    => 1
        );

        $insercion = $wpdb->insert($tableName, $data);

        if($insercion){

            echo json_encode(array("ok" => "si"));
    
        } else {
    
            echo json_encode(array("error" => $wpdb->show_errors));
            marcarError("status.php", "[UNIDAD] error al crear", '', $wpdb->show_errors, '');
    
        }

    } else {

        marcarError("status.php", "[UNIDAD YA EXISTE] error al crear porque ya existe la unidad", '', 'id_usuario: '.$id_user.', id_curso: '.$id_curso.', id_unidad: '.$id_unidad.'', '');

    }


}
?>