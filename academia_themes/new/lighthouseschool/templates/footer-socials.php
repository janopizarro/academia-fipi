<?php
/**
 * The template to display the socials in the footer
 *
 * @package WordPress
 * @subpackage LIGHTHOUSESCHOOL
 * @since LIGHTHOUSESCHOOL 1.0.10
 */


// Socials
if ( lighthouseschool_is_on(lighthouseschool_get_theme_option('socials_in_footer')) && ($lighthouseschool_output = lighthouseschool_get_socials_links()) != '') {
	?>
	<div class="footer_socials_wrap socials_wrap">
		<div class="footer_socials_inner">
			<?php lighthouseschool_show_layout($lighthouseschool_output); ?>
		</div>
	</div>
	<?php
}
?>