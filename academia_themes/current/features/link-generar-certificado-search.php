<?php 
require_once($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');

function mesEnPalabras($numeroMes) {
    switch ($numeroMes) {
        case 1:
            return "Enero";
        case 2:
            return "Febrero";
        case 3:
            return "Marzo";
        case 4:
            return "Abril";
        case 5:
            return "Mayo";
        case 6:
            return "Junio";
        case 7:
            return "Julio";
        case 8:
            return "Agosto";
        case 9:
            return "Septiembre";
        case 10:
            return "Octubre";
        case 11:
            return "Noviembre";
        case 12:
            return "Diciembre";
        default:
            return "Mes inválido";
    }
}
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

<body>

<?php 

function getDataExCourse($id) {
    global $wpdb;
    $tableName = $wpdb->prefix . "ex_ficha";

    $query = $wpdb->get_results( " SELECT * FROM $tableName WHERE `id_curso` = $id " );

    return $query[0];
}

function getDataUser($id) {
    global $wpdb;
    $tableName = $wpdb->prefix . "ex_persona";

    $query = $wpdb->get_results( " SELECT * FROM $tableName WHERE `id_per_persona` = $id " );

    return $query[0];
}

$documentNumber = $_POST["documentNumber"];

// $documentNumber = "116113546";

global $wpdb;
$tableName = $wpdb->prefix . "ex_persona";

$query = $wpdb->get_results( " SELECT * FROM $tableName WHERE `RUT` = ".$documentNumber." " );

$nombreApellido = $query[0]->Nombre1." ".$query[0]->Nombre2." ".$query[0]->Apellido1." ".$query[0]->Apellido2;

echo '<div class="bloque"><img class="logo" src="https://academia.fipi.cl/wp-content/themes/portal-fipi/images/logo.png" alt="">';

require_once( __DIR__ . '/link-generar-certificado-functions.php');

if(count($query) > 0){

    global $wpdb;
    $tableName = $wpdb->prefix . "ex_notas";
    
    $query = $wpdb->get_results( " SELECT * FROM $tableName WHERE `id_alumno` = ".$query[0]->id_persona." " );

    if(count($query) > 0){

        echo "<h2>Hola! ".$nombreApellido."</h2>";
        echo "<p>Tienes disponible para generar (".count($query).") certificado/s, a continuación mostramos el curso realizado y los botones para descargar.</p>";
    
        $i = 0;

        foreach($query as $res){ $i++;

            $dataCurso = getDataExCourse($res->id_curso);

            // echo $dataCurso->alias."<br>";
            $hours = $dataCurso->durac_hrs;

            $multiply = $i*3;
            $multiply2 = $i*10;

            $type = "";

            if($dataCurso->id_tipo_curso === "1"){
                $type = "Abierto";
            } else {
                $type = "Cerrado";
            }

            $fechaInicio = explode("-",$dataCurso->f_inicio);

            $year = $fechaInicio[0];
            $month = mesEnPalabras($fechaInicio[1]);

            $html = certificadoCurso($multiply, $res->id_curso, $nombreApellido, $month, $year, $dataCurso->alias, $type, $hours);
            $html2 = certificadoDiploPost($multiply2, $res->id_curso, $nombreApellido, $month, $year, $dataCurso->alias, $type, $hours);
            
            echo $html;
            echo $html2;

            $texto_minusculas = strtolower($nombreApellido );
            $texto_formateado = str_replace(' ', '-', $texto_minusculas);

            date_default_timezone_set('America/Santiago');
            $date = date('d-m-Y');

            ?>

            <div class="bloque-curso">
                <small><?php echo $dataCurso->alias; ?></small>
                <small class="bloque-curso-fecha">Fecha: <?php echo $month; ?> <?php echo $year; ?></small>
                <div class="bloque-curso-botones">
                    <?php echo '<a href="#" class="descargar-curso" onclick="generarPDF(\'pdf_curso_' . $res->id_curso . '\', \'' . $texto_formateado . '\', \'' . $date . '\')"><img src="'.get_template_directory_uri().'/features/img/pdf-document-svgrepo-com.svg" /> Descargar certificado Curso</a>'; ?>
                    <?php echo '<a href="#" class="descargar-curso descargar-curso--yellow" onclick="generarPDF(\'pdf_diplo_post_' . $res->id_curso . '\', \'' . $texto_formateado . '\', \'' . $date . '\')"><img src="'.get_template_directory_uri().'/features/img/pdf-document-svgrepo-com.svg" /> Descargar certificado Diplomado/Post titulo</a>'; ?>
                </div>
            </div>

            <?php 

        }

    } else {

        echo "<h2>El RUT <i>".$documentNumber."</i> existe pero no tiene actividad en curso(s).</h2>";
        
    }

    echo "<a href='".home_url()."' class='back-academy'>Regresar a Academia</a>";

} else {
    echo "<h2>No se encontró nada para el RUT ingresado.</h2>";

    echo "<a href='".home_url()."' class='back-academy'>Regresar a Academia</a>";

}

echo "</div>";
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.8.0/html2pdf.bundle.min.js"></script>
<script src="index.js"></script>

<script>
function generarPDF(id, nombre, fecha) {
    var element = document.getElementById(id);
    
    var opciones = {
    margin: 10,
    filename: `${nombre}-${fecha}.pdf`,
    image: { type: 'jpeg', quality: 0.98 },
    html2canvas: { scale: 3 },
    jsPDF: { unit: 'mm', format: 'a4', orientation: 'landscape' } // Aquí se establece la orientación
    };

    html2pdf(element, opciones);
}
</script>

</body>
</html>