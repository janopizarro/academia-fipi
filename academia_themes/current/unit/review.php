<?php 
// VERIFICAR EN QUE UNIDAD VA EL ALUMNNO
$id_user = getDataSession("id");
$id_curso = get_the_ID();

$tableName = $wpdb->prefix . "unidades_dinamico";
$queryStatusUnits = $wpdb->get_results(" SELECT * FROM $tableName WHERE `id_user` = $id_user AND `id_curso` = $id_curso AND `tipo` = 1 AND `status` = 1 OR `id_user` = $id_user AND `id_curso` = $id_curso AND `tipo` = 2 "); 

$unitActual = 0;

if(count($queryStatusUnits) > 0){

    $unitActual = count($queryStatusUnits);

} else {

    $unitActual = 0;

}
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

<div class="dashboard-list-box invoices margin-top-20" style="border-top: 10px solid #B0358A;">
    <h4>Reseñas del curso <i class="im im-icon-File-HorizontalText" style="position: relative; top: 3px;"></i></h4>
    <ul>

        <?php 
        if(haveActivityInCourse( $id_user, $id_curso ) && !haveFinishedCourse( $id_user, $id_curso )){

            // OBTENER LAS UNIDADES DEL CURSO
            $units = getUnitsCourse($id_curso);

            $x = 0;
            foreach($units as $res){ $x++;
    
                $html = '
                    
                <li class="resena_ resena_0'.$x.'" id="resena_0'.$x.'">
                    <strong>Unidad 0'.$x.'</strong>
                    <p>'.get_post_meta( $res->ID, 'unidad_resena', true ).'</p>
                </li>
            
                ';

                if($unitActual == 0){
                    echo $html;    
                } elseif($unitActual == $x){
                    echo $html;
                } else {}
    
            }

        } elseif (haveFinishedCourse( $id_user, $id_curso )) {

            // OBTENER LAS UNIDADES DEL CURSO
            $units = getUnitsCourse($id_curso);

            $x = 0;
            foreach($units as $res){ $x++;
    
                $html = '
                    
                <li class="resena_ resena_0'.$x.'" id="resena_0'.$x.'">
                    <strong>Unidad 0'.$x.'</strong>
                    <p>'.get_post_meta( $res->ID, 'unidad_resena', true ).'</p>
                </li>
            
                ';
    
                echo $html;    
            
            }

        } else {

            // OBTENER LAS UNIDADES DEL CURSO
            $units = getUnitsCourse($id_curso);

            $x = 0;
            foreach($units as $res){ $x++;
    
                $html = '
                    
                <li class="resena_ resena_0'.$x.'" id="resena_0'.$x.'">
                    <strong>Unidad 0'.$x.'</strong>
                    <p>'.get_post_meta( $res->ID, 'unidad_resena', true ).'</p>
                </li>
            
                ';
    
                echo $html;    
    
            }

        }
        ?>


        
    </ul>
</div>