<?php 
/* 
 * * Template name: Buscar Certificado * */
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar certificado</title>
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/features/link-generar-certificado.css?var=<?php echo date("Y-m-d-i:s")?>">
</head>

<script>
function validarRut(rut) {
    rut = rut.replace(/\./g, '').replace(/-/g, '');
    if (!/^[0-9]+[kK0-9]$/.test(rut)) {
        return false;
    }
    let cuerpo = rut.slice(0, -1);
    let dv = rut.slice(-1).toUpperCase();
    let suma = 0;
    let multiplo = 2;

    for (let i = cuerpo.length - 1; i >= 0; i--) {
        suma += multiplo * rut.charAt(i);
        multiplo = multiplo < 7 ? multiplo + 1 : 2;
    }

    let dvEsperado = 11 - (suma % 11);
    dvEsperado = dvEsperado === 11 ? '0' : dvEsperado === 10 ? 'K' : dvEsperado.toString();

    return dvEsperado === dv;
}

function validarFormulario(event) {
    const rutInput = document.getElementById('documentNumber');
    const rut = rutInput.value.trim();

    if (!validarRut(rut)) {
        event.preventDefault();
        alert('El RUT ingresado no es válido. Por favor, ingrese un RUT válido.');
        return false;
    }

    return true;
}

window.onload = function() {
    const form = document.querySelector('form');
    form.addEventListener('submit', validarFormulario);
}
</script>

<body>
    
    <div class="bloque">
        <img class="logo" src="https://academia.fipi.cl/wp-content/themes/portal-fipi/images/logo.png" alt="">
        <h2>Buscador de Certificados:</h2>
        <p>A continuación ingresa tu Rut y presiona <strong>BUSCAR CERTIFICADO</strong>.</p>
        <small>Sin puntos ni guión</small>
        <form action="<?php echo get_template_directory_uri(); ?>/features/link-generar-certificado-search.php" method="POST">
            <div class="campo">
                <label for="documentNumber">RUT:</label>
                <input type="text" name="documentNumber" id="documentNumber" required />
            </div>
            <button type="submit">BUSCAR CERTIFICADO</button>
        </form>
    </div>

</body>
</html>