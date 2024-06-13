<?php
// Resumen Alumnos
add_action('admin_menu', 'register_reporte_general_encuestas_page');

function register_reporte_general_encuestas_page() {
  add_submenu_page( 'users.php', 'Reporte General Encuestas', 'Reporte General Encuestas', 'manage_options', 'reporte_general_encuestas', 'reporte_general_encuestas_page_callback' ); 
}

function reporte_general_encuestas_page_callback() { ?>    

    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/features/resumen-general-encuestas.css">

    <style>
    .notice, .update-nag, .updated{ display:none; }
    td, th, .dataTables_info, .dataTables_filter, .paging_simple_numbers, .dt-buttons{
        font-size: 12px;
    }
    .msg_xlsx{ display: block; padding: 10px; background: #c6efff; border: 1px solid #63d8e4; margin-top: 20px; margin-bottom: -20px; color: #31aeaf; }
    </style>

    <h1>Reporte General Encuestas - Academia FIPI [EN QA]</h1>

    <!-- <a id="download">Tomar screenshot y descargar</a>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js" integrity="sha512-BNaRQnYJYiPSqHHDb58B0yaPfCu+Wgds8Gp/gU33kqBtgNS4tSPHuGibyoeqMV/TJlSKda6FXzoEyYGjTe+vXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
    html2canvas(document.body, {
    onrendered (canvas) {
        var link = document.getElementById('download');;
        var image = canvas.toDataURL();
        link.href = image;
        link.download = 'screenshot.png';
    }
    });
    </script> -->

    <?php 
    $idCurso = @$_POST["curso"];
    $year = @$_POST["year"];
    ?>

    <form method="post" action="">

        <!-- seleccionar fecha -->
        <!-- <select name="year" id="year">
            <option value="">Seleccionar Año</option>
            <option value="2020">2020</option>
            <option value="2021">2021</option>
            <option value="2022">2022</option>
            <option value="2023">2023</option>
            <option value="2024">2024</option>
        </select> -->

        <!-- seleccionar curso a revisar -->
        <select name="curso" id="curso">
            <option value="0">Selecciona un curso</option>
            <?php 
            $argpCursos = array(  
                'post_type' => 'curso',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'order' => 'ASC'
            );

            $loopCursos = new WP_Query( $argpCursos );

            while ( $loopCursos->have_posts() ) : $loopCursos->the_post();
            ?>

                <option <?php if($idCurso == get_the_ID()){echo "selected";} ?> value="<?php echo get_the_ID(); ?>"><?php echo the_title(); ?></option>
            
            <?php 
            endwhile;
            wp_reset_postdata();
            ?>
        </select>

        <!-- seleccionar fecha -->
        <select name="year" id="year">
            <option value="">Seleccionar Año</option>
            <option value="2020" <?php if($year === "2020"){ echo "selected"; }?>>2020</option>
            <option value="2021" <?php if($year === "2021"){ echo "selected"; }?>>2021</option>
            <option value="2022" <?php if($year === "2022"){ echo "selected"; }?>>2022</option>
            <option value="2023" <?php if($year === "2023"){ echo "selected"; }?>>2023</option>
            <option value="2024" <?php if($year === "2024"){ echo "selected"; }?>>2024</option>
        </select>

        <button>BUSCAR</button>

        <?php 
        if($idCurso){
            global $wpdb;
            $tableNameA = $wpdb->prefix . "encuesta_satisfaccion";
            $tableNameB = $wpdb->prefix . "encuesta_satisfaccion_respuestas";

            // a.type = '".$type."'

            if($year && $idCurso){
                $queryRespuestas = $wpdb->get_results( " SELECT * FROM $tableNameA a INNER JOIN $tableNameB b ON a.id = b.id_encuesta WHERE a.id_curso = '".$idCurso."' AND a.date_time LIKE '%".$year."%' " );
                $queryRespuestasId = $wpdb->get_results( " SELECT * FROM $tableNameA a INNER JOIN $tableNameB b ON a.id = b.id_encuesta WHERE a.id_curso = '".$idCurso."' AND a.date_time LIKE '%".$year."%' GROUP BY id_encuesta " );
            } else {
                $queryRespuestas = $wpdb->get_results( " SELECT * FROM $tableNameA a INNER JOIN $tableNameB b ON a.id = b.id_encuesta WHERE a.id_curso = '".$idCurso."' " );
                $queryRespuestasId = $wpdb->get_results( " SELECT * FROM $tableNameA a INNER JOIN $tableNameB b ON a.id = b.id_encuesta WHERE a.id_curso = '".$idCurso."' GROUP BY id_encuesta " );
            }
            
            if(count($queryRespuestas) === 0){
                echo "<div style='background: #000000ad;
                width: 100%;
                height: 100%;
                display: flex;
                position: fixed;
                margin-top: 10px;    align-items: center;
                justify-content: center;'><p style='font-weight: bold;
                color: #FFFFFF;
                font-size: 32px;
                width: 320px;
                text-align: center;'>nadie ha respondido aún, prueba con otro curso por favor.<p></div>";
            }

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
                        
            function getSpecialArr($arr, $key){
                $arrResponse = array();
            
                foreach($arr as $data){
                    $arrResponse[] = $data[$key];
                }
                return $arrResponse;
            }
            
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
                        
            $pregGrouped = array_merge(
                $arr1,
                $arr2,
                $arr3,
                $arr4,
                $arr5
            );
            
            if($step1Questions && $step1Questions && $step1Questions && $step1Questions && $step1Questions){
                $cabPreg = array_merge(
                    $step1Questions,
                    $step2Questions,
                    $step3Questions,
                    $step4Questions,
                    $step5Questions
                );
            } else {
                $cabPreg = [];
            }


        }

        function getResps($arr){
            $resp = [];
            foreach($arr as $req){
                $resp[] = $req["resp"];
            }
            return $resp;
        }

        function getAlgo($respuestasGenero, $key) {
            $frecuencia = array_count_values($respuestasGenero);
            $qts = [];
            foreach ($frecuencia as $freq => $cantidad) {
                // $asd[] = array("name" => $freq, "qty" => $cantidad);
                if($freq === $key){
                    $qts[] = $cantidad;
                }
            }
            return @$qts[0];
        }

        // print_r($arr2);

        $genero = [];
        if(isset($arr1["Género"])){
            $genero = $arr1["Género"];
        }
        $respuestasGenero = getResps($genero);

        $edad = [];
        if(isset($arr1["Edad"])){
            $edad = $arr1["Edad"];
        }
        $respuestasEdad = getResps($edad);

        $region = [];
        if(isset($arr1["Región de residencia"])){
            $region = $arr1["Región de residencia"];
        }
        $respuestasRegion = getResps($region);

        $profesion = [];
        if(isset($arr1["Profesión /ocupación"])){
            $profesion = $arr1["Profesión /ocupación"];
        }
        $respuestasProfesion = getResps($profesion);

        $area = [];
        if(isset($arr1["Area de ocupación"])){
            $area = $arr1["Area de ocupación"];
        }
        $respuestasArea = getResps($area);

        
        ?>

    </form>

    <h1 style="font-size: 19px;color: #116d9e;margin-top: 30px;margin-bottom: 20px;max-width: 800px;line-height: 30px;"><?php if(@$_POST["curso"]){ echo "Curso: ".get_the_title($_POST["curso"])." "; } ?></h1>

    <style>h1{font-size: 14px;color: #f3sd23}</style>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/emn178/chartjs-plugin-labels/src/chartjs-plugin-labels.js"></script>

    <?php 
    if(@$_POST["curso"]){
    ?>

    <div style="padding-right: 70px; margin-bottom: 50px;">

        <h2 style="font-size: 17px; font-weight:bold; color: #5BC2CD">Quienes son los que evaluaron los cursos...</h2>

        <div style="display:flex; justify-content: space-between; ">

            <div>
                <?php 
                require_once dirname( __FILE__ ) . '/parts/chart-01.php'; 
                ?>
            </div>

            <div>
                <?php 
                require_once dirname( __FILE__ ) . '/parts/chart-02.php'; 
                ?>
            </div>

            <div>
                <?php 
                require_once dirname( __FILE__ ) . '/parts/chart-03.php'; 
                ?>
            </div>

        </div>


    </div>

    <div style="padding-right: 70px;">

        <h2 style="font-size: 17px; font-weight:bold; color: #5BC2CD">En qué se especializan...</h2>

        <div style="display:flex; justify-content: space-between; ">        

            <div>
                <?php 
                require_once dirname( __FILE__ ) . '/parts/chart-04.php'; 
                ?>
            </div>

            <div>
                <?php 
                require_once dirname( __FILE__ ) . '/parts/chart-05.php'; 
                ?>
            </div>

        </div>

    </div>

    <div>

        <h2 style="font-size: 17px; font-weight:bold; color: #5BC2CD">Qué contestaron...</h2>

        <div style="background: #ffcbcb;max-width: 1200px;text-align: center;padding: 14px;border: 1px solid orange; margin-bottom:30px;">
            <strong style="width: 100%;display: block;background: orange;padding: 5px 0px;margin-bottom: 10px;">1. El curso presenta una estructura lógica de los contenidos que permite su comprensión</strong>
            <div>
        
            <?php 

            foreach($cabPreg as $res){

                $preg1 = $pregGrouped[$res];
                $respuestasPreg1 = getResps($preg1);
                $preg1Unicos = array_unique($respuestasPreg1);
                $preg1Arr = [];
                foreach($preg1Unicos as $rPreg1){
                    $preg1Arr[] = array("prg" => $rPreg1, "qty" => getAlgo($respuestasPreg1,$rPreg1));
                }

                $sum = 0;
                foreach ($preg1Arr as $key) {
                    $sum += $key["qty"];
                }

                $html = '
                
                <div style="background: #ffcbcb;max-width: 1200px;text-align: center;padding: 14px;border: 1px solid orange; margin-bottom:30px;">
                    <strong style="width: 100%;display: block;background: orange;padding: 5px 0px;margin-bottom: 10px;">'.$res.'</strong>
                    <div>';
                    
                    
                    foreach ($preg1Arr as $key) {

                        
                        if($sum != 0 && $res != "Comentarios y/o sugerencias: Comparte con nosotros sobre tu experiencia en este curso y déjanos sugerencias si las tienes…"){

                            $percentage = ($key["qty"] / $sum) * 100;

                            $html .= '
                        
                            <div style="margin-bottom: 10px;background: #FFFFFF;padding: 2px 0px;display: flex;">
                                <strong style="display: block;width: 50%;">'.$key["prg"].'</strong>
                                <strong style="display: block;width: 50%;">'.round($percentage).'%</strong>
                            </div>
                        
                        ';

                        } else {

                            $html .= '
                        
                            <div style="margin-bottom: 10px;background: #FFFFFF;padding: 2px 0px;display: flex;">
                                <strong style="display: block;width: 100%;">'.$key["prg"].'</strong>
                            </div>
                        
                        ';                            

                        }
                        

        
                    }
                    
                    $html .= '</div>
                </div>
                
                ';

                echo $html;
            }

            
            ?>

        <?php

        ?>

            

            </div>
        </div>


    </div>

    <?php } else {
        echo "Selecciona un curso por favor.";
    } ?>


<?php } ?>