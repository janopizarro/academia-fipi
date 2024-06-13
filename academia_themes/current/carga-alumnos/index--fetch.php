<?php
include('../../../../wp-load.php');

// print_r($_POST);
// print_r($_FILES);

include('SimpleXLSX.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$idCurso = $_POST["idCurso"];
$nombreCurso = $_POST["nombreCurso"];

$fechaInicioAcceso = $_POST["fechaInicioAcceso"];
$fechaTerminoAcceso = $_POST["fechaTerminoAcceso"];

$fechaA = explode("-",$fechaInicioAcceso);
$fechaB = explode("-",$fechaTerminoAcceso);

$fechaInicioAccesoFormat = $fechaA[2]."-".$fechaA[1]."-".$fechaA[0];
$fechaTerminoAccesoFormat = $fechaB[2]."-".$fechaB[1]."-".$fechaB[0];

$mensajes = array();

if ( $xlsx = SimpleXLSX::parse($_FILES["carga_alumnos"]['tmp_name']) ) {
    
    $total = count($xlsx->rows());

    $inst  = array();
    
    foreach ($xlsx->rows() as $elt) {
        
        $apellidos = $elt[0];
        $nombres   = $elt[1];
        $rut       = $elt[2];
        $email     = $elt[3];
        $tel_01    = $elt[4];
        $tel_02    = $elt[5];
        $direccion = $elt[6];
        $comuna    = $elt[7];
        $region    = $elt[8];

        if(email_exists($email)){

            // VERIFICAR SI EL CURSO TIENE UNIDADES DINÁMICAS
            if(getUnitsCourse($idCurso)){ 

                // verificar si alumno está en el curso indicado
                $tableName = $wpdb->prefix . "unidades_dinamico";
                $userdata = get_user_by('email', $email);
                $id_user = $userdata->ID;

                $consulta = $wpdb->get_results(" SELECT * FROM $tableName WHERE `id_user` = $id_user AND `id_curso` = $idCurso LIMIT 0,1 ");

            } else {

                // verificar si alumno está en el curso indicado
                $tableName = $wpdb->prefix . "estado_curso";
                $userdata = get_user_by('email', $email);
                $id_user = $userdata->ID;

                $consulta = $wpdb->get_results(" SELECT * FROM $tableName WHERE `id_user` = $id_user AND `id_curso` = $idCurso LIMIT 0,1 ");

            }


            if(count($consulta) > 0){

                $mensajes[] = array("title" => "Usuari@ ya asociado", "text" => "¡".$nombres." ".$apellidos." ya partenece al curso ".get_the_title($idCurso).", por favor verifica el sistema.", "type" => "error");                

            } else {

                $nuevoAcceso = array(
                    'post_title' => 'Acceso Curso ['.$email.']',
                    'post_status' => 'private',
                    'post_type' => 'accesos_curso',
                    'post_author' => $id_user
                );
                
                $nuevoAccesoID = wp_insert_post($nuevoAcceso);
        
                if($nuevoAccesoID){

                    add_post_meta($nuevoAccesoID, 'accesos_email', $email, true);
                    add_post_meta($nuevoAccesoID, 'accesos_idtrx', 'excel_user_existente', true);
                    add_post_meta($nuevoAccesoID, 'accesos_curso_comprado', get_the_title($idCurso), true);
                    add_post_meta($nuevoAccesoID, 'accesos_curso_comprado_id', $idCurso, true);
                    add_post_meta($nuevoAccesoID, 'accesos_estado', 'activo', true);
                    add_post_meta($nuevoAccesoID, 'accesos_fecha_inicio', $fechaInicioAccesoFormat, true);
                    add_post_meta($nuevoAccesoID, 'accesos_fecha_termino', $fechaTerminoAccesoFormat, true);
                
                    if(getUnitsCourse($idCurso)){ 

                        // se crea el avance de curso
                        $tableName = $wpdb->prefix . "unidades_dinamico";

                        global $wpdb;
                        $insercion = $wpdb->insert($tableName, 
                            array(
                                'id'        => 'null',
                                'id_user'   => $id_user,
                                'id_curso'  => $idCurso,
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
                            'id_user'   => $id_user,
                            'id_curso'  => $idCurso,
                            'unidad_intro' => 0,
                            'unidad_1'  => 0,
                            'unidad_2'  => 0,
                            'unidad_3'  => 0,
                            'unidad_4'  => 0,
                            'email_admin' => 0
                        ));                        

                    }



                    if($email && $nombres){

                        /* * bienvenida-usuario-antiguo-curso-nuevo-excel * */
                        insertarEnColaCorreo($GLOBALS['tipoCorreos'][5], $id_user, $idCurso, '');

                        $inst[] = 1;

                    } else {

                        marcarError("index--fetch.php", "[EXCEL][USUARIO ANTIGUO] error al enviar correo - no vienen parametros necesarios para enviar el correo", '', $email, '');

                    }

                } else {

                    // se guarda registro del error generado
                    $arr = array();
                    foreach($nuevoAcceso as $item){
                        $arr[] = $item;
                    }
                    $data = json_encode($arr);
                    marcarError("webpay--confirmation.php", "accesos_curso para usuario existente en base de datos propia", $nuevoAccesoID->get_error_message(), $email, $data);

                }

            }

        } else {

            if($apellidos && $nombres && $rut && $email && $tel_01 && $tel_02 && $direccion && $comuna && $region){
   
                // crear clave 
                $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
                $pass = array();
                $alphaLength = strlen($alphabet) - 1;
                for ($i = 0; $i < 13; $i++) {
                    $n = rand(0, $alphaLength);
                    $pass[] = $alphabet[$n];
                }

                $clave = implode($pass); 
                // End crear clave 
                
                $dataWP = array(
                    'user_login'           => $email,
                    'first_name'           => $nombres,
                    'last_name'            => $apellidos,
                    'user_pass'            => $clave,
                    'user_email'           => $email,
                    'show_admin_bar_front' => false,
                    'role'                 => 'alumno'
                );

                $user_id = wp_insert_user( $dataWP );

                // update_user_meta( $user_id, 'user_apellido', $apellidos);
                update_user_meta( $user_id, 'user_rut', $rut);
                update_user_meta( $user_id, 'user_telefono', $tel_01);
                update_user_meta( $user_id, 'user_telefono_02', $tel_02);
                update_user_meta( $user_id, 'user_comuna', $comuna);
                update_user_meta( $user_id, 'user_region', $region);
                update_user_meta( $user_id, 'user_direccion', $direccion);

                if(! is_wp_error( $user_id)){

                    $inst[] = 1;

                    $nuevoAcceso = array(
                        'post_title'   => 'Acceso Curso ['.$email.']',
                        'post_status'  => 'private',
                        'post_type'    => 'accesos_curso',
                        'post_author'  => $user_id
                    );
                    
                    $nuevoAccesoID = wp_insert_post($nuevoAcceso);

                    add_post_meta($nuevoAccesoID, 'accesos_email', $email, true);
                    add_post_meta($nuevoAccesoID, 'accesos_idtrx', 'excel', true);
                    add_post_meta($nuevoAccesoID, 'accesos_curso_comprado', $nombreCurso, true);
                    add_post_meta($nuevoAccesoID, 'accesos_curso_comprado_id', $idCurso, true);
                    add_post_meta($nuevoAccesoID, 'accesos_estado', 'activo', true);
                    add_post_meta($nuevoAccesoID, 'accesos_fecha_inicio', $fechaInicioAccesoFormat, true);
                    add_post_meta($nuevoAccesoID, 'accesos_fecha_termino', $fechaTerminoAccesoFormat, true);

                    if($nuevoAccesoID){

                        if(getUnitsCourse($idCurso)){ 

                            // se crea el avance de curso
                            $tableName = $wpdb->prefix . "unidades_dinamico";
    
                            global $wpdb;
                            $insercion = $wpdb->insert($tableName, 
                                array(
                                    'id'        => 'null',
                                    'id_user'   => $user_id,
                                    'id_curso'  => $idCurso,
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
                                'id_curso'  => $idCurso,
                                'unidad_intro' => 0,
                                'unidad_1'  => 0,
                                'unidad_2'  => 0,
                                'unidad_3'  => 0,
                                'unidad_4'  => 0,
                                'email_admin' => 0
                            ));                        
    
                        }

                        if(!$insercion){
                            marcarError("webpay--confirmation.php", "error al crear el estado de unidades!", $insercion->print_error(), $email, '');
                        }
                        // end se crea el avance de curso

                        /* * bienvenida-usuario-nuevo * */
                        insertarEnColaCorreo($GLOBALS['tipoCorreos'][0], $user_id, $idCurso, $clave);
                        
                    } else {

                        // se guarda registro del error generado
                        $arr = array();
                        foreach($nuevoAcceso as $item){
                            $arr[] = $item;
                        }
                        $data = json_encode($arr);
                        marcarError("index--fetch.php", "[EXCEL] accesos_curso para usuario nuevo en base de datos propia", $nuevoAccesoID->get_error_message(), $email, $data);

                    }


                } else {

                    // se guarda registro del error generado
                    marcarError("index--fetch.php", "[EXCEL] crear usuario wordpress", $user_id->get_error_message(), $email, '');

                }

            } else {

                echo $mensajes[] = array("title" => "Error en archivo Excel", "text" => "Falta una columna, por favor revisar el archivo cargado, comparar con el archivo de ejemplo.", "type" => "error");

            }

        }

    }

    if(count($inst)>0){

        $mensajes[] = array("title" => "Usuarios creados", "text" => "Se ingresaron ".count($inst)." usuarios nuevos, sus correos quedaron en la cola de envio, la cual se actualiza cada 5 minutos.", "type" => "success");

    }

} else {

    if(SimpleXLSX::parseError() === "Unknown archive format"){

        $mensajes[] = array("title" => "Error en formato", "text" => "El formato debe ser .xlsx", "type" => "info");

    } else {

        $mensajes[] = array("title" => "Error en SimpleXLSX", "text" => SimpleXLSX::parseError(), "type" => "error");

    }

}

echo json_encode($mensajes);

?>