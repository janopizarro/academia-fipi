<?php
/**
 * The template to display posts in widgets and/or in the search results
 *
 * @package WordPress
 * @subpackage LIGHTHOUSESCHOOL
 * @since LIGHTHOUSESCHOOL 1.0
 */

$lighthouseschool_post_id    = get_the_ID();
$lighthouseschool_post_date  = get_the_date('d/m, l');
$lighthouseschool_post_title = get_the_title();
$lighthouseschool_post_link  = get_permalink();
$lighthouseschool_post_author_id   = get_the_author_meta('ID');
$lighthouseschool_post_author_name = get_the_author_meta('display_name');
$lighthouseschool_post_author_url  = get_author_posts_url($lighthouseschool_post_author_id, '');

$lighthouseschool_args = get_query_var('lighthouseschool_args_widgets_posts');
$lighthouseschool_show_date = isset($lighthouseschool_args['show_date']) ? (int) $lighthouseschool_args['show_date'] : 1;
$lighthouseschool_show_image = isset($lighthouseschool_args['show_image']) ? (int) $lighthouseschool_args['show_image'] : 1;
$lighthouseschool_show_author = isset($lighthouseschool_args['show_author']) ? (int) $lighthouseschool_args['show_author'] : 1;
$lighthouseschool_show_counters = isset($lighthouseschool_args['show_counters']) ? (int) $lighthouseschool_args['show_counters'] : 1;
$lighthouseschool_show_categories = isset($lighthouseschool_args['show_categories']) ? (int) $lighthouseschool_args['show_categories'] : 1;

$lighthouseschool_output = lighthouseschool_storage_get('lighthouseschool_output_widgets_posts');

$lighthouseschool_post_counters_output = '';
if ( $lighthouseschool_show_counters ) {
	$lighthouseschool_post_counters_output = '<span class="post_info_item post_info_counters">'
								. lighthouseschool_get_post_counters('comments')
							. '</span>';
}


$lighthouseschool_output .= '<article class="post_item with_thumb">';

if ($lighthouseschool_show_image) {
	$lighthouseschool_post_thumb = get_the_post_thumbnail($lighthouseschool_post_id, lighthouseschool_get_thumb_size('tiny'), array(
		'alt' => the_title_attribute( array( 'echo' => false ) )
	));
	if ($lighthouseschool_post_thumb) $lighthouseschool_output .= '<div class="post_thumb">' . ($lighthouseschool_post_link ? '<a href="' . esc_url($lighthouseschool_post_link) . '">' : '') . ($lighthouseschool_post_thumb) . ($lighthouseschool_post_link ? '</a>' : '') . '</div>';
}

$lighthouseschool_output .= '<div class="post_content">'
			. ($lighthouseschool_show_categories 
					? '<div class="post_categories">'
						. lighthouseschool_get_post_categories()
						. $lighthouseschool_post_counters_output
						. '</div>' 
					: '')
			. '<h6 class="post_title">' . ($lighthouseschool_post_link ? '<a href="' . esc_url($lighthouseschool_post_link) . '">' : '') . ($lighthouseschool_post_title) . ($lighthouseschool_post_link ? '</a>' : '') . '</h6>'
			. apply_filters('lighthouseschool_filter_get_post_info', 
								'<div class="post_info">'
									. ($lighthouseschool_show_date 
										? '<span class="post_info_item post_info_posted">'
											. ($lighthouseschool_post_link ? '<a href="' . esc_url($lighthouseschool_post_link) . '" class="post_info_date">' : '') 
											. esc_html($lighthouseschool_post_date) 
											. ($lighthouseschool_post_link ? '</a>' : '')
											. '</span>'
										: '')
									. ($lighthouseschool_show_author 
										? '<span class="post_info_item post_info_posted_by">' 
											. esc_html__('by', 'lighthouseschool') . ' ' 
											. ($lighthouseschool_post_link ? '<a href="' . esc_url($lighthouseschool_post_author_url) . '" class="post_info_author">' : '') 
											. esc_html($lighthouseschool_post_author_name) 
											. ($lighthouseschool_post_link ? '</a>' : '') 
											. '</span>'
										: '')
									. (!$lighthouseschool_show_categories && $lighthouseschool_post_counters_output
										? $lighthouseschool_post_counters_output
										: '')
								. '</div>')
		. '</div>'
	. '</article>';
lighthouseschool_storage_set('lighthouseschool_output_widgets_posts', $lighthouseschool_output);
?>