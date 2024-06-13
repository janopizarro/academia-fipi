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

$actual = 0;

$finish = false;

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
            $actual = 1;
        }

        if($unidad_1 == 1 && $unidad_2 == 0 && $unidad_3 == 0 && $unidad_4 == 0){
            $actual = 2;
        }

        if($unidad_1 == 1 && $unidad_2 == 1 && $unidad_3 == 0 && $unidad_4 == 0){
            $actual = 3;
        }

        if($unidad_1 == 1 && $unidad_2 == 1 && $unidad_3 == 1 && $unidad_4 == 0){
            $actual = 4;
        }

        if($unidad_1 == 1 && $unidad_2 == 1 && $unidad_3 == 1 && $unidad_4 == 1){
            $finish = true;
        }

    }

}
// end verificar en que unidad va el alumnno
?>

<style>
.resena_01 strong{
	color: #B0358A !important;
}

.resena_02 strong{
	color: #00AEBC !important;
}

.resena_03 strong{
	color: #eab830 !important;
}

.resena_04 strong{
	color: #b2d017 !important;
}
</style>

<?php if($actual != 0){ ?>
<style>
.resena_{
    display: none;
}
.resena_activa{
    display: block !important;
}
</style>
<?php
}
?>
<div class="dashboard-list-box invoices margin-top-20 description-course">
    <h4>Reseñas del curso <i class="im im-icon-File-HorizontalText" style="position: relative; top: 3px;"></i></h4>
    <ul>
        <li class="resena_ resena_01" id="resena_01" <?php if($actual == 1){ echo "style='display:block'"; }?>>
            <strong>Unidad 01</strong>
            <p><?php echo get_post_meta( get_the_ID(), 'curso_resena_01', true ); ?></p>
        </li>
        
        <li class="resena_ resena_02" id="resena_02" <?php if($actual == 2){ echo "style='display:block'"; }?>>
            <strong>Unidad 02</strong>
            <p><?php echo get_post_meta( get_the_ID(), 'curso_resena_02', true ); ?></p>
        </li>

        <li class="resena_ resena_03" id="resena_03" <?php if($actual == 3){ echo "style='display:block'"; }?>>
            <strong>Unidad 03</strong>
            <p><?php echo get_post_meta( get_the_ID(), 'curso_resena_03', true ); ?></p>
        </li>

        <li class="resena_ resena_04" id="resena_04" <?php if($actual == 4){ echo "style='display:block'"; }?>>
            <strong>Unidad 04</strong>
            <p><?php echo get_post_meta( get_the_ID(), 'curso_resena_04', true ); ?></p>
        </li>

    </ul>
</div>