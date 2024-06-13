<?php
/**
 * The template to display custom header from the ThemeREX Addons Layouts
 *
 * @package WordPress
 * @subpackage LIGHTHOUSESCHOOL
 * @since LIGHTHOUSESCHOOL 1.0.06
 */

$lighthouseschool_header_css = $lighthouseschool_header_image = '';
$lighthouseschool_header_video = lighthouseschool_get_header_video();
if (true || empty($lighthouseschool_header_video)) {
	$lighthouseschool_header_image = get_header_image();
	if (lighthouseschool_is_on(lighthouseschool_get_theme_option('header_image_override')) && apply_filters('lighthouseschool_filter_allow_override_header_image', true)) {
		if (is_category()) {
			if (($lighthouseschool_cat_img = lighthouseschool_get_category_image()) != '')
				$lighthouseschool_header_image = $lighthouseschool_cat_img;
		} else if (is_singular() || lighthouseschool_storage_isset('blog_archive')) {
			if (has_post_thumbnail()) {
				$lighthouseschool_header_image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
				if (is_array($lighthouseschool_header_image)) $lighthouseschool_header_image = $lighthouseschool_header_image[0];
			} else
				$lighthouseschool_header_image = '';
		}
	}
}
$lighthouseschool_header_id = str_replace('header-custom-', '', lighthouseschool_get_theme_option("header_style"));
if ((int) $lighthouseschool_header_id == 0) {
	$lighthouseschool_header_id = lighthouseschool_get_post_id(array(
												'name' => $lighthouseschool_header_id,
												'post_type' => defined('TRX_ADDONS_CPT_LAYOUT_PT') ? TRX_ADDONS_CPT_LAYOUT_PT : 'cpt_layouts'
												)
											);
} else {
	$lighthouseschool_header_id = apply_filters('lighthouseschool_filter_get_translated_layout', $lighthouseschool_header_id);
}
$lighthouseschool_header_meta = get_post_meta($lighthouseschool_header_id, 'trx_addons_options', true);

?><header class="top_panel top_panel_custom top_panel_custom_<?php echo esc_attr($lighthouseschool_header_id); 
				?> top_panel_custom_<?php echo esc_attr(sanitize_title(get_the_title($lighthouseschool_header_id)));
				echo !empty($lighthouseschool_header_image) || !empty($lighthouseschool_header_video) 
					? ' with_bg_image' 
					: ' without_bg_image';
				if ($lighthouseschool_header_video!='') 
					echo ' with_bg_video';
				if ($lighthouseschool_header_image!='') 
					echo ' '.esc_attr(lighthouseschool_add_inline_css_class('background-image: url('.esc_url($lighthouseschool_header_image).');'));
				if (!empty($lighthouseschool_header_meta['margin']) != '') 
					echo ' '.esc_attr(lighthouseschool_add_inline_css_class('margin-bottom: '.esc_attr(lighthouseschool_prepare_css_value($lighthouseschool_header_meta['margin'])).';'));
				if (is_single() && has_post_thumbnail()) 
					echo ' with_featured_image';
				if (lighthouseschool_is_on(lighthouseschool_get_theme_option('header_fullheight'))) 
					echo ' header_fullheight lighthouseschool-full-height';
				?> scheme_<?php echo esc_attr(lighthouseschool_is_inherit(lighthouseschool_get_theme_option('header_scheme')) 
												? lighthouseschool_get_theme_option('color_scheme') 
												: lighthouseschool_get_theme_option('header_scheme'));
				?>"><?php

	// Background video
	if (!empty($lighthouseschool_header_video)) {
		get_template_part( 'templates/header-video' );
	}
		
	// Custom header's layout
	do_action('lighthouseschool_action_show_layout', $lighthouseschool_header_id);

	// Header widgets area
	get_template_part( 'templates/header-widgets' );
		
?></header>