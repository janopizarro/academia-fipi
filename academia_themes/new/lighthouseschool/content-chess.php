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
$lighthouseschool_columns = empty($lighthouseschool_blog_style[1]) ? 1 : max(1, $lighthouseschool_blog_style[1]);
$lighthouseschool_expanded = !lighthouseschool_sidebar_present() && lighthouseschool_is_on(lighthouseschool_get_theme_option('expand_content'));
$lighthouseschool_post_format = get_post_format();
$lighthouseschool_post_format = empty($lighthouseschool_post_format) ? 'standard' : str_replace('post-format-', '', $lighthouseschool_post_format);
$lighthouseschool_animation = lighthouseschool_get_theme_option('blog_animation');

?><article id="post-<?php the_ID(); ?>" 
	<?php post_class( 'post_item post_layout_chess post_layout_chess_'.esc_attr($lighthouseschool_columns).' post_format_'.esc_attr($lighthouseschool_post_format) ); ?>
	<?php echo (!lighthouseschool_is_off($lighthouseschool_animation) ? ' data-animation="'.esc_attr(lighthouseschool_get_animation_classes($lighthouseschool_animation)).'"' : ''); ?>>

	<?php
	// Add anchor
	if ($lighthouseschool_columns == 1 && shortcode_exists('trx_sc_anchor')) {
		echo do_shortcode('[trx_sc_anchor id="post_'.esc_attr(get_the_ID()).'" title="'.the_title_attribute( array( 'echo' => false ) ).'"]');
	}

	// Sticky label
	if ( is_sticky() && !is_paged() ) {
		?><span class="post_label label_sticky"></span><?php
	}

	// Featured image
	lighthouseschool_show_post_featured( array(
											'class' => $lighthouseschool_columns == 1 ? 'lighthouseschool-full-height' : '',
											'show_no_image' => true,
											'thumb_bg' => true,
											'thumb_size' => lighthouseschool_get_thumb_size(
																	strpos(lighthouseschool_get_theme_option('body_style'), 'full')!==false
																		? ( $lighthouseschool_columns > 1 ? 'huge' : 'original' )
																		: (	$lighthouseschool_columns > 2 ? 'big' : 'huge')
																	)
											) 
										);

	?><div class="post_inner"><div class="post_inner_content"><?php 

		?><div class="post_header entry-header"><?php 
			do_action('lighthouseschool_action_before_post_title'); 

			// Post title
			the_title( sprintf( '<h4 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h4>' );
			
			do_action('lighthouseschool_action_before_post_meta'); 

			// Post meta
			$lighthouseschool_components = lighthouseschool_is_inherit(lighthouseschool_get_theme_option_from_meta('meta_parts')) 
										? 'categories,date'.($lighthouseschool_columns < 3 ? ',counters' : '').($lighthouseschool_columns == 1 ? ',edit' : '')
										: lighthouseschool_array_get_keys_by_value(lighthouseschool_get_theme_option('meta_parts'));
			$lighthouseschool_counters = lighthouseschool_is_inherit(lighthouseschool_get_theme_option_from_meta('counters')) 
										? 'comments'
										: lighthouseschool_array_get_keys_by_value(lighthouseschool_get_theme_option('counters'));
			$lighthouseschool_post_meta = empty($lighthouseschool_components) 
										? '' 
										: lighthouseschool_show_post_meta(apply_filters('lighthouseschool_filter_post_meta_args', array(
												'components' => $lighthouseschool_components,
												'counters' => $lighthouseschool_counters,
												'seo' => false,
												'echo' => false
												), $lighthouseschool_blog_style[0], $lighthouseschool_columns)
											);
			lighthouseschool_show_layout($lighthouseschool_post_meta);
		?></div><!-- .entry-header -->
	
		<div class="post_content entry-content">
			<div class="post_content_inner">
				<?php
				$lighthouseschool_show_learn_more = !in_array($lighthouseschool_post_format, array('link', 'aside', 'status', 'quote'));
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
				lighthouseschool_show_layout($lighthouseschool_post_meta);
			}
			// More button
			if ( $lighthouseschool_show_learn_more ) {
				?><p><a class="more-link" href="<?php the_permalink(); ?>"><?php esc_html_e('Read more', 'lighthouseschool'); ?></a></p><?php
			}
			?>
		</div><!-- .entry-content -->

	</div></div><!-- .post_inner -->

</article>