<div class="ampidea-chat">
	<a class="enlace-whatsapp" rel="nofollow noopener noreferrer" target="_blank" href="https://wa.me/56954214774?text=Hol@%20tengo%20algunas%20dudas%20acerca%20de%20FIPI%20muchas%20gracias">
		<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/ampidea-chat.png" alt="">

		<div class="bubble-wrap">
			<div class="bubble left">
			<strong>¡Hola! Soy Ampidea</strong><br />¿En qué te puedo ayudar?
			</div>
		</div>
	</a>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/simple-slider/1.0.0/simpleslider.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
  // Espera 1000 milisegundos (1 segundo) antes de agregar la clase 'fade-in' a '.bubble-wrap'
  setTimeout(function() {
    var bubbles = document.querySelectorAll('.bubble-wrap');
    bubbles.forEach(function(bubble) {
      bubble.classList.add('fade-in');
    });
  }, 4000);

  simpleslider.getSlider({
    container: document.querySelector('.sc_testimonials_columns_wrap'),
    transitionTime:6,
    delay:7.5
  });

});
</script>

<style>
@include keyframes(scaleIn) {
  from {
    @include transform(scale(0));
  }
  to {
    @include transform(scale(1));
  }
}

.bubble-wrap.fade-in {
  @include animation(scaleIn 0.4s ease-in-out);
  @include transform-origin(0 50% 0);
}

.bubble-wrap {
  width: 244px;
  margin: 20px auto; 
}

.bubble {
	position: relative;
	padding: 20px;
	background: #FFFFFF;
	border-radius: 10px;
	border: #d5d5d5 solid 1px;
	font-size: 1em;
	line-height: 1.25em;
	color: #2A649D
}

.bubble.left:after {
  content: '';
  position: absolute;
  border-style: solid;
  border-width: 16px 16px 16px 0;
  border-color: transparent #FFFFFF;
  display: block;
  width: 0;
  z-index: 1;
  left: -15px;
  top: 50%;
  margin-top: -16px
  
}

.bubble.left:before {
  content: '';
  position: absolute;
  border-style: solid;
  border-width: 16px 16px 16px 0;
  border-color: transparent #d5d5d5;
  display: block;
  width: 0;
  z-index: 0;
  left: -16px;
  top: 50%;
  margin-top: -16px;
}
</style>

<?php
/**
 * The Footer: widgets area, logo, footer menu and socials
 *
 * @package WordPress
 * @subpackage LIGHTHOUSESCHOOL
 * @since LIGHTHOUSESCHOOL 1.0
 */

						// Widgets area inside page content
						lighthouseschool_create_widgets_area('widgets_below_content');
						?>				
					</div><!-- </.content> -->

					<?php
					// Show main sidebar
					// get_sidebar();

					// Widgets area below page content
					lighthouseschool_create_widgets_area('widgets_below_page');

					$lighthouseschool_body_style = lighthouseschool_get_theme_option('body_style');
					if ($lighthouseschool_body_style != 'fullscreen') {
						?></div><!-- </.content_wrap> --><?php
					}
					?>

			</div><!-- </.page_content_wrap> -->

			<?php
			// Footer
			$lighthouseschool_footer_style = lighthouseschool_get_theme_option("footer_style");
			if (strpos($lighthouseschool_footer_style, 'footer-custom-')===0)
				$lighthouseschool_footer_style = lighthouseschool_is_layouts_available() ? 'footer-custom' : 'footer-default';
			get_template_part( "templates/{$lighthouseschool_footer_style}");
			?>

		</div><!-- /.page_wrap -->

	</div><!-- /.body_wrap -->

	<?php if (lighthouseschool_is_on(lighthouseschool_get_theme_option('debug_mode')) && lighthouseschool_get_file_dir('images/makeup.jpg')!='') { ?>
		<img src="<?php echo esc_url(lighthouseschool_get_file_url('images/makeup.jpg')); ?>" id="makeup">
	<?php } ?>


	<?php wp_footer(); ?>

</body>
</html>