<div class="dashboard-list-box invoices margin-top-20">
    <ul>
        <li style="padding-left: 30px;">
            <strong>¿Qué vas a ver en este curso?</strong> 
            <?php echo get_post_meta( get_the_ID(), 'curso_vamos_a_ver', true ); ?> 
        </li>
        <li style="padding-left: 30px;">
            <strong>A quien está dirigido</strong>
            <?php echo get_post_meta( get_the_ID(), 'curso_dirigido', true ); ?>
        </li>
    </ul>
</div>