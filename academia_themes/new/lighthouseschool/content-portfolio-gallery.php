<?php
/**
 * The Gallery template to display posts
 *
 * Used for index/archive/search.
 *
 * @package WordPress
 * @subpackage LIGHTHOUSESCHOOL
 * @since LIGHTHOUSESCHOOL 1.0
 */

$lighthouseschool_blog_style = explode('_', lighthouseschool_get_theme_option('blog_style'));
$lighthouseschool_columns = empty($lighthouseschool_blog_style[1]) ? 2 : max(2, $lighthouseschool_blog_style[1]);
$lighthouseschool_post_format = get_post_format();
$lighthouseschool_post_format = empty($lighthouseschool_post_format) ? 'standard' : str_replace('post-format-', '', $lighthouseschool_post_format);
$lighthouseschool_animation = lighthouseschool_get_theme_option('blog_animation');
$lighthouseschool_image = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'full' );

?><article id="post-<?php the_ID(); ?>" 
	<?php post_class( 'post_item post_layout_portfolio post_layout_gallery post_layout_gallery_'.esc_attr($lighthouseschool_columns).' post_format_'.esc_attr($lighthouseschool_post_format) ); ?>
	<?php echo (!lighthouseschool_is_off($lighthouseschool_animation) ? ' data-animation="'.esc_attr(lighthouseschool_get_animation_classes($lighthouseschool_animation)).'"' : ''); ?>
	data-size="<?php if (!empty($lighthouseschool_image[1]) && !empty($lighthouseschool_image[2])) echo intval($lighthouseschool_image[1]) .'x' . intval($lighthouseschool_image[2]); ?>"
	data-src="<?php if (!empty($lighthouseschool_image[0])) echo esc_url($lighthouseschool_image[0]); ?>"
	>

	<?php

	// Sticky label
	if ( is_sticky() && !is_paged() ) {
		?><span class="post_label label_sticky"></span><?php
	}

	// Featured image
	$lighthouseschool_image_hover = 'icon';
	if (in_array($lighthouseschool_image_hover, array('icons', 'zoom'))) $lighthouseschool_image_hover = 'dots';
	$lighthouseschool_components = lighthouseschool_is_inherit(lighthouseschool_get_theme_option_from_meta('meta_parts')) 
								? 'categories,date,counters,share'
								: lighthouseschool_array_get_keys_by_value(lighthouseschool_get_theme_option('meta_parts'));
	$lighthouseschool_counters = lighthouseschool_is_inherit(lighthouseschool_get_theme_option_from_meta('counters')) 
								? 'comments'
								: lighthouseschool_array_get_keys_by_value(lighthouseschool_get_theme_option('counters'));
	lighthouseschool_show_post_featured(array(
		'hover' => $lighthouseschool_image_hover,
		'thumb_size' => lighthouseschool_get_thumb_size( strpos(lighthouseschool_get_theme_option('body_style'), 'full')!==false || $lighthouseschool_columns < 3 ? 'masonry-big' : 'masonry' ),
		'thumb_only' => true,
		'show_no_image' => true,
		'post_info' => '<div class="post_details">'
							. '<h2 class="post_title"><a href="'.esc_url(get_permalink()).'">'. esc_html(get_the_title()) . '</a></h2>'
							. '<div class="post_description">'
								. (!empty($lighthouseschool_components)
										? lighthouseschool_show_post_meta(apply_filters('lighthouseschool_filter_post_meta_args', array(
											'components' => $lighthouseschool_components,
											'counters' => $lighthouseschool_counters,
											'seo' => false,
											'echo' => false
											), $lighthouseschool_blog_style[0], $lighthouseschool_columns))
										: '')
								. '<div class="post_description_content">'
									. apply_filters('the_excerpt', get_the_excerpt())
								. '</div>'
								. '<a href="'.esc_url(get_permalink()).'" class="theme_button post_readmore"><span class="post_readmore_label">' . esc_html__('Learn more', 'lighthouseschool') . '</span></a>'
							. '</div>'
						. '</div>'
	));
	?>
</article>