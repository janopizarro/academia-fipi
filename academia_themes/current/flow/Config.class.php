<?php
/**
 * Clase para Configurar el cliente
 * @Filename: Config.class.php
 * @version: 2.0
 * @Author: flow.cl
 * @Email: csepulveda@tuxpan.com
 * @Date: 28-04-2017 11:32
 * @Last Modified by: Carlos Sepulveda
 * @Last Modified time: 28-04-2017 11:32
 */
 
// PRUEBAS

// $COMMERCE_CONFIG = array(
//  	"APIKEY" => "5BFD8FC2-C848-4B36-AFFD-48CDLD269D8A", // Registre aquí su apiKey
//  	"SECRETKEY" => "f08bf8b5c1700f084cb3e503cded3a923d2e9f1e", // Registre aquí su secretKey
//  	"APIURL" => "https://sandbox.flow.cl/api", // Producción EndPoint o Sandbox EndPoint
//  	"BASEURL" => "https://academia.fipi.cl/wp-content/themes/portal-fipi" //Registre aquí la URL base en su página donde instalará el cliente
//  );

// PRODUCTIVO

$COMMERCE_CONFIG = array(
	"APIKEY" => "79B83EBF-1368-424F-9297-784490CL57A7", // Registre aquí su apiKey
	"SECRETKEY" => "b94aec55b1f8b28b0915766bd9e60e795e080384", // Registre aquí su secretKey
	"APIURL" => "https://www.flow.cl/api", // Producción EndPoint o Sandbox EndPoint
	"BASEURL" => "https://academia.fipi.cl/wp-content/themes/portal-fipi" //Registre aquí la URL base en su página donde instalará el cliente
);
 
 class Config {
 	
	static function get($name) {
		global $COMMERCE_CONFIG;
		if(!isset($COMMERCE_CONFIG[$name])) {
			throw new Exception("¡Configuración no existe!", 1);
		}
		return $COMMERCE_CONFIG[$name];
	}
 }
