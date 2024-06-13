<div class="dashboard-list-box invoices margin-top-20 description-course" style="    margin-bottom: 35px;">
    <ul>
        <li>
            <strong>¿Qué vas a ver en este curso?</strong> 
            <?php echo get_post_meta( get_the_ID(), 'curso_vamos_a_ver', true ); ?> 
        </li>
        <li>
            <strong>A quien está dirigido</strong>
            <?php echo get_post_meta( get_the_ID(), 'curso_dirigido', true ); ?>
        </li>
    </ul>
</div>