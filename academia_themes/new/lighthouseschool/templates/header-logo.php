<?php
/**
 * The template to display the logo or the site name and the slogan in the Header
 *
 * @package WordPress
 * @subpackage LIGHTHOUSESCHOOL
 * @since LIGHTHOUSESCHOOL 1.0
 */

$lighthouseschool_args = get_query_var('lighthouseschool_logo_args');

// Site logo
$lighthouseschool_logo_image  = lighthouseschool_get_logo_image(isset($lighthouseschool_args['type']) ? $lighthouseschool_args['type'] : '');
$lighthouseschool_logo_text   = lighthouseschool_is_on(lighthouseschool_get_theme_option('logo_text')) ? get_bloginfo( 'name' ) : '';
$lighthouseschool_logo_slogan = get_bloginfo( 'description', 'display' );
if (!empty($lighthouseschool_logo_image) || !empty($lighthouseschool_logo_text)) {
	?><a class="sc_layouts_logo" href="<?php echo esc_url(home_url('/')); ?>"><?php
		if (!empty($lighthouseschool_logo_image)) {
			$lighthouseschool_attr = lighthouseschool_getimagesize($lighthouseschool_logo_image);
			echo '<img src="'.esc_url($lighthouseschool_logo_image).'" '.(!empty($lighthouseschool_attr[3]) ? sprintf(' %s', $lighthouseschool_attr[3]) : '').'>';
		} else {
			lighthouseschool_show_layout(lighthouseschool_prepare_macros($lighthouseschool_logo_text), '<span class="logo_text">', '</span>');
			lighthouseschool_show_layout(lighthouseschool_prepare_macros($lighthouseschool_logo_slogan), '<span class="logo_slogan">', '</span>');
		}
	?></a><?php
}
?>