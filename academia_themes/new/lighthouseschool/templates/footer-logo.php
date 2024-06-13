<?php
/**
 * The template to display the site logo in the footer
 *
 * @package WordPress
 * @subpackage LIGHTHOUSESCHOOL
 * @since LIGHTHOUSESCHOOL 1.0.10
 */

// Logo
if (lighthouseschool_is_on(lighthouseschool_get_theme_option('logo_in_footer'))) {
	$lighthouseschool_logo_image = '';
	if (lighthouseschool_is_on(lighthouseschool_get_theme_option('logo_retina_enabled')) && lighthouseschool_get_retina_multiplier(2) > 1)
		$lighthouseschool_logo_image = lighthouseschool_get_theme_option( 'logo_footer_retina' );
	if (empty($lighthouseschool_logo_image)) 
		$lighthouseschool_logo_image = lighthouseschool_get_theme_option( 'logo_footer' );
	$lighthouseschool_logo_text   = get_bloginfo( 'name' );
	if (!empty($lighthouseschool_logo_image) || !empty($lighthouseschool_logo_text)) {
		?>
		<div class="footer_logo_wrap">
			<div class="footer_logo_inner">
				<?php
				if (!empty($lighthouseschool_logo_image)) {
					$lighthouseschool_attr = lighthouseschool_getimagesize($lighthouseschool_logo_image);
					echo '<a href="'.esc_url(home_url('/')).'"><img src="'.esc_url($lighthouseschool_logo_image).'" class="logo_footer_image" '.(!empty($lighthouseschool_attr[3]) ? sprintf(' %s', $lighthouseschool_attr[3]) : '').'></a>' ;
				} else if (!empty($lighthouseschool_logo_text)) {
					echo '<h1 class="logo_footer_text"><a href="'.esc_url(home_url('/')).'">' . esc_html($lighthouseschool_logo_text) . '</a></h1>';
				}
				?>
			</div>
		</div>
		<?php
	}
}
?>