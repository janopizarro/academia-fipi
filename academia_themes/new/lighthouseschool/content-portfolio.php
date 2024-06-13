<?php
/**
 * The Portfolio template to display the content
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

?><article id="post-<?php the_ID(); ?>" 
	<?php post_class( 'post_item post_layout_portfolio post_layout_portfolio_'.esc_attr($lighthouseschool_columns).' post_format_'.esc_attr($lighthouseschool_post_format).(is_sticky() && !is_paged() ? ' sticky' : '') ); ?>
	<?php echo (!lighthouseschool_is_off($lighthouseschool_animation) ? ' data-animation="'.esc_attr(lighthouseschool_get_animation_classes($lighthouseschool_animation)).'"' : ''); ?>>
	<?php

	// Sticky label
	if ( is_sticky() && !is_paged() ) {
		?><span class="post_label label_sticky"></span><?php
	}

	$lighthouseschool_image_hover = lighthouseschool_get_theme_option('image_hover');
	// Featured image
	lighthouseschool_show_post_featured(array(
		'thumb_size' => lighthouseschool_get_thumb_size(strpos(lighthouseschool_get_theme_option('body_style'), 'full')!==false || $lighthouseschool_columns < 3 ? 'masonry-big' : 'masonry'),
		'show_no_image' => true,
		'class' => $lighthouseschool_image_hover == 'dots' ? 'hover_with_info' : '',
		'post_info' => $lighthouseschool_image_hover == 'dots' ? '<div class="post_info">'.esc_html(get_the_title()).'</div>' : ''
	));
	?>
</article>