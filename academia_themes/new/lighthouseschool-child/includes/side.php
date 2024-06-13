<a href="#" class="dashboard-responsive-nav-trigger"><i class="fa fa-reorder"></i> Dashboard Navigation</a>

<div class="dashboard-nav">
    <div class="dashboard-nav-inner">

        <img src="<?php echo get_template_directory_uri(); ?>/images/academia-fipi.png" alt="Academia FIPI">

        <?php 
        wp_nav_menu(array( 
            'menu' => 'menu_lateral',
            'theme_location' => 'menu_lateral',
            'container_class' => 'responsive__menu',
        )); 
        ?>
        
    </div>
</div>