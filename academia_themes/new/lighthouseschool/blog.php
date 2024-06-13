<?php
/**
 * The template to display blog archive
 *
 * @package WordPress
 * @subpackage LIGHTHOUSESCHOOL
 * @since LIGHTHOUSESCHOOL 1.0
 */

/*
Template Name: Blog archive
*/

/**
 * Make page with this template and put it into menu
 * to display posts as blog archive
 * You can setup output parameters (blog style, posts per page, parent category, etc.)
 * in the Theme Options section (under the page content)
 * You can build this page in the WPBakery Page Builder to make custom page layout:
 * just insert %%CONTENT%% in the desired place of content
 */

// Get template page's content
$lighthouseschool_content = '';
$lighthouseschool_blog_archive_mask = '%%CONTENT%%';
$lighthouseschool_blog_archive_subst = sprintf('<div class="blog_archive">%s</div>', $lighthouseschool_blog_archive_mask);
if ( have_posts() ) {
	the_post(); 
	if (($lighthouseschool_content = apply_filters('the_content', get_the_content())) != '') {
		if (($lighthouseschool_pos = strpos($lighthouseschool_content, $lighthouseschool_blog_archive_mask)) !== false) {
			$lighthouseschool_content = preg_replace('/(\<p\>\s*)?'.$lighthouseschool_blog_archive_mask.'(\s*\<\/p\>)/i', $lighthouseschool_blog_archive_subst, $lighthouseschool_content);
		} else
			$lighthouseschool_content .= $lighthouseschool_blog_archive_subst;
		$lighthouseschool_content = explode($lighthouseschool_blog_archive_mask, $lighthouseschool_content);
		// Add VC custom styles to the inline CSS
		$vc_custom_css = get_post_meta( get_the_ID(), '_wpb_shortcodes_custom_css', true );
		if ( !empty( $vc_custom_css ) ) lighthouseschool_add_inline_css(strip_tags($vc_custom_css));
	}
}

// Prepare args for a new query
$lighthouseschool_args = array(
	'post_status' => current_user_can('read_private_pages') && current_user_can('read_private_posts') ? array('publish', 'private') : 'publish'
);
$lighthouseschool_args = lighthouseschool_query_add_posts_and_cats($lighthouseschool_args, '', lighthouseschool_get_theme_option('post_type'), lighthouseschool_get_theme_option('parent_cat'));
$lighthouseschool_page_number = get_query_var('paged') ? get_query_var('paged') : (get_query_var('page') ? get_query_var('page') : 1);
if ($lighthouseschool_page_number > 1) {
	$lighthouseschool_args['paged'] = $lighthouseschool_page_number;
	$lighthouseschool_args['ignore_sticky_posts'] = true;
}
$lighthouseschool_ppp = lighthouseschool_get_theme_option('posts_per_page');
if ((int) $lighthouseschool_ppp != 0)
	$lighthouseschool_args['posts_per_page'] = (int) $lighthouseschool_ppp;
// Make a new query
query_posts( $lighthouseschool_args );
// Set a new query as main WP Query
$GLOBALS['wp_the_query'] = $GLOBALS['wp_query'];

// Set query vars in the new query!
if (is_array($lighthouseschool_content) && count($lighthouseschool_content) == 2) {
	set_query_var('blog_archive_start', $lighthouseschool_content[0]);
	set_query_var('blog_archive_end', $lighthouseschool_content[1]);
}

get_template_part('index');
?>