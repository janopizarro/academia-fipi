<?php
include('../../../../wp-load.php'); 
require_once("FlowApi.class.php"); 

// error_reporting(E_ALL);
// error_reporting(-1);
// ini_set('error_reporting', E_ALL);

try {

    if(!isset($_POST["token"])) {
		throw new Exception("No se recibio el token", 1);
	}

    $token = filter_input(INPUT_POST, 'token');

    $params = array(
        "token" => $token,
    );

    $serviceName = "payment/getStatus";

    $flowApi = new FlowApi();
    $response = $flowApi->send($serviceName, $params,"GET");
    
    $status = $response['status'];

    if($status == 2){

        // información de la transacción flow
        $flowOrder     = $response['flowOrder'];
        $requestDate   = explode(' ',$response['requestDate']);
        $fecha         = explode('-',$requestDate[0]);
        $fecha         = $fecha[2].'-'.$fecha[1].'-'.$fecha[0];
        $hora          = $requestDate[1];
        $amount        = $response['amount'];
        $commerceOrder = $response['commerceOrder'];
        $media         = $response['paymentData']['media'];
        $currency      = $response['currency'];
        $subject       = explode(': ',$response['subject']);
        $curso         = $subject[1];

        $tableNameTmp = $wpdb->prefix . "flow_temporal";

        // se rescata la información temporal
        $existeOC = $wpdb->get_results( "SELECT * FROM $tableNameTmp WHERE `oc` = '$commerceOrder' limit 0,1" );

        foreach($existeOC as $oc){
            $campos = $oc->data;
        }

        $camposDec = json_decode($campos);

        $GLOBALS['nombre']    = $camposDec->nombre;
        $GLOBALS['apellido']  = $camposDec->apellido;
        $GLOBALS['telefono']  = $camposDec->telefono;
        $GLOBALS['rut']  = $camposDec->rut;
        $GLOBALS['region']    = $camposDec->region;
        $GLOBALS['comuna']    = $camposDec->comuna;
        $GLOBALS['direccion'] = $camposDec->direccion;
        $GLOBALS['id_curso']  = $camposDec->id_curso;

        $GLOBALS['email'] = $response['payer'];

        // se comprueba si el correo existe en fipi
        if(email_exists($GLOBALS['email'])){

            $userdata = get_user_by('email', $GLOBALS['email']);

            $ID = $userdata->ID;

            // si existe se rescata el ID
            $nuevoPago = array(
                'post_title' => 'Pago FLOW ['.$GLOBALS['email'].']',
                'post_status' => 'private',
                'post_type' => 'transacciones',
                'post_author' => $ID
            );
            
            $nuevoPagoID = wp_insert_post($nuevoPago);

            if($nuevoPagoID){
    
                add_post_meta($nuevoPagoID, 'trx_id', $flowOrder, true);
                add_post_meta($nuevoPagoID, 'trx_oc', $commerceOrder, true);
                add_post_meta($nuevoPagoID, 'trx_nombre', $GLOBALS['nombre'], true);
                add_post_meta($nuevoPagoID, 'trx_email', $GLOBALS['email'], true);
                add_post_meta($nuevoPagoID, 'trx_monto', $amount, true);
                add_post_meta($nuevoPagoID, 'trx_fecha', $fecha, true);
                add_post_meta($nuevoPagoID, 'trx_hora', $hora, true);
                add_post_meta($nuevoPagoID, 'trx_tipo', $media, true);
    
                $nuevoAcceso = array(
                    'post_title' => 'Acceso Curso ['.$GLOBALS['email'].']',
                    'post_status' => 'private',
                    'post_type' => 'accesos_curso',
                    'post_author' => $ID
                );
                
                $nuevoAccesoID = wp_insert_post($nuevoAcceso);
        
                if($nuevoAccesoID){

                    add_post_meta($nuevoAccesoID, 'accesos_email', $GLOBALS['email'], true);
                    add_post_meta($nuevoAccesoID, 'accesos_idtrx', $flowOrder, true);
                    add_post_meta($nuevoAccesoID, 'accesos_curso_comprado', $curso, true);
                    add_post_meta($nuevoAccesoID, 'accesos_curso_comprado_id', $id_curso, true);
                    add_post_meta($nuevoAccesoID, 'accesos_estado', 'activo', true);

                    if(getUnitsCourse($id_curso)){
                        
                        // se crea el avance de curso
                        $tableName = $wpdb->prefix . "unidades_dinamico";
                            
                        global $wpdb;
                        $insercion = $wpdb->insert($tableName, 
                            array(
                                'id'        => 'null',
                                'id_user'   => $ID,
                                'id_curso'  => $id_curso,
                                'n_unidad'  => 0,
                                'tipo'      => 1,
                                'status'    => 0
                            )
                        );

                    } else {

                        // se crea el avance de curso
                        $tableName = $wpdb->prefix . "estado_curso";

                        global $wpdb;
                        $insercion = $wpdb->insert($tableName, array(
                            'id'        => 'null',
                            'id_user'   => $ID,
                            'id_curso'  => $id_curso,
                            'unidad_intro' => 0,
                            'unidad_1'  => 0,
                            'unidad_2'  => 0,
                            'unidad_3'  => 0,
                            'unidad_4'  => 0,
                            'email_admin' => 0
                        ));

                    }
                
                    if($GLOBALS['email'] && $GLOBALS['nombre']){

                        /* * curso-comprado-usuario-existente-flow * */
                        insertarEnColaCorreo($GLOBALS['tipoCorreos'][1], $ID, $id_curso, '');

                        /* * notificacion-administrador-curso-comprado-usuario-existente-flow * */
                        insertarEnColaCorreo($GLOBALS['tipoCorreos'][3], $ID, $id_curso, '');

                    } else {

                        marcarError("webpay--confirmation.php", "[USUARIO ANTIGUO] error al enviar correo - no vienen parametros necesarios para enviar el correo", '', $GLOBALS['email'], '');

                    }

                } else {

                    // se guarda registro del error generado
                    $arr = array();
                    foreach($nuevoAcceso as $item){
                        $arr[] = $item;
                    }
                    $data = json_encode($arr);
                    marcarError("webpay--confirmation.php", "accesos_curso para usuario existente en base de datos propia", $nuevoAccesoID->get_error_message(), $GLOBALS['email'], $data);

                }

            } else {

                // se guarda registro del error generado
                $arr = array();
                foreach($nuevoPago as $item){
                    $arr[] = $item;
                }
                $data = json_encode($arr);
                marcarError("webpay--confirmation.php", "registrar transacción de usuario existente en base de datos propia", $nuevoPagoID->get_error_message(), $GLOBALS['email'], $data);

            }

        } else {

            // si el usuario no existe entra a este apartado

            function randomPassword() {
                $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
                $pass = array(); 
                $alphaLength = strlen($alphabet) - 1; 
                for ($i = 0; $i < 13; $i++) {
                    $n = rand(0, $alphaLength);
                    $pass[] = $alphabet[$n];
                }
                return implode($pass); 
            }

            $clave = randomPassword();

            // si no existe se crea
            $dataWP = array(
                'user_login'           => $GLOBALS['email'],
                'first_name'           => $GLOBALS['nombre'],
                'last_name'            => $GLOBALS['apellido'],
                'user_pass'            => $clave,
                'user_email'           => $GLOBALS['email'],
                'show_admin_bar_front' => false,
                'role'                 => 'alumno'
            );

            $user_id = wp_insert_user( $dataWP );
            // update_user_meta( $user_id, 'user_apellido', $GLOBALS['apellido']);
            update_user_meta( $user_id, 'user_telefono', $GLOBALS['telefono']);
            update_user_meta( $user_id, 'user_comuna', $GLOBALS['comuna']);
            update_user_meta( $user_id, 'user_region', $GLOBALS['region']);
            update_user_meta( $user_id, 'user_direccion', $GLOBALS['direccion']);
            update_user_meta( $user_id, 'user_rut', $GLOBALS['rut']);
            // end se crea el usuario wordpress

            if(! is_wp_error( $user_id)){

                $nuevoPago = array(
                    'post_title' => 'Pago FLOW ['.$GLOBALS['email'].']',
                    'post_status' => 'private',
                    'post_type' => 'transacciones',
                    'post_author' => $user_id
                );
                
                $nuevoPagoID = wp_insert_post($nuevoPago);

                if($nuevoPagoID){

                    add_post_meta($nuevoPagoID, 'trx_id', $flowOrder, true);
                    add_post_meta($nuevoPagoID, 'trx_oc', $commerceOrder, true);
                    add_post_meta($nuevoPagoID, 'trx_nombre', $GLOBALS['nombre'], true);
                    add_post_meta($nuevoPagoID, 'trx_email', $GLOBALS['email'], true);
                    add_post_meta($nuevoPagoID, 'trx_monto', $amount, true);
                    add_post_meta($nuevoPagoID, 'trx_fecha', $fecha, true);
                    add_post_meta($nuevoPagoID, 'trx_hora', $hora, true);
                    add_post_meta($nuevoPagoID, 'trx_tipo', 'webpay', true);
    
                    $nuevoAcceso = array(
                        'post_title'   => 'Acceso Curso ['.$GLOBALS['email'].']',
                        'post_status'  => 'private',
                        'post_type'    => 'accesos_curso',
                        'post_author'  => $user_id
                    );
                    
                    $nuevoAccesoID = wp_insert_post($nuevoAcceso);
    
                    add_post_meta($nuevoAccesoID, 'accesos_email', $GLOBALS['email'], true);
                    add_post_meta($nuevoAccesoID, 'accesos_idtrx', $flowOrder, true);
                    add_post_meta($nuevoAccesoID, 'accesos_curso_comprado', $curso, true);
                    add_post_meta($nuevoAccesoID, 'accesos_curso_comprado_id', $id_curso, true);
                    
                    add_post_meta($nuevoAccesoID, 'accesos_estado', 'activo', true);
    
                    if($nuevoAccesoID){

                        if(getUnitsCourse($id_curso)){

                            // se crea el avance de curso
                            $tableName = $wpdb->prefix . "unidades_dinamico";
                                                        
                            global $wpdb;
                            $insercion = $wpdb->insert($tableName, 
                                array(
                                    'id'        => 'null',
                                    'id_user'   => $user_id,
                                    'id_curso'  => $id_curso,
                                    'n_unidad'  => 0,
                                    'tipo'      => 1,
                                    'status'    => 0
                                )
                            );

                        } else {

                            // se crea el avance de curso
                            $tableName = $wpdb->prefix . "estado_curso";
        
                            global $wpdb;
                            $insercion = $wpdb->insert($tableName, array(
                                'id'        => 'null',
                                'id_user'   => $user_id,
                                'id_curso'  => $id_curso,
                                'unidad_intro' => 0,
                                'unidad_1'  => 0,
                                'unidad_2'  => 0,
                                'unidad_3'  => 0,
                                'unidad_4'  => 0,
                                'email_admin' => 0
                            ));

                        }
    
                        if(!$insercion){
                            marcarError("webpay--confirmation.php", "error al crear el estado de unidaddes!", $insercion->print_error(), $GLOBALS['email'], '');
                        }
                        // end se crea el avance de curso

                        if($GLOBALS['email'] && $GLOBALS['nombre']){

                            /* * bienvenida-usuario-nuevo * */
                            insertarEnColaCorreo($GLOBALS['tipoCorreos'][0], $user_id, $id_curso, $clave);

                            /* * notificacion-administrador-curso-comprado-usuario-nuevo-flow * */
                            insertarEnColaCorreo($GLOBALS['tipoCorreos'][2], $user_id, $id_curso, '');
                        
                        } else {

                            marcarError("webpay--confirmation.php", "[USUARIO NUEVO] error al enviar correo - no vienen parametros necesarios para enviar el correo", '', $GLOBALS['email'], '');

                        }

                        marcarError("webpay--confirmation.php", "error al enviar correo", $sendEmailAdmin, $GLOBALS['email'], '');
    
                    } else {
    
                        // se guarda registro del error generado
                        $arr = array();
                        foreach($nuevoAcceso as $item){
                            $arr[] = $item;
                        }
                        $data = json_encode($arr);
                        marcarError("webpay--confirmation.php", "accesos_curso para usuario nuevo en base de datos propia", $nuevoAccesoID->get_error_message(), $GLOBALS['email'], $data);
    
                    }

                } else {

                    // se guarda registro del error generado
                    $arr = array();
                    foreach($nuevoPago as $item){
                        $arr[] = $item;
                    }
                    $data = json_encode($arr);
                    marcarError("webpay--confirmation.php", "registrar transacción de usuario nuevo en base de datos propia", $nuevoPagoID->get_error_message(), $GLOBALS['email'], $data);

                }

            } else {

                // se guarda registro del error generado
                $arr = array();
                foreach($response as $item){
                    $arr[] = $item;
                }
                $data = json_encode($arr);
                marcarError("webpay--confirmation.php", "crear usuario wordpress", $user_id->get_error_message(), $GLOBALS['email'], $data);

            }

        }
                    
    } else {

        $tableNameTmp = $wpdb->prefix . "flow_temporal";

        // se actualiza el estado de la transacción temporal
        $wpdb->update($tableNameTmp, array('estado' => "TRANSACCIÓN NO PROCESADA"),array('oc' => $commerceOrder));

    }

} catch (Exception $e) {
    echo $e->getCode() . " - " . $e->getMessage();
    die();
}
?>