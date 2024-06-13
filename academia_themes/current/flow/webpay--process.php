<?php
include('../../../../wp-load.php');
require_once("FlowApi.class.php"); 

if($_POST){

	$url = filter_var($_POST["return"], FILTER_SANITIZE_URL);

	if(strpos($url,home_url()) === false){

		// url manipulada
		$arr = array();
		foreach($_POST as $item){
			$arr[] = $item;
		}
		$data = json_encode($arr);
		marcarError("webpay--process.php", "url de retorno manipulada en form", '- -', filter_var($_POST["email"], FILTER_SANITIZE_EMAIL), $data);

	} else {

		// información cliente
		$email    = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
		$nombre   = filter_var($_POST["nombre"], FILTER_SANITIZE_STRING);
		$apellido = filter_var($_POST["apellido"], FILTER_SANITIZE_STRING);
		$telefono = filter_var($_POST["telefono"], FILTER_SANITIZE_NUMBER_INT);

		$region   = filter_var($_POST["region"], FILTER_SANITIZE_STRING);
		$comuna   = filter_var($_POST["comuna"], FILTER_SANITIZE_STRING);
		$dir      = filter_var($_POST["dir"], FILTER_SANITIZE_STRING);

		$rut = filter_var($_POST["rut"], FILTER_SANITIZE_STRING);

		$curso    = filter_var($_POST["curso"], FILTER_SANITIZE_STRING);
		$id_curso = filter_var($_POST["id_curso"], FILTER_SANITIZE_NUMBER_INT);
		$monto    = filter_var($_POST["monto"], FILTER_SANITIZE_NUMBER_INT);

		$url_retorno = 

		$ordenCompra = date('dm')."FIPI".date('his');
		
		//Prepara el arreglo de datos
		$params = array(
			"commerceOrder"   => $ordenCompra,
			"subject"         => "Pago de curso: ".$curso."",
			"currency"        => "CLP",
			"amount"          => $monto,
			"email"           => $email,
			"paymentMethod"   => 9,
			"urlConfirmation" => get_template_directory_uri()."/flow/webpay--confirmation.php",
			"urlReturn"       => get_template_directory_uri()."/flow/webpay--result.php",
			// "optional" 		  => $adicional
		);

		// se inserta la data temporal
		$adicional = array(
			"nombre"    => $nombre,
			"apellido"  => $apellido,
			"telefono"  => $telefono,
			"region"    => $region,
			"comuna"    => $comuna,
			"direccion" => $dir,
			"rut" => $rut,
			"id_curso"  => $id_curso,
		);
		$adicional = json_encode($adicional);

		date_default_timezone_set('America/Santiago');
		$date = date('Y-m-d h:i:s', time());

		$tableName = $wpdb->prefix . "flow_temporal";

		global $wpdb;
		$insercion = $wpdb->insert($tableName, array(
			'id'          => 'null',
			'oc'       	  => $ordenCompra,
 			'data'   	  => $adicional,
			'url_retorno' => $url,
			'estado'      => 'TRANSACCIÓN INICIADA',
			'fecha_hora'  => $date
		));

		if($insercion){

			// define el metodo a usar
			$serviceName = "payment/create";

			try {

				$flowApi = new FlowApi;
				$response = $flowApi->send($serviceName, $params,"POST");
				$redirect = $response["url"] . "?token=" . $response["token"];
				
				header("location:$redirect");

			} catch (Exception $e) {
				echo $e->getCode() . " - " . $e->getMessage();
			}

		} else {

			// url manipulada
			$arr = array();
			foreach($_POST as $item){
				$arr[] = $item;
			}
			$data = json_encode($arr);
			marcarError("webpay--process.php", "error al guardar la data en tabla temporal de flow `".$tableName."`", '- -', filter_var($_POST["email"], FILTER_SANITIZE_EMAIL), $data);

		}
		
	}

}

?>