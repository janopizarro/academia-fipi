<?php

if(getDataSession("email")){

	$status = json_decode(checkear_si_tiene_curso(getDataSession("email"),get_the_ID()), true);

	if($status[0]['comprado']){

		if($status[0]['estado'] != "activo"){
			redirect(5,'login');
			die();
		}

	}

} else {

	$status = array();

}

function estadoUnidad($unidad,$id_user,$id_curso){

	$estado = array();

	global $wpdb;

	$tableName = $wpdb->prefix . "estado_curso_respuestas";
	$consulta = $wpdb->get_results(" SELECT * FROM $tableName WHERE `id_user` = $id_user AND `id_curso` = $id_curso AND `unidad` = $unidad ");

	if(count($consulta) > 0){

		foreach($consulta as $res){

			if($res->intento == 1 && $res->estado === "correcta" || $res->intento == 2 && $res->estado === "correcta"){

				return true;

			} elseif ($res->intento == 2 && $res->estado === "incorrecta"){

				return true;

			} elseif ($res->intento == 1 && $res->estado === "incorrecta"){

				return false;

			} else {

				return false;

			}

		}

	} else {

		return false;

	}

}

function estadoPregunta($unidad,$id_user,$id_curso,$nPreg){

	global $wpdb;

	$tableName = $wpdb->prefix . "estado_curso_respuestas";
	$consulta = $wpdb->get_results(" SELECT * FROM $tableName WHERE `id_user` = $id_user AND `id_curso` = $id_curso AND `unidad` = $unidad AND `n_preg` = $nPreg ");

	if(count($consulta) > 0){

		foreach($consulta as $res){

			if($res->estado === "correcta"){
				return "ok";
			} elseif ($res->estado === "incorrecta" && $res->intento = 2){
				return "ok";
			} else {
				return false;
			}

		}

	}

}

function obtenerRespuestaCorrecta($unidad,$id_user,$id_curso,$nPreg){

	global $wpdb;

	$tableName = $wpdb->prefix . "estado_curso_respuestas";
	$consulta = $wpdb->get_results(" SELECT * FROM $tableName WHERE `id_user` = $id_user AND `id_curso` = $id_curso AND `n_preg` = $nPreg AND `estado` = 'correcta' AND `unidad` = $unidad ");

	if(count($consulta) > 0){

		foreach($consulta as $res){

			return $res->respuesta;

		}

	}

}

function obtenerRespuestaIncorrecta($unidad,$id_user,$id_curso,$nPreg){

	global $wpdb;

	$tableName = $wpdb->prefix . "estado_curso_respuestas";
	$consulta = $wpdb->get_results(" SELECT * FROM $tableName WHERE `id_user` = $id_user AND `id_curso` = $id_curso AND `n_preg` = $nPreg AND `estado` = 'incorrecta' AND `intento` = 2 AND `unidad` = $unidad ");

	if(count($consulta) > 0){

		foreach($consulta as $res){

			return $res->respuesta;

		}

	}

}

function formatearPrecio($monto){

	if($monto){
		$monto_ = number_format($monto);
		$monto_ = str_replace(",",".",$monto_);
		return "$".$monto_;
	} else {
		return 'Por favor completar este campo';
	}


}

// NUEVAS 

function getPercentageDinamic($id_course, $id_user){

	$total = array();

    foreach(getUnitsCourse($id_course) as $res){

        $id_unit = $res->ID;

        $questions_unit = get_post_meta( $id_unit, 'preguntas_unidad', true);

        foreach($questions_unit as $question){

            $total[] = $question;

        }

    }

    global $wpdb;
    $tableName = $wpdb->prefix . "unidades_dinamico_respuestas";
    $consulta = $wpdb->get_results(" SELECT * FROM $tableName WHERE `id_user` = $id_user AND `id_curso` = $id_course ");

    $percentage = ( count($consulta) / count($total) ) * 100;

    if($percentage >= 0){

        return number_format($percentage);

    } else {

        echo "error porcentaje";

    }

}



?>