<?php 
include('../../../wp-load.php');

$status = array();

$id_user = $_POST["id_user"];
$id_curso = $_POST["id_curso"];
$unidad = $_POST["unidad"];
$cant = $_POST["cant"];

for ($i = 0; $i <= $cant; $i++) {

    $x = $i+1;

    if(isset($_POST["preg_etp_01"][$i]) && isset($_POST["alt_sel"][$i]) && isset($_POST["alt_etp_01_c"][$i])){

        $preg = $_POST["preg_etp_0".$unidad][$i];
        $alt = explode("|",$_POST["alt_sel"][$i]);

        $alt_sel = $alt[0];
        $alt_class = $alt[1];

        $alt_c = explode("|",$_POST["alt_etp_c_".$unidad][$i]);

        $alt_c_string = $alt_c[0];
        $alt_c_class = $alt_c[1];
    
        if($alt_sel === $alt_c_string){

            // si es correcta

            $tableName = $wpdb->prefix . "estado_curso_respuestas";
            $consulta = $wpdb->get_results(" SELECT * FROM $tableName WHERE `id_user` = $id_user AND `id_curso` = $id_curso AND `pregunta` = '$preg' AND `unidad` = $unidad LIMIT 0,1 "); 

            $status[] = array("pregunta" => $preg, "resultado" => "correcta", "alt" => $alt_sel, "alt_c" => $alt_c_string, "class_ok" => $alt_c_class, "intento" => 1);

            if(count($consulta) == 0){

                echo "no existe correcta";

                global $wpdb;
                $insercion = $wpdb->insert($tableName, array(
                    'id'        => 'null',
                    'n_preg'    => $x,
                    'id_user'   => $id_user,
                    'id_curso'  => $id_curso,
                    'pregunta'  => $preg,
                    'respuesta' => $alt_sel,
                    'unidad'    => $unidad,
                    'estado'    => 'correcta',
                    'intento'   => 1
                ));

            } else {

                foreach($consulta as $res){
                    $idPreg = $res->id;
                    $intento = $res->intento;
                    $intento = $intento+1;
                    $estado = $res->estado;
                }

                if($estado != 'correcta'){
                    // se realiza el update
                    $wpdb->update($tableName, array('intento' => 2, 'estado' => 'correcta'),array('id' => $idPreg));
                    $status[] = array("pregunta" => $preg, "resultado" => "correcta", "alt" => $alt_sel, "alt_c" => $alt_c_string, "class_ok" => $alt_c_class, "class_error" => "", "intento" => 2);
                }

            }

        } else {

            // si se incorrecta

            $tableName = $wpdb->prefix . "estado_curso_respuestas";
            $consulta = $wpdb->get_results(" SELECT * FROM $tableName WHERE `id_user` = $id_user AND `id_curso` = $id_curso AND `pregunta` = '$preg' AND `unidad` = $unidad LIMIT 0,1 "); 

            $status[] = array("pregunta" => $preg, "resultado" => "incorrecta", "alt" => $alt_sel, "alt_c" => $alt_c, "class_error" => $alt_class, "class_ok" => $alt_c_class, "intento" => 1);

            if(count($consulta) == 0){

                echo "no existe incorrecta";

                global $wpdb;
                $tableName = $wpdb->prefix . "estado_curso_respuestas";
                $insercion = $wpdb->insert($tableName, array(
                    'id'        => 'null',
                    'n_preg'    => $x,
                    'id_user'   => $id_user,
                    'id_curso'  => $id_curso,
                    'pregunta'  => $preg,
                    'respuesta' => $alt_sel,
                    'unidad'    => $unidad,
                    'estado'    => 'incorrecta',
                    'intento'   => 1
                ));

            } else {

                foreach($consulta as $res){
                    $intento = $res->intento;
                    $intento = $intento+1;
                    $idPreg = $res->id;
                }

                $wpdb->update($tableName, array('intento' => 2),array('id' => $idPreg));
                $status[] = array("pregunta" => $preg, "resultado" => "incorrecta", "alt" => $alt_sel, "alt_c" => $alt_c, "class_error" => $alt_class, "class_ok" => $alt_c_class, "intento" => 2);

            }

        }

    }

}

echo json_encode($status);

?>