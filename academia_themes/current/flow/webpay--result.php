<?php
session_start();

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
   
    if($response['status'] == 2){

        $tableNameTmp = $wpdb->prefix . "flow_temporal";

        $commerceOrder = $response['commerceOrder'];
        $existeOC = $wpdb->get_results( "SELECT * FROM $tableNameTmp WHERE `oc` = '$commerceOrder' limit 0,1" );

        foreach($existeOC as $oc){
            $url = $oc->url_retorno;
        }

        // se inicia la sesión academia fipi
        $userdata = get_user_by('email', $response['payer']);
        $_SESSION["user_fipi"] = array("id" => $userdata->ID, "nombre" => $userdata->first_name, "email" => $response['payer']);

        // se elimina la ta información temporal
        $delete = $wpdb->delete($tableNameTmp,array('oc' => $commerceOrder),array('%d'));

        echo "<script>window.location.replace('".home_url()."/pago-aceptado/?return=".$url."');</script>";

    } else {

        echo "<script>window.location.replace('".home_url()."/pago-rechazado');</script>";

    }

} catch (Exception $e) {
    echo $e->getCode() . " - " . $e->getMessage();
}
?>