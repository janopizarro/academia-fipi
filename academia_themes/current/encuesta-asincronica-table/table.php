<?php 
$type = "asincronica";
$type_ = "asincronica";

$lib = '
    
<link rel="stylesheet" href="//cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.2/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

<script src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.print.min.js"></script>


<script>
jQuery(function($){
    $("#encuesta").DataTable({
        responsive: true,
        dom: "Bfrtip",
        buttons: [
            "copy", "csv", "excel", "pdf", "print"
        ],
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json"
        }
    });
});
</script>

';

global $wpdb;
$tableNameA = $wpdb->prefix . "encuesta_satisfaccion";
$tableNameB = $wpdb->prefix . "encuesta_satisfaccion_respuestas";

$queryRespuestas = $wpdb->get_results( " SELECT * FROM $tableNameA a INNER JOIN $tableNameB b ON a.id = b.id_encuesta WHERE a.type = '".$type."' AND a.date_time LIKE '%2022%' " );
$queryRespuestasId = $wpdb->get_results( " SELECT * FROM $tableNameA a INNER JOIN $tableNameB b ON a.id = b.id_encuesta WHERE a.type = '".$type."' AND a.date_time LIKE '%2022%' GROUP BY id_encuesta " );

foreach($queryRespuestas as $res){
    $respuestas[] = array("step" => $res->step, "data" => $res->data);
}

function getDataByStep($arr,$step){

    $dataReturn = [];

    foreach($arr as $res){
    
        if($res->step == $step){
    
            $data = json_decode($res->data, true);
    
            $preguntas = [];
            $respuestas = [];
        
            foreach($data as $da){
                $preguntas[] = $da['pregunta'];
            }
        
            foreach($data as $da){
                $respuestas[] = $da['respuesta'];
            }
        
            $i = 0;
        
            foreach($preguntas as $preg){
                $dataReturn[] = array("id_user" => $res->id_user, "fecha" => $res->date_time, "tipo" => $res->type, "id_curso" => $res->id_curso, "preg" => $preg, "resp" => $respuestas[$i]);
                $i++;
            }
            
        }
    
    }

    return $dataReturn;

}

function getOnlyQuestions($arr, $step){

    foreach($arr as $res){

        if($res->step == $step){

            $data = json_decode($res->data, true);
    
            $preguntas = [];
        
            foreach($data as $da){
                $preguntas[] = $da['pregunta'];
            }

            return $preguntas;

        }

    }   

}

// steps 
$step1 = getDataByStep($queryRespuestas, 1);
$step1Questions = getOnlyQuestions($queryRespuestas, 1);

$step2 = getDataByStep($queryRespuestas, 2);
$step2Questions = getOnlyQuestions($queryRespuestas, 2);

$step3 = getDataByStep($queryRespuestas, 3);
$step3Questions = getOnlyQuestions($queryRespuestas, 3);

$step4 = getDataByStep($queryRespuestas, 4);
$step4Questions = getOnlyQuestions($queryRespuestas, 4);

$step5 = getDataByStep($queryRespuestas, 5);
$step5Questions = getOnlyQuestions($queryRespuestas, 5);

// end steps

function groupByValue($array, $keySearch){
    $arr = array();

    foreach ($array as $key => $item) {
        $arr[$item[$keySearch]][$key] = $item;
    }

    return $arr;
}

function groupArray($questions, $array){

    $arrGroup = array();

    foreach ($questions as $res){
        $arrGroup[] = $array[$res];
    }

    return $arrGroup;

}

function getByPreg($question, $array){

    $arrGroup = array();

    $pregs = $array[$question];

    foreach ($pregs as $res){

        $arrGroup[] = $res["resp"];

    }

    return $arrGroup;

}

$array = json_decode(json_encode($queryRespuestasId), true);

// print_r($array);

function getSpecialArr($arr, $key){
    $arrResponse = array();

    foreach($arr as $data){
        $arrResponse[] = $data[$key];
    }
    return $arrResponse;
}
// $filtered = array_intersect_key( $queryRespuestasId, array_flip( array("id_user") ) );

// print_r($filtered);

$idUsers = getSpecialArr($array, "id_user");
$idCurso = getSpecialArr($array, "id_curso");
$tipo = getSpecialArr($array, "type");
$fecha = getSpecialArr($array, "date_time");

$arr1 = groupByValue($step1, "preg");
$arr2 = groupByValue($step2, "preg");
$arr3 = groupByValue($step3, "preg");
$arr4 = groupByValue($step4, "preg");
$arr5 = groupByValue($step5, "preg");

$que = array();

// foreach($arrIdUsers as $re => $key){
//     // $que[] = $re;
//     echo $re."<br>";
// }

// print_r($que);

$pregGrouped = array_merge(
    $arr1,
    $arr2,
    $arr3,
    $arr4,
    $arr5
);

// print_r($pregGrouped);

// print_r(groupArray($step1Questions, $arr));
// print_r(groupArray($step2Questions, $arr));
// print_r(groupArray($step3Questions, $arr));
// print_r(groupArray($step4Questions, $arr));
// print_r(groupArray($step5Questions, $arr));

$cabPreg = array_merge(
    $step1Questions,
    $step2Questions,
    $step3Questions,
    $step4Questions,
    $step5Questions
);

echo $lib;

?>

<style>
.notice{ display:none; }
td, th, .dataTables_info, .dataTables_filter, .paging_simple_numbers, .dt-buttons{
    font-size: 12px;
}
.btn{
    font-size: 9px;
    padding: 2px 10px;
    background: orange;
    border: orange;
    color: #121212;
}
</style>

<h9>AÑO 2022</h9>

<table border="1" id="encuesta">

    <thead>
        <tr>

            <th style='font-size: 12px'>ID Alumno</th>
            <th style='font-size: 12px'>Email Alumno</th>
            <th style='font-size: 12px'>ID Curso</th>
            <th style='font-size: 12px'>Curso</th>
            <th style='font-size: 12px'>Tipo</th>
            <th style='font-size: 12px'>Fecha</th>

            <?php

            /* * cabecera * */
            foreach($cabPreg as $res){
                echo "<th style='font-size: 12px'>".$res."</th>";
            }

            ?>

        </tr>
    </thead>

    <tbody>

        <?php 

        $arrNuevo = array();

        foreach($cabPreg as $data){
            
            $dat = array();

            $resp = getByPreg($data, $pregGrouped);

            $arrNuevo[] = $resp;

        }

        $lenght = count($arrNuevo[0]) - 1;

        $html = "<tr>";

        $i = 0;
        while ($i <= $lenght) {

            $emailAlumno = get_userdata(@$idUsers[$i]);

            $html.= "<td>".@$idUsers[$i]."</td>";

            if(@$emailAlumno->user_login){
                $html.= "<td>".$emailAlumno->user_login."</td>";
            } else {
                $html.= "<td>alumn/o ya no existe en la academia</td>";
            }

            if(@$idCurso[$i]){
                $html.= "<td>".$idCurso[$i]."</td>";
            } else {
                $html.= "<td>no hay data</td>";
            }

            if(@get_the_title($idCurso[$i])){
                $html.= "<td>".get_the_title($idCurso[$i])."</td>";
            } else {
                $html.= "<td>no hay data</td>";
            }

            if(@$tipo[$i]){
                $html.= "<td>".$tipo[$i]."</td>";
            } else {
                $html.= "<td>no hay data</td>";
            }

            if(@$fecha[$i]){
                $html.= "<td>".$fecha[$i]."</td>";
            } else {
                $html.= "<td>no hay data</td>";
            }

            foreach($arrNuevo as $funciona){ 

                if(isset($funciona[$i])){
                    $html.= "<td>".$funciona[$i]."</td>";
                } else {
                    $html.= "<td>no hubo respuesta</td>";
                }

            }

            $i++;

            $html.= "</tr>";
        }

        echo $html;

        ?>
    </tbody>
   
    
    <tfoot>
        <tr>

            <th style='font-size: 12px'>ID Alumno</th>
            <th style='font-size: 12px'>Email Alumno</th>
            <th style='font-size: 12px'>ID Curso</th>
            <th style='font-size: 12px'>Curso</th>
            <th style='font-size: 12px'>Tipo</th>
            <th style='font-size: 12px'>Fecha</th>

            <?php

            /* * cabecera * */
            foreach($cabPreg as $res){
                echo "<th style='font-size: 12px'>".$res."</th>";
            }

            ?>

        </tr>
    </tfoot>

</table>
