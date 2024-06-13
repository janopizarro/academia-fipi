<?php 
session_start();

unset($_SESSION["user_fipi"]);
session_destroy();

redirect(1000,'login');
?>