<?php 
session_start();

get_header();
?>

<!-- Dashboard -->
<div id="dashboard">

	<?php 
    /* * side * */
    require_once dirname( __FILE__ ) . '/includes/side.php';
    ?>

	<div class="dashboard-content">

		<?php 
		if(isset($_SESSION['user_fipi'])) {
		?>
			<!-- Titlebar -->
			<div id="titlebar">
				<div class="row">
					<div class="col-md-12">
						<h2>Hol@, <?php echo getDataSession("nombre"); ?>!</h2>
					</div>
				</div>
			</div>
		<?php 
		}
		?>

		<!-- <h1 style="margin-bottom: 20px;">
			<?php 
			$titulo_bienvenida = nl2br(cmb2_get_option('home_options', 'titulo_bienvenida', true));
			echo $titulo_bienvenida; 
			?>
		</h1>

		<?php 
		$bajada_bienvenida = cmb2_get_option('home_options', 'bajada_bienvenida', true);
		echo wpautop($bajada_bienvenida); 
		?> -->

		<div id="slide" class="slide" style="max-width:470px; margin-top: 65px;">

			<?php 
			$args = array(  
				'posts_per_page' => -1,
				'order' => 'ASC',
				'post_status' => 'publish',
				'post_type' => 'slide'
			);

			$loop = new WP_Query( $args );

			if($loop->post_count > 0){

				while ( $loop->have_posts() ) : $loop->the_post();
				$link = get_post_meta( get_the_ID(), 'link_slide', true );
				?>

					<div>
						<?php if($link != ""){ ?>
							<a href="<?php echo $link; ?>">
						<?php } ?>
						
							<img src="<?php echo get_post_meta( get_the_ID(), 'imagen_slide', true ); ?>" alt="<?php get_the_title(); ?>">
						
						<?php
						if($link != ""){
						?>
						</a>
						<?php
						}
						?>
					</div>

				<?php 
				endwhile;
				wp_reset_postdata();

			} else {

				echo "<small>No se ha encontrado nada...</small>";

			}
			?>

		</div>


<?php 
get_footer();
?>
