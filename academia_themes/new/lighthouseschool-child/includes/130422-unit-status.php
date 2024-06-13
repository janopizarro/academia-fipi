<?php 
// verificar en que unidad va el alumnno
$id_user = getDataSession("id");
$id_curso = get_the_ID();

$tableName = $wpdb->prefix . "estado_curso";
$consulta = $wpdb->get_results(" SELECT * FROM $tableName WHERE `id_user` = $id_user AND `id_curso` = $id_curso LIMIT 0,1 "); 

$status = array(
    "unidad_1" => false,
    "unidad_2" => false,
    "unidad_3" => false,
    "unidad_4" => false,
);

$actual = "";

$finish = false;

function getVideoUnitInset($unit){

    // $arr = array("");

    // $urlVideo = str_replace("https://vimeo.com/","",get_post_meta( get_the_ID(), 'curso_resena_0'.$unit.'_video', true )); 

    // $urlVideo_ = explode("/",$urlVideo);
    // if(count($urlVideo_)>0){
    //     $urlVideo_ok = $urlVideo_[0];
    // }

    // $iframe = '<iframe src="https://player.vimeo.com/video/'.$urlVideo.'?api=1&player_id=video&title=0&amp;byline=0&amp;portrait=0&amp;color=c9ff23;autoplay=1" width="640" height="360" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" webkitallowfullscreen mozallowfullscreen allowfullscreen id="video"></iframe>';
    // return "<script>document.getElementById('loadVideo').innerHTML = 'demodemodemodemdomedo'</script>";

    /* obtener el video intro */
    preg_match('/src="([^"]+)"/', get_post_meta( get_the_ID(), 'curso_resena_0'.$unit.'_resena_video_iframe', true), $match);
    $url = $match[1];

    $js = "    
        <script>
        let videoPlayer = document.getElementById('videoPlayer');
        let url_string = '".$url."';
        let adsURL = url_string+'&api=1&player_id=video&title=0&amp;byline=0&amp;portrait=0&amp;color=c9ff23;autoplay=1';
        videoPlayer.src = adsURL;
        </script>
    ";

    return $js;
    /* end obtener el video intro */

}

if(count($consulta) > 0){

    foreach($consulta as $res){

        $idEstado = $res->id;
        $unidad_intro = $res->unidad_intro;
        $unidad_1 = $res->unidad_1;
        $unidad_2 = $res->unidad_2;
        $unidad_3 = $res->unidad_3;
        $unidad_4 = $res->unidad_4;
        $email_admin = $res->email_admin;

        if($unidad_intro == 1 && $unidad_1 == 0 && $unidad_2 == 0 && $unidad_3 == 0 && $unidad_4 == 0){
            $status["unidad_1"] = true;
            $actual = 1;
            // update video unit
            echo getVideoUnitInset(1);
        }

        if($unidad_1 == 1 && $unidad_2 == 0 && $unidad_3 == 0 && $unidad_4 == 0){
            $status["unidad_1"] = true;
            $status["unidad_2"] = true;
            $actual = 2;
            // update video unit
            echo getVideoUnitInset(2);
        }

        if($unidad_1 == 1 && $unidad_2 == 1 && $unidad_3 == 0 && $unidad_4 == 0){
            $status["unidad_1"] = true;
            $status["unidad_2"] = true;
            $status["unidad_3"] = true;
            $actual = 3;
            // update video unit
            echo getVideoUnitInset(3);
        }

        if($unidad_1 == 1 && $unidad_2 == 1 && $unidad_3 == 1 && $unidad_4 == 0){
            $status["unidad_1"] = true;
            $status["unidad_2"] = true;
            $status["unidad_3"] = true;
            $status["unidad_4"] = true;
            $actual = 4;
            // update video unit
            echo getVideoUnitInset(4);
        }

        if($unidad_1 == 1 && $unidad_2 == 1 && $unidad_3 == 1 && $unidad_4 == 1){
            $status["unidad_1"] = true;
            $status["unidad_2"] = true;
            $status["unidad_3"] = true;
            $status["unidad_4"] = true;
            $finish = true;
            $closeAll = '*';

            // preguntar si se ha enviado el email a admin
            if($email_admin == 0){

                /* * notificacion-administrador-curso-finalizado * */
                insertarEnColaCorreo($GLOBALS['tipoCorreos'][4], $id_user, $id_curso, '');

                $introEstado = $wpdb->update($tableName, array('email_admin' => 1),array('id' => $idEstado));

            }

        }

    }

}

// end verificar en que unidad va el alumnno

if($_POST){
    if($_POST["userId"] && $_POST["courseId"]){
        $introEstado = $wpdb->update($tableName, array('unidad_intro' => 1),array('id_user' => $_POST["userId"], 'id_curso' => $_POST["courseId"]));
        if($introEstado){
            echo '
            <script>
                document.location.reload(true)
            </script>            
            ';
        }
    }
}
?>

<script>
/*
 * Verificar limite de alternativas para seleccionar * */

function verificarLimiteAlertnativa(limit, grupoClase) {
    var helperGroup = document.querySelector('.helper_' + grupoClase + '');
    var checkboxgroup = document.querySelectorAll('.' + grupoClase + '');
    helperGroup.style.display = "none";
    for (var i = 0; i < checkboxgroup.length; i++) {
        checkboxgroup[i].onclick = function () {
            var checkedcount = 0;
            for (var i = 0; i < checkboxgroup.length; i++) {
                checkedcount += (checkboxgroup[i].checked) ? 1 : 0;
            }
            if (checkedcount > limit) {
                helperGroup.style.display = "inline-block";
                helperGroup.textContent = `Seleccionaste el máximo (${limit}) de alternativas`;
                this.checked = false;
            } else {
                if (helperGroup.textContent != "") {
                    helperGroup.style.display = "none";
                    helperGroup.textContent = "";
                }
            }
        }
    }
}
</script>

<?php 
$fechaInicio = get_post_meta( get_the_ID(), 'curso_fecha', true );
if($date_now > $date2){ 

    setlocale(LC_TIME, 'es_ES', 'Spanish_Spain', 'Spanish'); 
    $fechaInicioString = str_replace("/","-",$fechaInicio);
    echo "<p style='padding: 15px; width: 100%; background: #e3e489; color: #949403;'>Ya tienes reservado tu cupo, recuerda: El curso comienza el <strong>".strftime('%d %B %Y',strtotime($fechaInicioString))."</strong>, nos vemos! =)</p>";

}
else
{
?>
    <!-- test --> 
    <div id="accordion" class="accordionStyle">

        <div class="card">
            <div class="card-header" id="h_unidad_intro">
                <h5 class="mb-0">
                    <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#unidad_intro" aria-expanded="false" aria-controls="unidad_intro">
                        Intro Curso
                    </button>
                </h5>
            </div>
            <div id="unidad_intro" class="collapse <?php if($unidad_intro == 0){ echo "show"; } ?>" aria-labelledby="h_unidad_intro" data-parent="#accordion">
                <div class="card-body">
                    <?php echo get_post_meta( get_the_ID(), 'curso_intro_desc', true ); ?>

                    <?php if($unidad_intro == 0){ ?>
                        <form action="" method="post">
                            <input type="hidden" name="courseId" value="<?php echo get_the_ID(); ?>">
                            <input type="hidden" name="userId" value="<?php echo getDataSession("id"); ?>">
                            <button class="button finalizar_intro" type="submit" style="font-size: 15px; margin: 25px 0px; background: #b05ad4c2; color: #FFFFFF;">¡Comenzar el curso!</button>
                        </form>
                    <?php } ?>

                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" id="h_unidad_01">
                <h5 class="mb-0">
                    <button class="btn btn-link collapsed unidad_01" <?php if(!$status["unidad_1"]){ echo "disabled"; } ?> data-toggle="collapse" data-target="#unidad_01" aria-expanded="false" aria-controls="unidad_01">
                        Unidad 01
                    </button>
                </h5>
            </div>
            <div id="unidad_01" class="collapse <?php if(isset($closeAll)){ } else { if($actual == 1){ echo "show"; } } ?>" aria-labelledby="h_unidad_01" data-parent="#accordion">
                <div class="card-body">
                    <?php 
                        echo getPreguntasUnidad(1);
                    ?>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" id="h_unidad_02">
                <h5 class="mb-0">
                    <button class="btn btn-link collapsed unidad_02" <?php if(!$status["unidad_2"]){ echo "disabled"; } ?> data-toggle="collapse" data-target="#unidad_02" aria-expanded="false" aria-controls="unidad_02">
                        Unidad 02
                    </button>
                </h5>
            </div>
            <div id="unidad_02" class="collapse <?php if(isset($closeAll)){ } else { if($actual == 2){ echo "show"; } } ?>" aria-labelledby="h_unidad_02" data-parent="#accordion">
                <div class="card-body">
                    <?php 
                        echo getPreguntasUnidad(2);
                    ?>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" id="h_unidad_03">
                <h5 class="mb-0">
                    <button class="btn btn-link collapsed unidad_03" <?php if(!$status["unidad_3"]){ echo "disabled"; } ?> data-toggle="collapse" data-target="#unidad_03" aria-expanded="false" aria-controls="unidad_03">
                        Unidad 03
                    </button>
                </h5>
            </div>
            <div id="unidad_03" class="collapse <?php if(isset($closeAll)){ } else { if($actual == 3){ echo "show"; } } ?>" aria-labelledby="h_unidad_03" data-parent="#accordion">
                <div class="card-body">
                    <?php 
                        echo getPreguntasUnidad(3);
                    ?>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" id="h_unidad_04">
                <h5 class="mb-0">
                    <button class="btn btn-link collapsed unidad_04" <?php if(!$status["unidad_4"]){ echo "disabled"; } ?> data-toggle="collapse" data-target="#unidad_04" aria-expanded="false" aria-controls="unidad_04">
                        Unidad 04
                    </button>
                </h5>
            </div>
            <div id="unidad_04" class="collapse <?php if(isset($closeAll)){ } else { if($actual == 4){ echo "show"; } } ?>" aria-labelledby="h_unidad_04" data-parent="#accordion">
                <div class="card-body">
                    <?php 
                        echo getPreguntasUnidad(4);
                    ?>
                </div>
            </div>
        </div>

        <div class="card" id="f_course">

            <p id="load_finish" style="display:none; font-size: 13px; margin: 10px; color: #3ca4ce; font-family: inherit;"><img src="<?php echo get_template_directory_uri(); ?>/images/loading.svg" style="width: 20px; margin-right: 7px;"/> Actualizando estado...</p>
        
            <?php
            // if($finish){

            //     echo

            //         '<div class="card-header" id="h_unidad_finalizado" style="background-color: #cde2b9;">
            //             <h5 class="mb-0">
            //                 <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#unidad_finalizado" aria-expanded="false" aria-controls="unidad_finalizado" style="color: #5d9019; text-decoration: none;">
            //                     ¡Felicitaciones!
            //                 </button>
            //             </h5>
            //         </div>
            //         <div id="unidad_finalizado" class="collapse show" aria-labelledby="h_unidad_finalizado" data-parent="#accordion">
            //             <div class="card-body">
            //                 '.get_post_meta( get_the_ID(), 'curso_finalizado_msg', true ).'
            //                 <form action="" method="post">
            //                     <input type="hidden" name="token" value="'.getDataSession("id").'">
            //                     <button class="button finalizar_intro" type="submit" style="font-size: 15px; margin: 25px 0px; background: #b05ad4c2; color: #FFFFFF;">Descargar Diploma</button>
            //                 </form>

            //             </div>
            //         </div>';

            // }
            ?>
        
        </div>

    </div>
    <!-- end test --> 

    <script src="<?php echo get_template_directory_uri(); ?>/js/sweetalert2.all.min.js"></script>
    <script src="https://player.vimeo.com/api/player.js"></script>
    <script src="<?php echo get_template_directory_uri(); ?>/js/unidad.js"></script>

<?php 
}
?>