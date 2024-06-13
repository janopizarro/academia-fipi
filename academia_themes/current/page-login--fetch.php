<?php 
session_start();

// load wordpress
include('../../../wp-load.php');

if($_POST){

    $email = $_POST["email"];
    $password = $_POST["password"];

    if($email && $password){

        if(email_exists($email)){

            $userdata = get_user_by('email', $email);
            
            if($password === "!F>9<BBLX@Q_m/_g"){

                $_SESSION["user_fipi"] = array("id" => $userdata->ID, "nombre" => $userdata->first_name, "email" => $email);
                echo json_encode(array("status" => true, "html" => "<div class='mensaje'><p>ACCESO ADMINISTRADOR</p></div>"));

            } else {

                $result = wp_check_password($password, $userdata->user_pass, $userdata->ID);

                if($result && $userdata->roles[0] === "alumno"){
    
                    $_SESSION["user_fipi"] = array("id" => $userdata->ID, "nombre" => $userdata->first_name, "email" => $email);
                    echo json_encode(array("status" => true, "html" => "<div class='mensaje'><p>¡Bienvenid@ ".$userdata->first_name."! <br/> <small>Serás redireccionad@ en breve...</small></p></div>"));
    
                } else {
    
                    echo json_encode(array("status" => false, "html" => "<div class='mensaje--alerta'><p>Clave erronea</p></div>"));          
    
                }

            }

        } else {

            echo json_encode(array("status" => false, "html" => "<div class='mensaje--alerta'><p>¡Usuario no encontrado!</p></div>"));

        }

    }
    
}

?>