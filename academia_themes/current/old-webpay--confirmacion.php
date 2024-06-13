<?php
include('../../../wp-load.php'); 
require_once("flow/FlowApi.class.php"); 

// require_once("vendor/autoload.php"); 

// php mailer
// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;
// end php mailer

// function password
// function randomPassword() {
//     $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
//     $pass = array(); //remember to declare $pass as an array
//     $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
//     for ($i = 0; $i < 13; $i++) {
//         $n = rand(0, $alphaLength);
//         $pass[] = $alphabet[$n];
//     }
//     return implode($pass); //turn the array into a string
// }

$token = filter_input(INPUT_POST, 'token');
$params = array(
    "token" => $token,
);
$serviceName = "payment/getStatus";
try {
    $flowApi = new FlowApi;
    $response = $flowApi->send($serviceName, $params,"GET");
    
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

                echo "Hubo un error al crear el usuario en Wordpress! ".$user_id->get_error_message()."";

            } 

        }

    } else {

        echo "transacción no aprobada por flow";

    }

    // if(empty($response["paymentData"]["amount"])){
    //     echo "<script>window.location.replace('".home_url()."/estado-de-pago/?state=error');</script>";

    //     // se actualiza estado de datos temporales
    //     $table = $wpdb->prefix . "trx_temp";
    //     $wpdb->update($table, array('estado' => "TRANSACCIÓN NO PROCESADA", 'description' => "Pago Webpay presentó problemas ó fue anulado por el usuario"),array('orden_compra' => $commerceOrder));

    // } else {
        
    //     // obtener campos insertados temporalmente en bd
    //     $table = $wpdb->prefix . "trx_temp";
    //     $existeOC = $wpdb->get_results( "SELECT * FROM $table WHERE `orden_compra` = '$commerceOrder' limit 0,1" );
    //     if(count($existeOC)>0){

    //         foreach($existeOC as $oc){
    //             $campos = $oc->campos;
    //         }

    //         $camposDec = json_decode($campos);

    //         $telefono       = $camposDec->telefono;
    //         $nombre         = $camposDec->nombre;
    //         $email          = $camposDec->email;
    //         $servicio       = $camposDec->servicio;
    //         $monto          = $camposDec->monto;
    //         $mensaje        = $camposDec->mensaje;

    //     } else {
    //         echo "No existe";
    //         return false;
    //     }
    //     // End obtener campos insertados temporalmente en bd
 
    //     // fecha hora actual
    //     $dt = new DateTime("now", new DateTimeZone('America/Santiago'));
    //     $horaActual  = $dt->format('H:i:s');

    //     $flowOrder = $response["flowOrder"];

    //     // se inserta en la base de datos de transacciones
    //     global $wpdb;
    //     $wpdb->insert('wpor_transacciones_flow', array(
    //         'id'              => 'null',
    //         'tipo_pago'       => "Webpay",
    //         'monto'           => $monto,
    //         'nombre'          => $nombre,
    //         'email'           => $email,
    //         'telefono'        => $telefono,
    //         'servicio'        => $servicio,
    //         'fecha'           => $dt->format('d-m-Y'),
    //         'hora'            => $horaActual,
    //         'id_trx'          => $flowOrder,
    //     ));

    //     // $htmlContent = '<!DOCTYPE html><html><head><meta content="text/html; charset=utf8"http-equiv="Content-Type"><meta content="IE=edge"http-equiv="X-UA-Compatible"><title></title><link href=""rel="stylesheet"></head><body><body bgcolor="4CC899"leftmargin="0"marginheight="0"marginwidth="0"style="height:100%;width:100%;min-width:100%;margin:0;padding:0;background-color:#fff"topmargin="0"><center><table border="0"cellpadding="0"cellspacing="0"width="100%"><tr><td align="center"bgcolor="4CC899"><table border="0"cellpadding="0"cellspacing="0"width="620"align="center"bgcolor="4CC899"class="tableFull"style="min-width:310px"><tr><td> </td></tr></table><table border="0"cellpadding="0"cellspacing="0"width="620"align="center"bgcolor="white"class="tableFull"style="min-width:310px"><tr><td> </td></tr><tr><td align="center"><img alt=""src="http://kellucausas.com/wp-content/uploads/2019/11/kellu-logo-azul-1.png"width="100"></td></tr><tr><td></td></tr><tr><td align="center"><p style="font-family:arial;font-size:15px;color:#334082;font-weight:700;padding-left:25px;padding-right:25px">Querido/a <strong>'.$nombre.' :</strong></p></td></tr><tr><td></td></tr><tr><td></td></tr><tr><td align="center"><p style="font-family:arial;font-size:14px;color:#334082;padding-left:25px;padding-right:25px">¡Hemos recibido tu aporte con éxito y estamos muy agradecidos! Tu donación nos permitirá ayudar y generar un impacto en la vida de quienes más lo necesitan.</p></td></tr><tr><td align="center"><p style="font-family:arial;font-size:14px;color:#334082;padding-left:25px;padding-right:25px">Como creemos firmemente en el poder de la colaboración, te invitamos a contarle a tu familia y amigos sobre esta nueva manera de ayudar que te conecta directamente con las personas para ver el impacto real de tu aporte.</p></td></tr><tr><td align="center"><p style="font-family:arial;font-size:14px;color:#334082;padding-left:25px;padding-right:25px">¡Mantente atento/a!</p></td></tr><tr><td align="center"><p style="font-family:arial;font-size:14px;color:#334082;padding-left:25px;padding-right:25px">Te mantendremos informado sobre la entrega de la ayuda una vez que lleguemos a la meta.</p></td></tr><tr><td height="15"></td></tr><tr><td align="center"><p style="font-family:arial;font-size:14px;color:#334082;font-style:italic">Un gran abrazo<br><br><strong>Equipo Kellü</strong></p></td></tr><tr><td align="center"><p style="font-family:arial;font-size:14px;color:#334082;padding-left:25px;padding-right:25px">Te invitamos a seguirnos en nuestras redes sociales.</p></td></tr><tr><td align="center"><p style="font-family:arial;font-size:14px;color:#334082;padding-left:25px;padding-right:25px"><strong><a href="https://www.instagram.com/kellucausas/"><img alt="" width="50" src="http://www.kellucausas.com/instagram.png"></a></strong></p></td></tr><tr><td></td></tr><tr><td> </td></tr></table><table border="0"cellpadding="0"cellspacing="0"width="620"align="center"bgcolor="4CC899"class="tableFull"style="min-width:310px"><tr><td> </td></tr></table></td></tr></table></center></body></html>';

    //     // $mail = new PHPMailer();
    //     // $mail->IsSMTP();
    //     // $mail->SMTPAuth = true; 
    //     // $mail->SMTPSecure = 'ssl';
    //     // $mail->Host = "mail.kellucausas.com";
    //     // $mail->Port = 465;
    //     // $mail->IsHTML(true);
    //     // $mail->Username = "no-reply@kellucausas.com";
    //     // $mail->Password = "yT}pPWJVS(3LQgLr";
    //     // $mail->SetFrom('no-reply@kellucausas.com', 'Kellü Causas');
    //     // $mail->CharSet = 'UTF-8';
    //     // $mail->Subject = "¡Gracias por tu donación a Kellü!";
    //     // $mail->Body = $htmlContent;
    //     // $mail->AddAddress($email);

    //     // if(!$mail->Send()) {
    //     //     echo "Mailer Error: " . $mail->ErrorInfo;
    //     // } else {
            
    //     // }

    //     // $clave = randomPassword();

    //     // $existeLogin = $wpdb->get_results( "SELECT * FROM `wpor_login_flow` WHERE `email` LIKE '%$email%'" );
    //     // if(!count($existeLogin)>0){
    //     //     global $wpdb;
    //     //     $wpdb->insert('wpor_login_flow', array(
    //     //         'id'       => 'null',
    //     //         'email'    => $email,
    //     //         'password' => $clave,
    //     //     ));

    //     //     // se envia correo con clave y bienvenida
    //     //     $htmlContent = '<!DOCTYPE html><html><head><meta content="text/html; charset=utf8"http-equiv="Content-Type"><meta content="IE=edge"http-equiv="X-UA-Compatible"><title></title><link href=""rel="stylesheet"></head><body><body bgcolor="4CC899"leftmargin="0"marginheight="0"marginwidth="0"style="height:100%;width:100%;min-width:100%;margin:0;padding:0;background-color:#fff"topmargin="0"><center><table border="0"cellpadding="0"cellspacing="0"width="100%"><tr><td align="center"bgcolor="4CC899"><table border="0"cellpadding="0"cellspacing="0"width="620"align="center"bgcolor="4CC899"class="tableFull"style="min-width:310px"><tr><td> </td></tr></table><table border="0"cellpadding="0"cellspacing="0"width="620"align="center"bgcolor="white"class="tableFull"style="min-width:310px"><tr><td> </td></tr><tr><td align="center"><img alt=""src="http://kellucausas.com/wp-content/uploads/2019/11/kellu-logo-azul-1.png"width="100"></td></tr><tr><td></td></tr><tr><td align="center"><p style="font-family:arial;font-size:15px;color:#334082;font-weight:700;padding-left:25px;padding-right:25px">Querido/a <strong>'.$nombre.' :</strong></p></td></tr><tr><td></td></tr><tr><td></td></tr><tr><td align="center"><p style="font-family:arial;font-size:14px;color:#334082;padding-left:25px;padding-right:25px">¡Hemos recibido tu aporte con éxito y estamos muy agradecidos! Tu donación nos permitirá ayudar y generar un impacto en la vida de quienes más lo necesitan.</p></td></tr><tr><td align="center"><p style="font-family:arial;font-size:14px;color:#334082;padding-left:25px;padding-right:25px">Como creemos firmemente en el poder de la colaboración, te invitamos a contarle a tu familia y amigos sobre esta nueva manera de ayudar que te conecta directamente con las personas para ver el impacto real de tu aporte.</p></td></tr><tr><td align="center"><p style="font-family:arial;font-size:14px;color:#334082;padding-left:25px;padding-right:25px">¡Mantente atento/a!</p></td></tr><tr><td align="center"><p style="font-family:arial;font-size:14px;color:#334082;padding-left:25px;padding-right:25px">Te mantendremos informado sobre la entrega de la ayuda una vez que lleguemos a la meta.</p></td></tr><tr><td height="15"></td></tr><tr><td align="center"><p style="font-family:arial;font-size:14px;color:#334082;padding-left:25px;padding-right:25px;font-style:italic">Puedes acceder al portal para ver el historial de tus transacciones.<br>Tus datos de acceso son:</p></td></tr><tr bgcolor="dedede"><td align="center"><p style="font-family:arial;font-size:17px;color:#334082;padding-left:25px;padding-right:25px">email: <strong>'.$email.'</strong><br>clave: <strong>'.$clave.'</strong></p></td></tr><tr><td height="25"></td></tr><tr><td align="center"><a href="http://www.kellucausas.com/wordpress/mi-perfil/"style="background:#334082;color:#c3ffff;padding:10px 15px;font-family:arial;font-size:15px;text-decoration:none">Ir al Portal</a></td></tr><tr><td height="15"></td></tr><tr><td align="center"><p style="font-family:arial;font-size:14px;color:#334082;font-style:italic">Un gran abrazo<br><br><strong>Equipo Kellü</strong></p></td></tr><tr><td align="center"><p style="font-family:arial;font-size:14px;color:#334082;padding-left:25px;padding-right:25px">Te invitamos a seguirnos en nuestras redes sociales.</p></td></tr><tr><td align="center"><p style="font-family:arial;font-size:14px;color:#334082;padding-left:25px;padding-right:25px"><strong><a href="https://www.instagram.com/kellucausas/"><img alt="" width="50" src="http://www.kellucausas.com/instagram.png"></a></strong></p></td></tr><tr><td></td></tr><tr><td> </td></tr></table><table border="0"cellpadding="0"cellspacing="0"width="620"align="center"bgcolor="4CC899"class="tableFull"style="min-width:310px"><tr><td> </td></tr></table></td></tr></table></center></body></html>';
                
    //     //     $mail = new PHPMailer();
    //     //     $mail->IsSMTP();
    //     //     $mail->SMTPAuth = true; 
    //     //     $mail->SMTPSecure = 'ssl';
    //     //     $mail->Host = "mail.kellucausas.com";
    //     //     $mail->Port = 465;
    //     //     $mail->IsHTML(true);
    //     //     $mail->Username = "no-reply@kellucausas.com";
    //     //     $mail->Password = "yT}pPWJVS(3LQgLr";
    //     //     $mail->SetFrom('no-reply@kellucausas.com', 'Kellü Causas');
    //     //     $mail->CharSet = 'UTF-8';
    //     //     $mail->Subject = "¡Gracias por ser parte de Kellü!";
    //     //     $mail->Body = $htmlContent;
    //     //     $mail->AddAddress($email);

    //     //     if(!$mail->Send()) { echo "Mailer Error: " . $mail->ErrorInfo; } else { }

    //     // }
    //     echo "<script>window.location.replace('".home_url()."/estado-de-pago/?state=ok');</script>";

    //     // se actualiza estado de datos temporales
    //     $table = $wpdb->prefix . "trx_temp";
    //     $wpdb->update($table, array('estado' => "TRANSACCIÓN APROBADA", 'description' => "Pago Webpay fue recibido correctamente"),array('orden_compra' => $commerceOrder));

    // }

} catch (Exception $e) {
    echo $e->getCode() . " - " . $e->getMessage();
}
?>