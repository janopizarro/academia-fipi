<?php
/**
 * The default template to display the content
 *
 * Used for index/archive/search.
 *
 * @package WordPress
 * @subpackage LIGHTHOUSESCHOOL
 * @since LIGHTHOUSESCHOOL 1.0
 */

$lighthouseschool_post_format = get_post_format();
$lighthouseschool_post_format = empty($lighthouseschool_post_format) ? 'standard' : str_replace('post-format-', '', $lighthouseschool_post_format);
$lighthouseschool_animation = lighthouseschool_get_theme_option('blog_animation');

?><article id="post-<?php the_ID(); ?>" 
	<?php post_class( 'post_item post_layout_excerpt post_format_'.esc_attr($lighthouseschool_post_format) ); ?>
	<?php echo (!lighthouseschool_is_off($lighthouseschool_animation) ? ' data-animation="'.esc_attr(lighthouseschool_get_animation_classes($lighthouseschool_animation)).'"' : ''); ?>
	><?php

	// Sticky label
	if ( is_sticky() && !is_paged() ) {
		?><span class="post_label label_sticky"></span><?php
	}

	// Featured image
	lighthouseschool_show_post_featured(array( 'thumb_size' => lighthouseschool_get_thumb_size( strpos(lighthouseschool_get_theme_option('body_style'), 'full')!==false ? 'full' : 'big' ) ));

	// Title and post meta
	if (get_the_title() != '') {
		?>
		<div class="post_header entry-header">
			<?php

            // Post date
            $dt = apply_filters('lighthouseschool_filter_get_post_date', lighthouseschool_get_date());
            if (!empty($dt)) {
                ?>
                <span class="post_meta_item post_date"><a href="<?php the_permalink(); ?>"><?php echo esc_html($dt); ?></a></span>
                <?php
            }

			do_action('lighthouseschool_action_before_post_title'); 

			// Post title
			the_title( sprintf( '<h6 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h6>' );

			do_action('lighthouseschool_action_before_post_meta'); 

			// Post meta
			$lighthouseschool_components = lighthouseschool_is_inherit(lighthouseschool_get_theme_option_from_meta('meta_parts')) 
										? 'categories,counters,author'
										: lighthouseschool_array_get_keys_by_value(lighthouseschool_get_theme_option('meta_parts'));
			$lighthouseschool_counters = lighthouseschool_is_inherit(lighthouseschool_get_theme_option_from_meta('counters')) 
										? 'comments'
										: lighthouseschool_array_get_keys_by_value(lighthouseschool_get_theme_option('counters'));

			if (!empty($lighthouseschool_components))
				lighthouseschool_show_post_meta(apply_filters('lighthouseschool_filter_post_meta_args', array(
					'components' => $lighthouseschool_components,
					'counters' => $lighthouseschool_counters,
					'seo' => false
					), 'excerpt', 1)
				);
			?>
		</div><!-- .post_header --><?php
	}
	
	// Post content
	?><div class="post_content entry-content"><?php
		if (lighthouseschool_get_theme_option('blog_content') == 'fullpost') {
			// Post content area
			?><div class="post_content_inner"><?php
				the_content( '' );
			?></div><?php
			// Inner pages
			wp_link_pages( array(
				'before'      => '<div class="page_links"><span class="page_links_title">' . esc_html__( 'Pages:', 'lighthouseschool' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
				'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'lighthouseschool' ) . ' </span>%',
				'separator'   => '<span class="screen-reader-text">, </span>',
			) );

		} else {

			$lighthouseschool_show_learn_more = !in_array($lighthouseschool_post_format, array('link', 'aside', 'status', 'quote'));

			// Post content area
			?><div class="post_content_inner"><?php
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
			?></div><?php
			// More button
			if ( $lighthouseschool_show_learn_more ) {
				?><p><a class="more-link" href="<?php the_permalink(); ?>"><?php esc_html_e('Read more', 'lighthouseschool'); ?></a></p><?php
			}

		}
	?></div><!-- .entry-content -->
</article>