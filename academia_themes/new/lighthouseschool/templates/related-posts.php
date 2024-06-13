<?php
/**
 * The template 'Style 1' to displaying related posts
 *
 * @package WordPress
 * @subpackage LIGHTHOUSESCHOOL
 * @since LIGHTHOUSESCHOOL 1.0
 */

$lighthouseschool_link = get_permalink();
$lighthouseschool_post_format = get_post_format();
$lighthouseschool_post_format = empty($lighthouseschool_post_format) ? 'standard' : str_replace('post-format-', '', $lighthouseschool_post_format);
?><div id="post-<?php the_ID(); ?>" 
	<?php post_class( 'related_item related_item_style_1 post_format_'.esc_attr($lighthouseschool_post_format) ); ?>><?php
	lighthouseschool_show_post_featured(array(
		'thumb_size' => lighthouseschool_get_thumb_size( (int) lighthouseschool_get_theme_option('related_posts') == 1 ? 'huge' : 'big' ),
		'show_no_image' => false,
		'singular' => false,
		'post_info' => '<div class="post_header entry-header">'
							. '<div class="post_categories">' . lighthouseschool_get_post_categories('') . '</div>'
							. '<h6 class="post_title entry-title"><a href="' . esc_url($lighthouseschool_link) . '">' . wp_kses( get_the_title(), 'lighthouseschool_kses_content' ) . '</a></h6>'
							. (in_array(get_post_type(), array('post', 'attachment'))
									? '<span class="post_date"><a href="' . esc_url($lighthouseschool_link) . '">' . lighthouseschool_get_date() . '</a></span>'
									: '')
						. '</div>'
		)
	);
?></div>