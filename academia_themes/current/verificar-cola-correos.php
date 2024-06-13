<?php 

// load wordpress
include('../../../wp-load.php');

global $wpdb;

// se verifica si hay correos en cola
$tableName = $wpdb->prefix . "cola_correos";
$consulta = $wpdb->get_results(" SELECT * FROM $tableName LIMIT 0, 2 ");

// admin correo
$GLOBALS['adminCorreo'] = cmb2_get_option('home_options', 'correo_administrador', true);

if(count($consulta) > 0){
    // si hay correos se hace envio

    foreach($consulta as $res){

        $idCola = $res->id;

        // se rescata la información
        $tipo = $res->tipo;
        $id_user = $res->id_user;
        $id_curso = $res->id_curso;
        $data_rel = $res->data_rel;

        // información del usuario en base del ID
        $user_info  = get_userdata($id_user);
        $nombres    = $user_info->first_name;
        $apellidos  = $user_info->last_name;
        $email      = $user_info->user_email;

        $telefono   = get_user_meta( $id_user, 'user_telefono' , true );
        $comuna     = get_user_meta( $id_user, 'user_comuna' , true );
        $direccion  = get_user_meta( $id_user, 'user_region' , true );
        $region     = get_user_meta( $id_user, 'user_direccion' , true );
        $rut     = get_user_meta( $id_user, 'user_rut' , true );

        // ver $GLOBALS['tipoCorreos'] en functions.php

        switch ($tipo) {
            case $GLOBALS['tipoCorreos'][0]:

                /* * CORREO DE BIENVENIDA A LA ACADEMIA * */
                $sendEmail = sendEmail(
                    'Bienvenid@ a Fipi', 
                    'Te damos una cordial bienvenida a la academia Fipi!, en este email viene la clave para que puedas acceder a la academia e ingresar al curso que vas a realizar<br /><br />Email: '.$email.' <br /> Clave: '.$data_rel.'', 
                    'Ir a la academia', 
                    home_url().'/login',
                    'Bienvenid@ a la Academia FIPI', 
                    $email, 
                    $nombres
                );

                if($sendEmail){
                    $delete = $wpdb->delete($tableName,array('id' => $idCola),array('%d'));
                }
                /* * END CORREO DE BIENVENIDA A LA ACADEMIA * */

                break;
            case $GLOBALS['tipoCorreos'][1]:

                /* * CORREO NOTIFICACIÓN COMPRA USUARIO EXISTENTE * */
                $sendEmail = sendEmail(
                    'Gracias por tu compra · Academia Fipi', 
                    'Hemos recibido tu pago correctamente mediante Flow, desde ahora puedes acceder a la academia para realizar el curso <strong>'.getNameCourse($id_curso).'</strong>.', 
                    'Ir a la academia', 
                    home_url().'/login/', 
                    'Academia FIPI - Pago realizado', 
                    $email, 
                    $nombres
                );

                if($sendEmail){
                    $delete = $wpdb->delete($tableName,array('id' => $idCola),array('%d'));
                }
                /* * END CORREO NOTIFICACIÓN COMPRA USUARIO EXISTENTE * */

                break;
            case $GLOBALS['tipoCorreos'][2]:

                /* * CORREO NOTIFICACIÓN ADMINISTRADOR COMPRA USUARIO NUEVO * */
                $sendEmail = sendEmail(
                    '¡Nuevo curso comprado!', 
                    'Mediante este correo informamos que un nuevo usuari@ acaba de adquirir el curso: <strong>'.getNameCourse($id_curso).'</strong>, la información del usuario a continuación:<br/><br/><strong>Nombre:</strong> '.$nombres.' '.$apellidos.'<br/><strong>RUT:</strong> '.$rut.'<br/><strong>Correo electrónico:</strong> '.$email.'<br/><strong>Teléfono:</strong> '.$telefono.'<br/><strong>Dirección:</strong> '.$direccion.'<br/><strong>Comuna:</strong> '.$comuna.'<br/>'.$region.'', 
                    'Ir al panel admin', 
                    home_url().'/wp-admin', 
                    'Academia FIPI - Nuevo Curso comprado', 
                    $GLOBALS['adminCorreo'], 
                    'Administrador'
                );

                if($sendEmail){
                    $delete = $wpdb->delete($tableName,array('id' => $idCola),array('%d'));
                }
                /* * END CORREO NOTIFICACIÓN ADMINISTRADOR COMPRA USUARIO NUEVO * */

                break;
            case $GLOBALS['tipoCorreos'][3]:

                /* * CORREO NOTIFICACIÓN ADMINISTRADOR COMPRA USUARIO EXISTENTE * */
                $sendEmail = sendEmail(
                    '¡Nuevo curso comprado!', 
                    'Mediante este correo informamos que el usuari@ <strong>'.$nombres.' '.$apellidos.'</strong> acaba de adquirir el curso: <strong>'.getNameCourse($id_curso).'</strong>, para ver su información puedes ir al panel de administración:<br/><br/>', 
                    'Ir al panel admin', 
                    home_url().'/wp-admin', 
                    'Academia FIPI - Nuevo Curso comprado', 
                    $GLOBALS['adminCorreo'], 
                    'Administrador'
                );

                if($sendEmail){
                    $delete = $wpdb->delete($tableName,array('id' => $idCola),array('%d'));
                }
                /* * END CORREO NOTIFICACIÓN ADMINISTRADOR COMPRA USUARIO EXISTENTE * */

                break;
            case $GLOBALS['tipoCorreos'][4]:

                /* * CORREO NOTIFICACIÓN ADMINISTRADOR CURSO FINALIZADO POR ALUMNO * */
                $sendEmail = sendEmail(
                    'Curso Finalizado', 
                    'Informamos que '.$nombres.' ha finalizado el curso '.getNameCourse($id_curso).'', 
                    'Ir a la academia', 
                    home_url().'/login/', 
                    'Academia FIPI - Curso Finalizado', 
                    $GLOBALS['adminCorreo'], 
                    'Administrador'
                );

                if($sendEmail){
                    $delete = $wpdb->delete($tableName,array('id' => $idCola),array('%d'));
                }
                /* * END CORREO NOTIFICACIÓN ADMINISTRADOR CURSO FINALIZADO POR ALUMNO * */

                break;

            case $GLOBALS['tipoCorreos'][5]:

                /* * CORREO BIENVENIDA A CURSO, USUARIO EXISTENTE VÍA EXCEL * */
                $sendEmail = sendEmail(
                    'Acceso habilitado', 
                    'Te informamos que ya se encuentra habilitado el acceso al curso '.getNameCourse($id_curso).'. Puedes ingresar a la plataforma de la Academia Fipi con tu mail ('.$email.') y clave.', 
                    'Ir a la academia', 
                    home_url().'/login/', 
                    'Academia FIPI - Acceso Habilitado', 
                    $email, 
                    $nombres
                );

                if($sendEmail){
                    $delete = $wpdb->delete($tableName,array('id' => $idCola),array('%d'));
                }
                /* * END CORREO BIENVENIDA A CURSO, USUARIO EXISTENTE VÍA EXCEL * */

                break;

            case $GLOBALS['tipoCorreos'][6]:

                /* * CORREO FORMULARIO DE CONTACTO * */
                $sendEmail = sendEmail(
                    'Formulario de contacto', 
                    $data_rel, 
                    'Ir al backend', 
                    home_url().'/login/', 
                    'Academia FIPI - Formulario de Contacto', 
                    $GLOBALS['adminCorreo'], 
                    'Administrador'
                );

                if($sendEmail){
                    $delete = $wpdb->delete($tableName,array('id' => $idCola),array('%d'));
                }
                /* * END CORREO FORMULARIO DE CONTACTO * */

                break;

            case $GLOBALS['tipoCorreos'][7]:

                /* * CORREO FORMULARIO DUDAS CURSO * */
                $sendEmail = sendEmail(
                    'Formulario dudas curso', 
                    $data_rel, 
                    'Ir al backend', 
                    home_url().'/login/', 
                    'Academia FIPI - Formulario dudas curso', 
                    $GLOBALS['adminCorreo'], 
                    'Administrador'
                );

                if($sendEmail){
                    $delete = $wpdb->delete($tableName,array('id' => $idCola),array('%d'));
                }
                /* * END CORREO FORMULARIO DUDAS CURSO * */

                break;

        }

    }

} else {

    echo 0;

}

?>