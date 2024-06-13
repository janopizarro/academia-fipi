<?php 
$x_ = 3;

$type = "sincronico";
$type_ = "sincronico";

echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">';
echo '

<style>
table {
    display: flex !important;
    flex-flow: column !important;
    width: 100% !important;
}

thead {
    flex: 0 0 auto !important;
}

tbody {
    flex: 1 1 auto !important;
    display: block !important;
    overflow-y: auto !important;
    overflow-x: hidden !important;
    height: 400px;
}

tr {
    width: 100% !important;
    display: table !important;
    table-layout: fixed !important;
}                
</style>

';

$id_curso_consultar = 0;
    
if(@$_POST){
    if(@$_POST["curso"] == "ver-todos"){
       echo "<script>location.reload();</script>"; 
    } else {
        $id_curso_consultar = $_POST["curso"];
    }
}

/* * * * buscar respuestas de encuesta * * * */
$respuestas = array();

if(@$_POST["fecha_a_buscar"]){
    $format = explode("-",$_POST["fecha_a_buscar"]);
    $fecha = $format[2]."-".$format[1]."-".$format[0];
}

global $wpdb;
$tableNameA = $wpdb->prefix . "encuesta_satisfaccion";
$tableNameB = $wpdb->prefix . "encuesta_satisfaccion_respuestas";

if(@$id_curso_consultar){
    $queryRespuestas = $wpdb->get_results( " SELECT * FROM $tableNameA a INNER JOIN $tableNameB b ON a.id = b.id_encuesta WHERE a.type = '".$type."' AND a.id_curso = '".$id_curso_consultar."' " );
    $queryEncuestados = $wpdb->get_results( " SELECT * FROM $tableNameA WHERE type = '".$type."' AND id_curso = '".$id_curso_consultar."' " );
} else if (@$fecha){
    $queryRespuestas = $wpdb->get_results( " SELECT * FROM $tableNameA a INNER JOIN $tableNameB b ON a.id = b.id_encuesta WHERE a.type = '".$type."' AND a.date_time LIKE '%".$fecha."%' " );
    $queryEncuestados = $wpdb->get_results( " SELECT * FROM $tableNameA WHERE type = '".$type."' AND date_time LIKE '%".$fecha."%' " );
} else { 
    $queryRespuestas = $wpdb->get_results( " SELECT * FROM $tableNameA a INNER JOIN $tableNameB b ON a.id = b.id_encuesta WHERE a.type = '".$type."' " );
    $queryEncuestados = $wpdb->get_results( " SELECT * FROM $tableNameA WHERE type = '".$type."' " );
}

foreach($queryRespuestas as $res){
    $respuestas[] = array("step" => $res->step, "data" => $res->data);
}
/* * * * end buscar respuestas de encuesta * * * */

function verificarRespuestas($step, $type, $fecha, $id_curso_consultar){
    $respuestas = array();
    global $wpdb;
    $tableNameA = $wpdb->prefix . "encuesta_satisfaccion";
    $tableNameB = $wpdb->prefix . "encuesta_satisfaccion_respuestas";
    if(@$id_curso_consultar){
        $queryRespuestas = $wpdb->get_results( " SELECT * FROM $tableNameA a INNER JOIN $tableNameB b ON a.id = b.id_encuesta WHERE a.type = '".$type."' AND b.step = ".$step." AND a.id_curso = '".$id_curso_consultar."' " );
    } else if(@$fecha){
        $queryRespuestas = $wpdb->get_results( " SELECT * FROM $tableNameA a INNER JOIN $tableNameB b ON a.id = b.id_encuesta WHERE a.type = '".$type."' AND b.step = ".$step." AND a.date_time LIKE '%".$fecha."%' " );
    } else {
        $queryRespuestas = $wpdb->get_results( " SELECT * FROM $tableNameA a INNER JOIN $tableNameB b ON a.id = b.id_encuesta WHERE a.type = '".$type."' AND b.step = ".$step." " );
    }
    return $queryRespuestas;
}

function obtenerRespuestasDePregunta($step, $preg, $type, $fecha, $id_curso_consultar){

    $ARRNEW = array();

    $array = json_decode(json_encode(verificarRespuestas($step, $type, $fecha, $id_curso_consultar)), true);
    
    foreach($array as $res){
        $ARRNEW[] = $res['data'];
    }
    
    $norm = array();
    
    foreach($ARRNEW as $some){
        $norm[] = json_decode($some, true);
    }
    
    $asd = array();
    
    $respuestas_arr = array();
    
    foreach($norm as $d => $index){
    
        foreach($index as $raf){
    
            if($raf['pregunta']){
    
                if($raf['pregunta'] === $preg){
                    $respuestas_arr[] = $raf['respuesta'];
                }
    
            }
    
        }
    
    }

    return $respuestas_arr;

}

function obtenerEmailRespuestasDePregunta($step, $preg, $type, $fecha, $id_curso_consultar){

    $ARRNEW = array();

    $array = json_decode(json_encode(verificarRespuestas($step, $type, $fecha, $id_curso_consultar)), true);

    foreach($array as $res){
        $user_info = get_userdata($res['id_user']);
        $ARRNEW[] = @$user_info->user_login;
    }
    
    return $ARRNEW;

}

// print_r(verificarRespuestas($respuestas, $step, $rev->post_title, $alternativa));

// /* * * * * * * * * * * * * */
// function countRepeats(){
//     return true;
// }

// function getAnswers($step){
//     global $wpdb;
//     $tableNameA = $wpdb->prefix . "encuesta_satisfaccion";
//     $tableNameB = $wpdb->prefix . "encuesta_satisfaccion_respuestas";
//     $data = $wpdb->get_results( " SELECT * FROM $tableNameA a INNER JOIN $tableNameB b ON a.id = b.id_encuesta WHERE a.type = 'sincronico' AND b.step = $step " );
//     return $data;
// }

// function buscarOcurrencias($pregunta, $step){
//     global $wpdb;
//     $tableNameA = $wpdb->prefix . "encuesta_satisfaccion";
//     $tableNameB = $wpdb->prefix . "encuesta_satisfaccion_respuestas";
//     $query = $wpdb->get_results( " SELECT * FROM $tableNameA a INNER JOIN $tableNameB b ON a.id = b.id_encuesta WHERE a.type = 'sincronico' AND b.step = $step " );
    
//     $datas = array();

//     foreach($query as $res){
//         $datas[] = json_decode($res->data);
//     }

//     $asd = array();

//     $x = 0;
//     foreach($datas as $dat){ $x++;
//         $c = $x - 1;
//         if(strcmp($dat[$c]->pregunta, $pregunta) == 0){
//             $asd[] = "si ".$dat[$c]->respuesta;
//         }
//         // if($dat[$c]->pregunta == $pregunta){
//         //     $asd[] = $dat[$c]->respuesta;
//         // }
//     }

//     return $datas;

// }
/* * * * * * * * * * * * * */

$grupos_preguntas = [];
$grupos_actuales = get_categories('taxonomy=categoria_encuesta_'.$type_.'&type=encuesta_'.$type.'');

/* * * * * * * * * * * * * */
// $preguntas = array();
// $respuestas = array();

// $answers = json_decode(json_encode(getAnswers($step)), true);

// $datas = array();

// foreach($answers as $ans){
//     $datas[] = json_decode($ans["data"]);
// }

// print_r($datas);

// $arr = array();

// $u = 0;
// foreach ($datas as $value){ $u++;
//     $c = $u - 1;
//     $arr[] = $value[$c]->pregunta;
    
// }

// print_r($arr);
/* * * * * * * * * * * * * */

if(count($grupos_actuales) > 0){

    $queryCursos_ = $wpdb->get_results( " SELECT * FROM $tableNameA WHERE type = '".$type."' " );

    /* * * * * */
    echo "<h4 style='font-size: 18px;'>".count($queryEncuestados)." alumnos han respondido la encuesta de los siguientes cursos:</h4>";
    /* * * * * */

    $cursos = array();

    foreach($queryEncuestados as $res){
        $cursos[] = $res->id_curso;
    }

    foreach($queryCursos_ as $res_){
        $cursos_[] = $res_->id_curso;
    }
    ?>

    <div class='grupo_grafico' style="margin-bottom: 60px;">

    <div class='grupo_ p-3'>
        <form action="" method="post">
            <p>Para ver una fecha en particular, selecciona acá:</p>
            <input type="date" name="fecha_a_buscar" onchange='this.form.submit()' placeholder="Seleccionar" <?php if(@$fecha){ echo "value='".$_POST["fecha_a_buscar"]."'"; }?>>
        </form>
    </div>
    <br>
    <div class='grupo_ p-3'>
        <p>Para ver un curso en especifico, selecciona acá:</p>
        <form action="" method="post">
            <select  onchange='this.form.submit()' name="curso" id="curso">
                <option>Selecciona un curso</option>
                <?php 
                    foreach(array_count_values($cursos_) as $x => $key){
                        $selected = "";
                        if($id_curso_consultar && $id_curso_consultar == $x){
                            $selected = "selected";
                        }
                        echo "<option ".$selected." value='".$x."'>".get_the_title($x)."</option>";
                    }
                ?>
                <option value="ver-todos">VER TODOS LOS CURSOS</option>
            </select>
        </form>
    </div>

    <div class='grupo_'>

        <div id="canvas-holder-init" style="min-width:620px; max-width:700px;">
            <canvas id="chart-area_init"></canvas>
        </div>

        <script>
        var config0_init = {
            type: 'pie',
            data: {
                datasets: [{
                    data: [
                        <?php 
                        foreach(array_count_values($cursos) as $x => $key){
                            echo $key.",";
                        }
                        ?>
                    ],
                    backgroundColor: ["#347C17","#BCE954","#FFEF00","#C04000","#FF4500","#550A35","#583759","#4B0150","#41A317"],
                    label: 'Dataset <?php echo $x; ?>'
                }],
                labels: [
                    <?php 
                    foreach(array_count_values($cursos) as $x => $key){
                        echo "'".get_the_title($x)."',";
                    }
                    ?>
                ],
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Encuesta <?php echo ucfirst($type); ?>',
                    }
                }
            }
        };

        var ctx_init = document.getElementById('chart-area_init').getContext('2d');
        var ctx0init = new Chart(ctx_init,config0_init);      
        </script>

        </div>

    </div>

    <?php
    foreach ($grupos_actuales as $grupo) {
        $grupos_preguntas[] = array("id_grupo" => $grupo->term_id, "titulo_grupo" => $grupo->name, "slug_grupo" => $grupo->slug, "descripcion_grupo" => $grupo->description);
    }

    $alternativas_array = array();
    $preguntas_array = array();
    
    $s = 0;
    foreach ($grupos_preguntas as $res){ $s++;

        $step = $s;

        $datas = array();

        $verificar = verificarRespuestas($step, $type, @$fecha, @$id_curso_consultar);

        foreach($verificar as $res_){
            $datas[] = json_decode($res_->data, true);
        }

        $PreguntasRespuestas = array();

        
        $arr = new RecursiveIteratorIterator(new RecursiveArrayIterator($datas));
        $new_arr = iterator_to_array($arr, false);

        $i = 0;
        foreach($datas as $da => $de){ $i++;
            $c = $i - 1;
            if(isset($de[$c])){
                $PreguntasRespuestas[] = $de[$c]["pregunta"]." <=> ".$de[$c]["respuesta"];
            }
        }

        echo "<div class='grupo_grafico'>";

        echo '<h2><small>Paso: '.$step.'</small> · '.$res["titulo_grupo"].'</h2>';

        $html = '';

        $args = array(
            'showposts' => -1,
            'post_type' => 'encuesta_'.$type.'',
            'order'     => 'DESC',
            'tax_query' => array(
                array(
                    'taxonomy' => 'categoria_encuesta_'.$type_.'', 
                    'terms'    => array($res["id_grupo"])
                )
            )
        );
        
        $loop = new WP_Query( $args );

        $reverse = array_reverse($loop->posts);

        $o = 0;

        foreach($reverse as $rev) { $o++;

            echo "<div class='grafico_'>";

            $tieneAlternativas = count(get_post_meta( $rev->ID, 'encuesta_'.$type.'_alternativas', true )) > 1 ? 'si' : 'no';

            echo "<h3>".$rev->post_title."</h3>";

            
            if($tieneAlternativas === "si"){

                $alts = obtenerRespuestasDePregunta($step, $rev->post_title, $type, @$fecha, @$id_curso_consultar);                
                $count_values = array_count_values($alts);

                $preguntas_array[] = $rev->post_title;

                // $script = "
    
                // <script>
                //     var config0".$s.$i." = {
                //         type: 'pie',
                //         data: {
                //             datasets: [{
                //                 data: [";

                $alts_arr = array();
                $alts_arr_normal = array();

                $i = 0;
                foreach(get_post_meta( $rev->ID, 'encuesta_'.$type.'_alternativas', true ) as $res){ $i++;
                    
                    if($res['encuesta_'.$type.'_alternativa'] !== ""){

                        $alternativa = $res['encuesta_'.$type.'_alternativa'];

                        $alts_arr[] = "'".$alternativa."'";
                        $alts_arr_normal[] = $alternativa;

                    }

                }

                $alts_to_graphic = implode(",",$alts_arr);

                $arrayColors = ["#347C17","#BCE954","#FFEF00","#C04000","#FF4500","#550A35","#583759","#4B0150","#41A317","#347C17","#BCE954","#FFEF00","#C04000","#FF4500","#550A35","#583759","#4B0150","#41A317"];

                $script = "

                <div id='canvas-holder".$rev->ID."' style='min-width:450px; max-width:500px;'>
                    <canvas id='chart-area".$rev->ID."'></canvas>
                </div>
                
                <script>
                    var config".$rev->ID." = {
                        type: 'pie',
                        data: {
                            datasets: [{
                                data: [";
                                
                                $zero = 0;
                                $numItems = count($alts_arr_normal);
                                $e = 0;
                                foreach($alts_arr_normal as $alts_){ $e++;
                                    if(isset($count_values[$alts_])){
                                        if($e != $numItems){
                                            $script .= $count_values[$alts_].",";
                                        } else {
                                            $script .= $count_values[$alts_];
                                        }
                                    } else {
                                        if($e != $numItems){
                                            $script .= $zero.",";
                                        } else {
                                            $script .= 0;
                                        }
                                    }
                                }
                                
                                $script .= "],
                                backgroundColor: [";
                                
                                $d = 0;
                                foreach($alts_arr_normal as $nr){ $d++;
                                    if($d != $numItems){
                                        $script .= "'".$arrayColors[$d]."',";
                                    } else {
                                        $script .= "'".$arrayColors[$d]."'";
                                    }
                                }
                                
                                $script .= "],
                                label: 'Dataset ".$s.$i."'
                            }],
                            labels: [";
                            
                            $d = 0;
                            foreach($alts_arr_normal as $nr){ $d++;
                                $num_ = 0;
                                if(isset($count_values[$nr])){
                                    $num_ = $count_values[$nr];
                                }
                                if($d != $numItems){
                                    $script .= "'".$nr." (".$num_.")',";
                                } else {
                                    $script .= "'".$nr." (".$num_.")'";
                                }
                            }
                            
                            $script .= "],
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                title: {
                                    display: true,
                                    text: 'Resultados: ".$rev->post_title."',
                                }
                            }
                        }
                    };

                    var ctx".$rev->ID." = document.getElementById('chart-area".$rev->ID."').getContext('2d');
                    var ctx0".$rev->ID." = new Chart(ctx".$rev->ID.",config".$rev->ID.");                  

                </script>
                
                ";

                echo $script;

            } else {

                $resps = obtenerRespuestasDePregunta($step, $rev->post_title, $type, @$fecha, @$id_curso_consultar);
                
                $asd = obtenerEmailRespuestasDePregunta($step, $rev->post_title, $type, @$fecha, @$id_curso_consultar);

                // print_r($asd);

                /* * * * * * * * * * * */
                $html_table = '                
                <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Email</th>
                        <th scope="col">Respuesta</th>
                    </tr>
                </thead>
                <tbody>';

                $o = 0;
                foreach($resps as $resps_){ 
                    $html_table .= '<tr>
                        <td>'.$asd[$o].'</td>
                        <td>'.$resps_.'</td>
                    </tr>';
                    $o++;
                }

                $html_table .= '
                </tbody>
                </table>
                ';

                echo $html_table;
                /* * * * * * * * * * * */

            }

            echo "</div>";

        }
        
        echo "</div>";

        // echo "</div>";

    }

}







// ';
                        
//                             $loop = obtenerPreguntas($res["id_grupo"], $type, $type_);

//                             // print_r($loop);

//                             while ( $loop->have_posts() ) : $loop->the_post();
        
//                                 $tieneAlternativas = count(get_post_meta( get_the_ID(), 'encuesta_'.$type.'_alternativas', true )) > 1 ? 'si' : 'no';
        
//                                 if($tieneAlternativas === "si"){
        
//                                     foreach(get_post_meta( get_the_ID(), 'encuesta_'.$type.'_alternativas', true ) as $resultado){
//                                         $alternativa = $resultado['encuesta_'.$type.'_alternativa'];
//                                         $html .= '"'.$alternativa.'",';
//                                     }
        
//                                 } else {
//                                     echo '';
//                                 }
        
//                             endwhile;
//                             wp_reset_postdata();

                        
//                         $html .= 


























?>

<!-- <div id="canvas-holder-0<?php echo $x_; ?>" style="max-width:400px;">
    <canvas id="chart-area<?php echo $x_; ?>"></canvas>
</div>

<?php
// global $wpdb;
// $tableName = $wpdb->prefix . "encuesta_satisfaccion";
// $data = $wpdb->get_results( " SELECT * FROM $tableName WHERE type = 'sincronico' " );

// $alumnos = array();

// foreach($data as $res){
//     $alumnos[] = $res->id_user;
// }
?>

<script>
    var config0<?php echo $x_ ?> = {
        type: 'pie',
        data: {
            datasets: [{
                data: [
                    <?php 
                    foreach(array_count_values($alumnos) as $x => $key){
                        echo $key.",";
                    }
                    ?>
                ],
                backgroundColor: 'green',
                label: 'Dataset <?php echo $x; ?>'
            }],
            labels: [
                <?php 
                foreach(array_count_values($alumnos) as $x => $key){
                    echo "'".get_the_title($x)."',";
                }
                ?>
            ],
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Chart Title',
                }
            }
        }
    };

</script> -->