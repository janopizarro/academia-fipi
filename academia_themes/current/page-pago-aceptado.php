<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pago Aceptado</title>
</head>
<body>

    <?php 
    if($_GET['return']){
        $url = $_GET['return'];
        echo '<script>setTimeout(function () { window.location.href= "'.$url.'"; },3000);</script>';
    }
    ?>

    <div>
        <img src="<?php echo get_template_directory_uri(); ?>/images/pago-aceptado.svg" alt="" style="max-width: 830px; margin: 0 auto 0 auto; display: block;">
    </div>
    
</body>
</html>