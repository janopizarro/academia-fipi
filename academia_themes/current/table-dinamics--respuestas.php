<?php 
include('../../../wp-load.php');

// print_r($_POST);

$id_user = $_POST["user"];
$id_curso = $_POST["curso"];
$unidad = $_POST["unidad"];

$tableName = $wpdb->prefix . "unidades_dinamico_respuestas";
$consulta = $wpdb->get_results(" SELECT * FROM $tableName WHERE `id_user` = $id_user AND `id_curso` = $id_curso AND `unidad` = $unidad");

$consultaIncorrectas = $wpdb->get_results(" SELECT * FROM $tableName WHERE `id_user` = $id_user AND `id_curso` = $id_curso AND `unidad` = $unidad AND `intento` = 2 AND `estado` LIKE 'incorrecta'");
$consultaCorrectas = $wpdb->get_results(" SELECT * FROM $tableName WHERE `id_user` = $id_user AND `id_curso` = $id_curso AND `unidad` = $unidad AND `estado` LIKE 'correcta'");

echo "<span style='padding: 10px;font-size: 12px;background: #c9ffff;width: 100%;display: block;margin-bottom: 12px;color: #006177;border: 1px solid #00617763;'>De <strong>".count($consulta)." preguntas</strong>, <strong>".count($consultaCorrectas)."</strong> respuestas correctas y <strong>".count($consultaIncorrectas)."</strong> respuestas incorrectas.</span>";

if(count($consulta) > 0){

    foreach($consulta as $res){

        if($res->intento == 2 && $res->estado === "incorrecta"){
            $respuesta = $res->respuesta_incorrecta;
        } else {
            $respuesta = $res->respuesta_correcta;
        }

        if($res->estado === "correcta"){
            $color = "style='color:green'";
        } else {
            $color = "style='color:red'";
        }
    
        $html = "<ul style='font-size: 12px;'>";
        $html .= "<li>Pregunta: <strong>".$res->pregunta."</strong></li>";
        $html .= "<li ".$color.">Respuesta: <strong>".$respuesta."</strong></li>";
        $html .= "<li ".$color.">Estado: <strong>".$res->estado."</strong></li>";
        $html .= "<li ".$color.">Intento(s): <strong>".$res->intento."</strong></li>";
        $html .= "</ul>";

        echo $html; 
        echo "<hr>";
    }

}

?>