<?php
// DETECTAR ENVIO DE FORMA PARA INICIAR EL CURSO
if($_POST){

    if($_POST["userId"] && $_POST["courseId"]){

        $tableName = $wpdb->prefix . "unidades_dinamico";
        $introEstado = $wpdb->update($tableName, array('status' => 1),array('id_user' => $_POST["userId"], 'id_curso' => $_POST["courseId"], 'tipo' => 1, 'status' => 0));

        if($introEstado){

            echo '
                <script>
                console.log("si...");
                location.reload();
                </script>            
            ';

        }

    }

}

// OBTENER ESTADO DE INTRO
if(count($queryIntroCourse) == 0){ 

    $unitIntroShow = "show"; 
    $unitIntroDisabled = "";

} else {

    $unitIntroShow = "";
    $unitIntroDisabled = "disabled";

}

$html .= '<div id="accordion" class="accordionStyle">
<div class="card">
    <!--<div class="card-header" id="h_unidad_intro">
        <h5 class="mb-0">
            <button class="btn btn-link collapsed" '.$unitIntroDisabled.' data-toggle="collapse" data-target="#unidad_intro" aria-expanded="false" aria-controls="unidad_intro">
                Intro Curso
            </button>
        </h5>
    </div>-->
    <div id="unidad_intro" class="collapse '.$unitIntroShow.'" aria-labelledby="h_unidad_intro" data-parent="#accordion">
        <div class="card-body">
            '.get_post_meta( get_the_ID(), 'curso_intro_desc', true ).'';
            
if(count($queryIntroCourse) == 0){ 

    $html .= '

            <form action="" method="post">
                <input type="hidden" name="courseId" value="'.get_the_ID().'">
                <input type="hidden" name="userId" value="'.getDataSession("id").'">
                <button class="button finalizar_intro" type="submit" style="font-size: 15px;margin: 5px 0px;background: #BF2871;color: #FFFFFF;border-radius: 45px;">¡Comenzar el curso!</button>
            </form>';
}

    $html .= '    
        </div>
    </div>
</div>
</div>';
?>