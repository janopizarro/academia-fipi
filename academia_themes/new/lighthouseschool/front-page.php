<?php
/**
 * The Front Page template file.
 *
 * @package WordPress
 * @subpackage LIGHTHOUSESCHOOL
 * @since LIGHTHOUSESCHOOL 1.0.31
 */

get_header();

// If front-page is a static page
if (get_option('show_on_front') == 'page') {

	// If Front Page Builder is enabled - display sections
	if (lighthouseschool_is_on(lighthouseschool_get_theme_option('front_page_enabled'))) {

		if ( have_posts() ) the_post();

		$lighthouseschool_sections = lighthouseschool_array_get_keys_by_value(lighthouseschool_get_theme_option('front_page_sections'), 1, false);
		if (is_array($lighthouseschool_sections)) {
			foreach ($lighthouseschool_sections as $lighthouseschool_section) {
				get_template_part("front-page/section", $lighthouseschool_section);
			}
		}
	
	// Else - display native page content
	} else
		get_template_part('page');

// Else get index template to show posts
} else
	get_template_part('index');

get_footer();
?>