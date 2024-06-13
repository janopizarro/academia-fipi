<?php
/**
 * The Classic template to display the content
 *
 * Used for index/archive/search.
 *
 * @package WordPress
 * @subpackage LIGHTHOUSESCHOOL
 * @since LIGHTHOUSESCHOOL 1.0
 */

$lighthouseschool_blog_style = explode('_', lighthouseschool_get_theme_option('blog_style'));
$lighthouseschool_columns = empty($lighthouseschool_blog_style[1]) ? 2 : max(2, $lighthouseschool_blog_style[1]);
$lighthouseschool_expanded = !lighthouseschool_sidebar_present() && lighthouseschool_is_on(lighthouseschool_get_theme_option('expand_content'));
$lighthouseschool_post_format = get_post_format();
$lighthouseschool_post_format = empty($lighthouseschool_post_format) ? 'standard' : str_replace('post-format-', '', $lighthouseschool_post_format);
$lighthouseschool_animation = lighthouseschool_get_theme_option('blog_animation');
$lighthouseschool_components = lighthouseschool_is_inherit(lighthouseschool_get_theme_option_from_meta('meta_parts')) 
							? 'categories,date,counters'.($lighthouseschool_columns < 3 ? ',edit' : '')
							: lighthouseschool_array_get_keys_by_value(lighthouseschool_get_theme_option('meta_parts'));
$lighthouseschool_counters = lighthouseschool_is_inherit(lighthouseschool_get_theme_option_from_meta('counters')) 
							? 'comments'
							: lighthouseschool_array_get_keys_by_value(lighthouseschool_get_theme_option('counters'));

?><div class="<?php echo  esc_html($lighthouseschool_blog_style[0] == 'classic' ? 'column' : 'masonry_item masonry_item'); ?>-1_<?php echo esc_attr($lighthouseschool_columns); ?>"><article id="post-<?php the_ID(); ?>" 
	<?php post_class( 'post_item post_format_'.esc_attr($lighthouseschool_post_format)
					. ' post_layout_classic post_layout_classic_'.esc_attr($lighthouseschool_columns)
					. ' post_layout_'.esc_attr($lighthouseschool_blog_style[0]) 
					. ' post_layout_'.esc_attr($lighthouseschool_blog_style[0]).'_'.esc_attr($lighthouseschool_columns)
					); ?>
	<?php echo (!lighthouseschool_is_off($lighthouseschool_animation) ? ' data-animation="'.esc_attr(lighthouseschool_get_animation_classes($lighthouseschool_animation)).'"' : ''); ?>>
	<?php

	// Sticky label
	if ( is_sticky() && !is_paged() ) {
		?><span class="post_label label_sticky"></span><?php
	}

	// Featured image
	lighthouseschool_show_post_featured( array( 'thumb_size' => lighthouseschool_get_thumb_size($lighthouseschool_blog_style[0] == 'classic'
													? (strpos(lighthouseschool_get_theme_option('body_style'), 'full')!==false 
															? ( $lighthouseschool_columns > 2 ? 'big' : 'huge' )
															: (	$lighthouseschool_columns > 2
																? ($lighthouseschool_expanded ? 'med' : 'small')
																: ($lighthouseschool_expanded ? 'big' : 'med')
																)
														)
													: (strpos(lighthouseschool_get_theme_option('body_style'), 'full')!==false 
															? ( $lighthouseschool_columns > 2 ? 'masonry-big' : 'full' )
															: (	$lighthouseschool_columns <= 2 && $lighthouseschool_expanded ? 'masonry-big' : 'masonry')
														)
								) ) );

	if ( !in_array($lighthouseschool_post_format, array('link', 'aside', 'status', 'quote')) ) {
		?>
		<div class="post_header entry-header">
			<?php 
			do_action('lighthouseschool_action_before_post_title'); 

			// Post title
			the_title( sprintf( '<h4 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h4>' );

			do_action('lighthouseschool_action_before_post_meta'); 

			// Post meta
			if (!empty($lighthouseschool_components))
				lighthouseschool_show_post_meta(apply_filters('lighthouseschool_filter_post_meta_args', array(
					'components' => $lighthouseschool_components,
					'counters' => $lighthouseschool_counters,
					'seo' => false
					), $lighthouseschool_blog_style[0], $lighthouseschool_columns)
				);

			do_action('lighthouseschool_action_after_post_meta'); 
			?>
		</div><!-- .entry-header -->
		<?php
	}		
	?>

	<div class="post_content entry-content">
		<div class="post_content_inner">
			<?php
			$lighthouseschool_show_learn_more = false;
			if (has_excerpt()) {
				the_excerpt();
			} else if (strpos(get_the_content('!--more'), '!--more')!==false) {
				the_content( '' );
			} else if (in_array($lighthouseschool_post_format, array('link', 'aside', 'status'))) {
				the_content();
			} else if ($lighthouseschool_post_format == 'quote') {
				if (($quote = lighthouseschool_get_tag(get_the_content(), '<blockquote>', '</blockquote>'))!='')
					lighthouseschool_show_layout(wpautop($quote));
				else
					the_excerpt();
			} else if (substr(get_the_content(), 0, 1)!='[') {
				the_excerpt();
			}
			?>
		</div>
		<?php
		// Post meta
		if (in_array($lighthouseschool_post_format, array('link', 'aside', 'status', 'quote'))) {
			if (!empty($lighthouseschool_components))
				lighthouseschool_show_post_meta(apply_filters('lighthouseschool_filter_post_meta_args', array(
					'components' => $lighthouseschool_components,
					'counters' => $lighthouseschool_counters
					), $lighthouseschool_blog_style[0], $lighthouseschool_columns)
				);
		}
		// More button
		if ( $lighthouseschool_show_learn_more ) {
			?><p><a class="more-link" href="<?php the_permalink(); ?>"><?php esc_html_e('Read more', 'lighthouseschool'); ?></a></p><?php
		}
		?>
	</div><!-- .entry-content -->

</article></div>