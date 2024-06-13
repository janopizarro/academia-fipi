<?php
/**
 * The Sticky template to display the sticky posts
 *
 * Used for index/archive
 *
 * @package WordPress
 * @subpackage LIGHTHOUSESCHOOL
 * @since LIGHTHOUSESCHOOL 1.0
 */

$lighthouseschool_columns = max(1, min(3, count(get_option( 'sticky_posts' ))));
$lighthouseschool_post_format = get_post_format();
$lighthouseschool_post_format = empty($lighthouseschool_post_format) ? 'standard' : str_replace('post-format-', '', $lighthouseschool_post_format);
$lighthouseschool_animation = lighthouseschool_get_theme_option('blog_animation');

?><div class="column-1_<?php echo esc_attr($lighthouseschool_columns); ?>"><article id="post-<?php the_ID(); ?>" 
	<?php post_class( 'post_item post_layout_sticky post_format_'.esc_attr($lighthouseschool_post_format) ); ?>
	<?php echo (!lighthouseschool_is_off($lighthouseschool_animation) ? ' data-animation="'.esc_attr(lighthouseschool_get_animation_classes($lighthouseschool_animation)).'"' : ''); ?>
	>

	<?php
	if ( is_sticky() && is_home() && !is_paged() ) {
		?><span class="post_label label_sticky"></span><?php
	}

	// Featured image
	lighthouseschool_show_post_featured(array(
		'thumb_size' => lighthouseschool_get_thumb_size($lighthouseschool_columns==1 ? 'big' : ($lighthouseschool_columns==2 ? 'med' : 'avatar'))
	));

	if ( !in_array($lighthouseschool_post_format, array('link', 'aside', 'status', 'quote')) ) {
		?>
		<div class="post_header entry-header">
			<?php
			// Post title
			the_title( sprintf( '<h6 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h6>' );
			// Post meta
			lighthouseschool_show_post_meta(apply_filters('lighthouseschool_filter_post_meta_args', array(), 'sticky', $lighthouseschool_columns));
			?>
		</div><!-- .entry-header -->
		<?php
	}
	?>
</article></div>