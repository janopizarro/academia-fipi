<?php
include('../../../wp-load.php'); 

$url = 'https://estudiorange.cl/develop/fipi/curso/diseno-de-planes-de-intervencion/';

if(strpos($url,home_url()) === false){
    echo "error";
} else {
    echo "si";
}
// if (str_contains($url,$trueUrl)) {
//     echo "Checking the existence of the empty string will always return true";
// }

// error_reporting(E_ALL);
// error_reporting(-1);
// ini_set('error_reporting', E_ALL);
?>