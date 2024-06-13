<?php 
// load wordpress
include('../../../../wp-load.php');

// error_reporting(E_ALL);
// error_reporting(-1);
// ini_set('error_reporting', E_ALL);

if($_POST){

	$id_user    = $_POST["id_user"];
    $id_curso   = $_POST["id_curso"];
    $email      = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $curso      = filter_var($_POST["curso"], FILTER_SANITIZE_STRING);
	$evaluacion = filter_var($_POST["evaluacion"], FILTER_SANITIZE_STRING);

    if($id_user && $email && $curso && $evaluacion){


        if(!get_page_by_title('Evaluador(a): '.$email,OBJECT,'evaluacion')){

            /* * se crea la evaluacion * */
            $nuevaEvaluacion = array(
                'post_title' => 'Evaluador(a): '.$email,
                'post_status' => 'private',
                'post_type' => 'evaluacion',
            );
            
            $nuevaEvaluacionId = wp_insert_post($nuevaEvaluacion);
            /* * End se crea la evaluacion * */

            if($nuevaEvaluacionId){

                /* * campos meta asociados a la evaluacion * */
                add_post_meta($nuevaEvaluacionId, 'id_user_evaluado', $id_user, true);
                add_post_meta($nuevaEvaluacionId, 'id_curso_evaluado', $id_curso, true);
                add_post_meta($nuevaEvaluacionId, 'email_evaluado', $email, true);
                add_post_meta($nuevaEvaluacionId, 'curso_evaluado', $curso, true);
                add_post_meta($nuevaEvaluacionId, 'nivel_evaluado', $evaluacion, true);

                echo json_encode(array("status" => true, "class_message" => "success", "message" => "¡Evaluación enviada exitosamente! gracias por apoyar a Academia FIPI.<br> Esta página se recargará en breve..."));

            } else {

                echo json_encode(array("status" => false, "class_message" => "warning", "message" => "Ocurrio un problema al insertar."));            

            }

        } else {

            echo json_encode(array("status" => false, "class_message" => "info",  "message" => "Ya evaluaste este curso"));            
            
        }
        
    } else {

        echo json_encode(array("status" => false, "class_message" => "warning",  "message" => "Ocurrio un problema!, verifica los campos ingresados"));

    }

} else {

    echo json_encode(array("status" => false, "class_message" => "error",  "message" => "Ocurrio un problema!"));

}

?>