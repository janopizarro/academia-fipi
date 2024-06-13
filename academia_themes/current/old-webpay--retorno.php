<?php
include('../../../wp-load.php');
require_once("flow/FlowApi.class.php"); 

$token = filter_input(INPUT_POST, 'token');
$params = array(
    "token" => $token,
);
$serviceName = "payment/getStatus";

error_reporting(E_ALL);
error_reporting(-1);
ini_set('error_reporting', E_ALL);

function randomPassword() {
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}

$clave = randomPassword();

try {
    $flowApi = new FlowApi;
    $response = $flowApi->send($serviceName, $params,"GET");
   
    // print_r($response);

    $nombre   = $response['optional']['nombre'];
    $apellido = $response['optional']['apellido'];
    $telefono = $response['optional']['telefono'];
    $id_curso = $response['optional']['id_curso'];
    $email = $response['payer'];
    $flowOrder = $response['flowOrder'];
    $requestDate = explode(' ',$response['requestDate']);
    $fecha = explode('-',$requestDate[0]);
    $fecha = $fecha[2].'-'.$fecha[1].'-'.$fecha[0];
    $hora = $requestDate[1];
    $status = $response['status']; // 2 webpay normal
    $amount = $response['amount'];
    $commerceOrder = $response['commerceOrder'];

    if($status == 2){

        // verificar si existe en wordpress el email
        if(email_exists($email)){

            $userdata = get_user_by('email', $email);

            $ID = $userdata->ID;

            // si existe se rescata el ID
            $nuevoPago = array(
                'post_title' => 'Pago FLOW ['.$email.']',
                'post_status' => 'private',
                'post_type' => 'transacciones',
                'post_author' => $ID
            );
            
            $nuevoPagoID = wp_insert_post($nuevoPago);

            if($nuevoPagoID){
                echo "Pago registrado, usuario existente";                
            }
    
            add_post_meta($nuevoPagoID, 'trx_id', $flowOrder, true);
            add_post_meta($nuevoPagoID, 'trx_oc', $commerceOrder, true);
            add_post_meta($nuevoPagoID, 'trx_nombre', $nombre, true);
            add_post_meta($nuevoPagoID, 'trx_email', $email, true);
            add_post_meta($nuevoPagoID, 'trx_monto', $amount, true);
            add_post_meta($nuevoPagoID, 'trx_fecha', $fecha, true);
            add_post_meta($nuevoPagoID, 'trx_hora', $hora, true);
            add_post_meta($nuevoPagoID, 'trx_tipo', 'webpay', true);

            $nuevoAcceso = array(
                'post_title' => 'Acceso Curso ['.$email.']',
                'post_status' => 'private',
                'post_type' => 'accesos_curso',
                'post_author' => $ID
            );
            
            $nuevoAccesoID = wp_insert_post($nuevoAcceso);
    
            add_post_meta($nuevoAccesoID, 'accesos_email', $email, true);
            add_post_meta($nuevoAccesoID, 'accesos_idtrx', $flowOrder, true);
            add_post_meta($nuevoAccesoID, 'accesos_cursos_presenciales', $id_curso, true);
            add_post_meta($nuevoAccesoID, 'accesos_estado', 'activo', true);

            if($nuevoAccesoID){
                echo "Acceso garantizado";

                // se crea el avance de curso
                $tableName = $wpdb->prefix . "estado_curso";

                global $wpdb;
                $insercion = $wpdb->insert($tableName, array(
                    'id'        => 'null',
                    'id_user'   => $id_user,
                    'id_curso'  => $id_curso,
                    'unidad_1'  => 0,
                    'unidad_2'  => 0,
                    'unidad_3'  => 0,
                    'unidad_4'  => 0,
                ));
                // end se crea el avance de curso

            }


        } else {

            // si no existe se crea
            $dataWP = array(
                'user_login'           => $nombre,
                'first_name'           => $nombre,
                'user_pass'            => $clave,
                'user_email'           => $email,
                'show_admin_bar_front' => false,
                'role'                 => 'alumno'
            );

            echo $clave;
            
            $user_id = wp_insert_user( $dataWP );
            update_user_meta( $user_id, 'user_apellido', $apellido);
            update_user_meta( $user_id, 'user_telefono', $telefono);
            // end se crea el usuario wordpress

            if(! is_wp_error( $user_id)){

                $nuevoPago = array(
                    'post_title' => 'Pago FLOW ['.$email.']',
                    'post_status' => 'private',
                    'post_type' => 'transacciones',
                    'post_author' => $user_id
                );
                
                $nuevoPagoID = wp_insert_post($nuevoPago);
    
                if($nuevoPagoID){
                    echo "Pago registrado, usuario existente";                
                }
        
                add_post_meta($nuevoPagoID, 'trx_id', $flowOrder, true);
                add_post_meta($nuevoPagoID, 'trx_oc', $commerceOrder, true);
                add_post_meta($nuevoPagoID, 'trx_nombre', $nombre, true);
                add_post_meta($nuevoPagoID, 'trx_email', $email, true);
                add_post_meta($nuevoPagoID, 'trx_monto', $amount, true);
                add_post_meta($nuevoPagoID, 'trx_fecha', $fecha, true);
                add_post_meta($nuevoPagoID, 'trx_hora', $hora, true);
                add_post_meta($nuevoPagoID, 'trx_tipo', 'webpay', true);
    
                $nuevoAcceso = array(
                    'post_title' => 'Acceso Curso ['.$email.']',
                    'post_status' => 'private',
                    'post_type' => 'accesos_curso',
                    'post_author' => $user_id
                );
                
                $nuevoAccesoID = wp_insert_post($nuevoAcceso);
        
                add_post_meta($nuevoAccesoID, 'accesos_email', $email, true);
                add_post_meta($nuevoAccesoID, 'accesos_idtrx', $flowOrder, true);
                add_post_meta($nuevoAccesoID, 'accesos_cursos_presenciales', $id_curso, true);
                add_post_meta($nuevoAccesoID, 'accesos_estado', 'activo', true);
    
                if($nuevoAccesoID){
                    echo "Acceso garantizado";

                    // se crea el avance de curso
                    $tableName = $wpdb->prefix . "estado_curso";

                    global $wpdb;
                    $insercion = $wpdb->insert($tableName, array(
                        'id'        => 'null',
                        'id_user'   => $user_id,
                        'id_curso'  => $id_curso,
                        'unidad_1'  => 0,
                        'unidad_2'  => 0,
                        'unidad_3'  => 0,
                        'unidad_4'  => 0,
                    ));
                    // end se crea el avance de curso

                }
                
            } else {

                echo "Hubo un error al crear el usuario en Wordpress! ".$user_id->get_error_message()."";

            } 

        }

    } else {

        echo "transacción no aprobada por flow";

    }

    







 















    // $commerceOrder = $response["commerceOrder"];

    // if(empty($response["paymentData"]["amount"])){
    //     echo "<script>window.location.replace('".home_url()."/donacion-finalizada/?state=error');</script>";

    //     // se actualiza estado de datos temporales
    //     $wpdb->update('wpor_trx_temp', array('estado' => "TRANSACCIÓN NO PROCESADA", 'description' => "Pago Webpay presentó problemas ó fue anulado por el usuario"),array('orden_compra' => $commerceOrder));

    // } else {        
    //     echo "<script>window.location.replace('".home_url()."/donacion-finalizada/?state=ok');</script>";
    // }

} catch (Exception $e) {
    echo $e->getCode() . " - " . $e->getMessage();
}
?>