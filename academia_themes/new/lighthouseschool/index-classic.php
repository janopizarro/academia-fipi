<?php
/**
 * The template for homepage posts with "Classic" style
 *
 * @package WordPress
 * @subpackage LIGHTHOUSESCHOOL
 * @since LIGHTHOUSESCHOOL 1.0
 */

lighthouseschool_storage_set('blog_archive', true);

get_header(); 

if (have_posts()) {

	echo get_query_var('blog_archive_start');

	$lighthouseschool_classes = 'posts_container '
						. (substr(lighthouseschool_get_theme_option('blog_style'), 0, 7) == 'classic' ? 'columns_wrap columns_padding_bottom' : 'masonry_wrap');
	$lighthouseschool_stickies = is_home() ? get_option( 'sticky_posts' ) : false;
	$lighthouseschool_sticky_out = lighthouseschool_get_theme_option('sticky_style')=='columns' 
							&& is_array($lighthouseschool_stickies) && count($lighthouseschool_stickies) > 0 && get_query_var( 'paged' ) < 1;
	if ($lighthouseschool_sticky_out) {
		?><div class="sticky_wrap columns_wrap"><?php	
	}
	if (!$lighthouseschool_sticky_out) {
		if (lighthouseschool_get_theme_option('first_post_large') && !is_paged() && !in_array(lighthouseschool_get_theme_option('body_style'), array('fullwide', 'fullscreen'))) {
			the_post();
			get_template_part( 'content', 'excerpt' );
		}
		
		?><div class="<?php echo esc_attr($lighthouseschool_classes); ?>"><?php
	}
	while ( have_posts() ) { the_post(); 
		if ($lighthouseschool_sticky_out && !is_sticky()) {
			$lighthouseschool_sticky_out = false;
			?></div><div class="<?php echo esc_attr($lighthouseschool_classes); ?>"><?php
		}
		get_template_part( 'content', $lighthouseschool_sticky_out && is_sticky() ? 'sticky' : 'classic' );
	}
	
	?></div><?php

	lighthouseschool_show_pagination();

	echo get_query_var('blog_archive_end');

} else {

	if ( is_search() )
		get_template_part( 'content', 'none-search' );
	else
		get_template_part( 'content', 'none-archive' );

}

get_footer();
?>