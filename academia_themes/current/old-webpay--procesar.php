<?php
include('../../../wp-load.php');
require_once("flow/FlowApi.class.php"); 

// print_r($_POST);

if($_POST){

	// información cliente
	$email  = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
	$nombre = $_POST["nombre"];
	$apellido = $_POST["apellido"];
	$telefono = $_POST["telefono"];
	$curso  = $_POST["curso"];
	$id_curso = $_POST["id_curso"];
	$monto  = $_POST["monto"];

	$ordenCompra = date('dm')."FIPI".date('his');
	
	$adicional = array(
		"nombre" => $nombre,
		"apellido" => $apellido,
		"telefono" => $telefono,
		"id_curso" => $id_curso
	);
	$adicional = json_encode($adicional);

	//Prepara el arreglo de datos
	$params = array(
		"commerceOrder"   => $ordenCompra,
		"subject"         => "Pago Curso: ".$curso."",
		"currency"        => "CLP",
		"amount"          => $monto,
		"email"           => $email,
        "paymentMethod"   => 9,
        "urlConfirmation" => "".get_template_directory_uri()."/webpay--confirmacion.php",
		"urlReturn"       => "".get_template_directory_uri()."/webpay--retorno.php",
		"optional" 		  => $adicional
	);
	
	//Define el metodo a usar
	$serviceName = "payment/create";

	try {

		$flowApi = new FlowApi;
		$response = $flowApi->send($serviceName, $params,"POST");
        $redirect = $response["url"] . "?token=" . $response["token"];
		
        header("location:$redirect");

	} catch (Exception $e) {
		echo $e->getCode() . " - " . $e->getMessage();
	}

}

error_reporting(E_ALL);
error_reporting(-1);
ini_set('error_reporting', E_ALL);

?>