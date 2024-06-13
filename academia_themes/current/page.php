<?php 
session_start();
// verificarSesion();

get_header();
?>

<!-- Dashboard -->
<div id="dashboard">

	<!-- Navigation
	================================================== -->

	<!-- Responsive Navigation Trigger -->
	<a href="#" class="dashboard-responsive-nav-trigger"><i class="fa fa-reorder"></i> Dashboard Navigation</a>

	<div class="dashboard-nav">
		<div class="dashboard-nav-inner">

			<img src="<?php echo get_template_directory_uri(); ?>/images/academia-fipi.png" alt="Academia FIPI">
			
		</div>
	</div>
	<!-- Navigation / End -->


	<!-- Content
	================================================== -->
	<div class="dashboard-content">

	<h4 class="title-row"><?php echo the_title(); ?></h4>
		
    <?php if ( have_posts() ) : while ( have_posts() ) : the_post();
    the_content();
    endwhile; else: ?>
    <p>Sorry, no posts matched your criteria.</p>
    <?php endif; ?>

    <?php 
get_footer();
?>
