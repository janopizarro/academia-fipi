<?php 
// load wordpress
include('../../../../wp-load.php');

if($_POST){

	$nombre   = filter_var($_POST["nombre"], FILTER_SANITIZE_STRING);
    $email    = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $telefono   = filter_var($_POST["telefono"], FILTER_SANITIZE_STRING);
    $curso   = filter_var($_POST["curso"], FILTER_SANITIZE_STRING);
	$duda_comentario = filter_var($_POST["duda_comentario"], FILTER_SANITIZE_STRING);

    if($nombre && $email && $curso && $duda_comentario){

        insertarEnColaCorreo($GLOBALS['tipoCorreos'][7], '', '', '<p>Adjuntamos el siguiente formulario de duda/comentario del curso '.$curso.' de la Academia FIPI:</p><br/><ul><li>Nombre: '.$nombre.'</li><li>Email: '.$email.'</li><li>Teléfono: '.$telefono.'</li><li>Duda/Comentario: '.$duda_comentario.'</li></ul>');

        echo json_encode(array("status" => true, "class_message" => "success", "message" => "Formulario enviado exitosamente, nos pondremos en contacto contigo!"));

    } else {

        echo json_encode(array("status" => false, "class_message" => "warning",  "message" => "Ocurrio un problema!, verifica los campos ingresados"));

    }

} else {

    echo json_encode(array("status" => false, "class_message" => "error",  "message" => "Ocurrio un problema!"));

}

?>