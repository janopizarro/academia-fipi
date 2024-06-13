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
					get_sidebar();

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