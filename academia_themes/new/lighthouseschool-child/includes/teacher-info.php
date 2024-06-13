<?php 
$idDocente = get_post_meta( get_the_ID(), 'curso_docente', true );
$idDocenteDos = get_post_meta( get_the_ID(), 'curso_docente_segundo', true );
?>

<div class="dashboard-list-box invoices margin-top-20 teacher-info">
    <h4><?php if ($idDocenteDos){ echo "Docentes"; } else { echo "Docente"; } ?> <i class="im im-icon-File-HorizontalText" style="position: relative; top: 3px;"></i></h4>

    <div class="bloque-docente">

        <img src="<?php echo get_post_meta( $idDocente, 'curso_docente_fotografia', true ); ?>" alt="">

        <div class="bloque-docente__txt">
            <h5><?php echo get_the_title($idDocente); ?></h5>
            <p><?php echo get_post_meta( $idDocente, 'curso_docente_resena', true ); ?></p>
        </div>

    </div>

    <?php if($idDocenteDos){?>
        <div class="bloque-docente">

            <img src="<?php echo get_post_meta( $idDocenteDos, 'curso_docente_fotografia', true ); ?>" alt="">

            <div class="bloque-docente__txt">
                <h5><?php echo get_the_title($idDocenteDos); ?></h5>
                <p><?php echo get_post_meta( $idDocenteDos, 'curso_docente_resena', true ); ?></p>
            </div>

        </div>
    <?php } ?>

</div>