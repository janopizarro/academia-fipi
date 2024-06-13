<?php 

// load wordpress
require_once($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');

// Verifica que la solicitud es POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtiene el cuerpo de la solicitud
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    // Verifica que los datos han sido decodificados correctamente
    if (json_last_error() === JSON_ERROR_NONE) {
        $mostrarCertificado = $data['mostrarCertificado'];
        $idShowCert = $data['idShowCert'];

        // Aquí puedes manejar la lógica con los datos recibidos
        // Por ejemplo, actualizar la base de datos, etc.

        // http_response_code(500);
        // Responde al cliente
        // echo 'Datos recibidos correctamente '.$mostrarCertificado." - ".$idShowCert;

        global $wpdb;

        $tableName = $wpdb->prefix . "unidades_dinamico";
        $update = $wpdb->update($tableName, array('show_cert' => $mostrarCertificado),array('id' => $idShowCert));

        if ($update === false) {
            echo json_encode(array("status" => "error", "message" => $wpdb->last_error));
        } else {
            echo json_encode(array("status" => "ok", "message", $update));
        }


    } else {
        // Error al decodificar el JSON
        echo 'Error al decodificar JSON';
    }
} else {
    // Respuesta para solicitudes que no son POST
    echo 'Método no soportado';
}