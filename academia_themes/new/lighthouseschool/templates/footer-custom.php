<?php
/**
 * The template to display default site footer
 *
 * @package WordPress
 * @subpackage LIGHTHOUSESCHOOL
 * @since LIGHTHOUSESCHOOL 1.0.10
 */

$lighthouseschool_footer_scheme =  lighthouseschool_is_inherit(lighthouseschool_get_theme_option('footer_scheme')) ? lighthouseschool_get_theme_option('color_scheme') : lighthouseschool_get_theme_option('footer_scheme');
$lighthouseschool_footer_id = str_replace('footer-custom-', '', lighthouseschool_get_theme_option("footer_style"));
if ((int) $lighthouseschool_footer_id == 0) {
	$lighthouseschool_footer_id = lighthouseschool_get_post_id(array(
												'name' => $lighthouseschool_footer_id,
												'post_type' => defined('TRX_ADDONS_CPT_LAYOUT_PT') ? TRX_ADDONS_CPT_LAYOUT_PT : 'cpt_layouts'
												)
											);
} else {
	$lighthouseschool_footer_id = apply_filters('lighthouseschool_filter_get_translated_layout', $lighthouseschool_footer_id);
}
$lighthouseschool_footer_meta = get_post_meta($lighthouseschool_footer_id, 'trx_addons_options', true);
?>
<footer class="footer_wrap footer_custom footer_custom_<?php echo esc_attr($lighthouseschool_footer_id); 
						?> footer_custom_<?php echo esc_attr(sanitize_title(get_the_title($lighthouseschool_footer_id))); 
						if (!empty($lighthouseschool_footer_meta['margin']) != '') 
							echo ' '.esc_attr(lighthouseschool_add_inline_css_class('margin-top: '.esc_attr(lighthouseschool_prepare_css_value($lighthouseschool_footer_meta['margin'])).';'));
						?> scheme_<?php echo esc_attr($lighthouseschool_footer_scheme); 
						?>">
	<?php
    // Custom footer's layout
    do_action('lighthouseschool_action_show_layout', $lighthouseschool_footer_id);
	?>
</footer><!-- /.footer_wrap -->
