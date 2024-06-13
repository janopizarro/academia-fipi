<?php 
$idDocente = get_post_meta( get_the_ID(), 'curso_docente', true );
$idDocenteDos = get_post_meta( get_the_ID(), 'curso_docente_segundo', true );
?>

<div class="dashboard-list-box invoices">

    <div class="bloque-docente--new">

        <img src="<?php echo get_post_meta( $idDocente, 'curso_docente_fotografia', true ); ?>" alt="">

        <div class="bloque-docente__txt">
            <h5><?php echo get_the_title($idDocente); ?></h5>
            <small>Docente</small>
        </div>

    </div>

    <?php if($idDocenteDos){?>
        <div class="bloque-docente--new">

            <img src="<?php echo get_post_meta( $idDocenteDos, 'curso_docente_fotografia', true ); ?>" alt="">

            <div class="bloque-docente__txt">
                <h5><?php echo get_the_title($idDocenteDos); ?></h5>
            </div>

        </div>
    <?php } ?>

</div>