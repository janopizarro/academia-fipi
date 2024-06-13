<?php 
include('../../../../wp-load.php');

// error_reporting(E_ALL);
// error_reporting(-1);
// ini_set('error_reporting', E_ALL);

$status = array();

$id_user = $_POST["id_user"];
$id_curso = $_POST["id_curso"];

$id_unidad = $_POST["data_unit"];

$unidad = $_POST["unidad"];
$cant = $_POST["cant"];

$preg_unidad = $_POST["preg_unidad_".$unidad];

$altCorrectas = array();

$preguntas = get_post_meta($id_unidad, 'preguntas_unidad', true);
foreach ($preguntas as $key => $pregunta) { 
    $altCorrectas[] = $pregunta['alternativa_correcta_unidad'];
}

$statusQuestion = array();

for ($i = 0; $i <= $cant; $i++) {

    $x = $i+1;

    if(isset($altCorrectas[$i])){

        if(isset($_POST['alternativa_seleccionada_'.$x])){

            $verificarAlternativas = verificarAlternativas($altCorrectas[$i],$_POST['alternativa_seleccionada_'.$x]);

            if($verificarAlternativas === "NO"){
                $estado = "incorrecta";
                $msg = "¡Te queda 1 intento!";
            } else {
                $estado = "correcta";
                $msg = "";
            }
    
            // se verifica el n de intento 
            $tableName = $wpdb->prefix . "unidades_dinamico_respuestas";
            $consulta = $wpdb->get_results(" SELECT * FROM $tableName WHERE `id_user` = $id_user AND `id_curso` = $id_curso AND `id_unidad` = $id_unidad AND `n_preg` = $x AND `unidad` = $unidad LIMIT 0,1 ");
    
            $intento = 0;
    
            $alts_sel = implode(",",$_POST['alternativa_seleccionada_'.$x]);
            
            if(count($consulta) > 0){
    
                // si existe se actualiza el numero de intentos
                foreach($consulta as $res){
                    $intento = $res->intento;
                    $idPreg  = $res->id;
                    $estadoRegistrado = $res->estado;
                }
    
                if($estado === 'correcta' && $estadoRegistrado === 'incorrecta' && $intento == 1){
    
                    $intento = 2;
                    $wpdb->update($tableName, array('intento' => 2, 'estado' => $estado, 'respuesta_correcta' => $alts_sel, 'respuesta_incorrecta' => ''),array('id' => $idPreg));    
    
                }
                
                if($estadoRegistrado === 'incorrecta' && $intento == 1){
    
                    $intento = 2;
                    $wpdb->update($tableName, array('intento' => 2, 'estado' => $estado, 'respuesta_incorrecta' => $alts_sel),array('id' => $idPreg));    
    
                }
    
            } else {
    
                $intento = 1;
    
                // si no existe se inserta con intento = 1
                global $wpdb;
    
                if($estado === 'correcta'){
                    $data = array(
                        'id'        => 'null',
                        'n_preg'    => $x,
                        'id_user'   => $id_user,
                        'id_curso'  => $id_curso,
                        'id_unidad' => $id_unidad,
                        'pregunta'  => $preg_unidad[$i],
                        'respuesta_incorrecta' => '',
                        'respuesta_correcta' => $alts_sel,
                        'unidad'    => $unidad,
                        'estado'    => $estado,
                        'intento'   => $intento
                    );
                } else {
                    $data = array(
                        'id'        => 'null',
                        'n_preg'    => $x,
                        'id_user'   => $id_user,
                        'id_curso'  => $id_curso,
                        'id_unidad' => $id_unidad,
                        'pregunta'  => $preg_unidad[$i],
                        'respuesta_incorrecta' => $alts_sel,
                        'respuesta_correcta' => '',
                        'unidad'    => $unidad,
                        'estado'    => $estado,
                        'intento'   => $intento
                    );
                }
    
                $insercion = $wpdb->insert($tableName, $data);
     
            }
    
            if($intento == 2){
    
                $msg = "";
    
                // retorna json al front    
                $statusQuestion[] = array(
                    "question_unidad_".$unidad."_".$x => $verificarAlternativas,
                    "estado" => $estado,
                    "intento" => 'intentos-agotados',
                    "class_question" => 'question_unidad_'.$unidad.'_'.$x.'',
                    "question_number" => $x,
                    "message" => $msg
                );
    
            } else {
    
                // retorna json al front    
                $statusQuestion[] = array(
                    "question_unidad_".$unidad."_".$x => $verificarAlternativas,
                    "estado" => $estado,
                    "intento" => $intento,
                    "class_question" => 'question_unidad_'.$unidad.'_'.$x.'',
                    "question_number" => $x,
                    "message" => $msg
                );
    
            }

        }

    }

}

echo json_encode($statusQuestion);

?>