<div class="dashboard-list-box invoices margin-top-20" style="border-top: 10px solid #B0358A;">
    <ul>
        <li>
            <strong>¿Qué vas vamos a ver en este curso?</strong>
            <p><?php echo get_post_meta( get_the_ID(), 'curso_vamos_a_ver', true ); ?></p>
        </li>
        <li>
            <strong>A quien está dirigido</strong> 
            <p><?php echo get_post_meta( get_the_ID(), 'curso_dirigido', true ); ?></p>
        </li>
        <li>
            <strong>Objetivos</strong>
            <p><?php echo get_post_meta( get_the_ID(), 'curso_objetivos', true ); ?></p>
        </li>
        <li>
            <strong>Contenidos</strong>
            <p><?php echo get_post_meta( get_the_ID(), 'curso_contenidos', true ); ?></p>
        </li>
        <li>
            <strong>Cantidad de horas</strong>
            <p><?php echo get_post_meta( get_the_ID(), 'curso_horas', true ); ?></p>
        </li>
        <li>
            <strong>Certifica</strong>
            <p><?php echo get_post_meta( get_the_ID(), 'curso_certifica', true ); ?></p>
        </li>
        <?php 
        if(get_post_meta( get_the_ID(), 'curso_monto', true )){
        ?>

            <li style="background: #fff9e8;">
                <strong>Inversión</strong>
                <p><?php echo formatearPrecio(get_post_meta( get_the_ID(), 'curso_monto', true )); ?></p>
            </li>
        
        <?php 
        }
        ?>
    </ul>
</div>