<?php
/**
 * The template for homepage posts with "Portfolio" style
 *
 * @package WordPress
 * @subpackage LIGHTHOUSESCHOOL
 * @since LIGHTHOUSESCHOOL 1.0
 */

lighthouseschool_storage_set('blog_archive', true);



get_header(); 

if (have_posts()) {

	echo get_query_var('blog_archive_start');

	$lighthouseschool_stickies = is_home() ? get_option( 'sticky_posts' ) : false;
	$lighthouseschool_sticky_out = lighthouseschool_get_theme_option('sticky_style')=='columns' 
							&& is_array($lighthouseschool_stickies) && count($lighthouseschool_stickies) > 0 && get_query_var( 'paged' ) < 1;
	
	// Show filters
	$lighthouseschool_cat = lighthouseschool_get_theme_option('parent_cat');
	$lighthouseschool_post_type = lighthouseschool_get_theme_option('post_type');
	$lighthouseschool_taxonomy = lighthouseschool_get_post_type_taxonomy($lighthouseschool_post_type);
	$lighthouseschool_show_filters = lighthouseschool_get_theme_option('show_filters');
	$lighthouseschool_tabs = array();
	if (!lighthouseschool_is_off($lighthouseschool_show_filters)) {
		$lighthouseschool_args = array(
			'type'			=> $lighthouseschool_post_type,
			'child_of'		=> $lighthouseschool_cat,
			'orderby'		=> 'name',
			'order'			=> 'ASC',
			'hide_empty'	=> 1,
			'hierarchical'	=> 0,
			'exclude'		=> '',
			'include'		=> '',
			'number'		=> '',
			'taxonomy'		=> $lighthouseschool_taxonomy,
			'pad_counts'	=> false
		);
		$lighthouseschool_portfolio_list = get_terms($lighthouseschool_args);
		if (is_array($lighthouseschool_portfolio_list) && count($lighthouseschool_portfolio_list) > 0) {
			$lighthouseschool_tabs[$lighthouseschool_cat] = esc_html__('All', 'lighthouseschool');
			foreach ($lighthouseschool_portfolio_list as $lighthouseschool_term) {
				if (isset($lighthouseschool_term->term_id)) $lighthouseschool_tabs[$lighthouseschool_term->term_id] = $lighthouseschool_term->name;
			}
		}
	}
	if (count($lighthouseschool_tabs) > 0) {
		$lighthouseschool_portfolio_filters_ajax = true;
		$lighthouseschool_portfolio_filters_active = $lighthouseschool_cat;
		$lighthouseschool_portfolio_filters_id = 'portfolio_filters';
		?>
		<div class="portfolio_filters lighthouseschool_tabs lighthouseschool_tabs_ajax">
			<ul class="portfolio_titles lighthouseschool_tabs_titles">
				<?php
				foreach ($lighthouseschool_tabs as $lighthouseschool_id=>$lighthouseschool_title) {
					?><li><a href="<?php echo esc_url(lighthouseschool_get_hash_link(sprintf('#%s_%s_content', $lighthouseschool_portfolio_filters_id, $lighthouseschool_id))); ?>" data-tab="<?php echo esc_attr($lighthouseschool_id); ?>"><?php echo esc_html($lighthouseschool_title); ?></a></li><?php
				}
				?>
			</ul>
			<?php
			$lighthouseschool_ppp = lighthouseschool_get_theme_option('posts_per_page');
			if (lighthouseschool_is_inherit($lighthouseschool_ppp)) $lighthouseschool_ppp = '';
			foreach ($lighthouseschool_tabs as $lighthouseschool_id=>$lighthouseschool_title) {
				$lighthouseschool_portfolio_need_content = $lighthouseschool_id==$lighthouseschool_portfolio_filters_active || !$lighthouseschool_portfolio_filters_ajax;
				?>
				<div id="<?php echo esc_attr(sprintf('%s_%s_content', $lighthouseschool_portfolio_filters_id, $lighthouseschool_id)); ?>"
					class="portfolio_content lighthouseschool_tabs_content"
					data-blog-template="<?php echo esc_attr(lighthouseschool_storage_get('blog_template')); ?>"
					data-blog-style="<?php echo esc_attr(lighthouseschool_get_theme_option('blog_style')); ?>"
					data-posts-per-page="<?php echo esc_attr($lighthouseschool_ppp); ?>"
					data-post-type="<?php echo esc_attr($lighthouseschool_post_type); ?>"
					data-taxonomy="<?php echo esc_attr($lighthouseschool_taxonomy); ?>"
					data-cat="<?php echo esc_attr($lighthouseschool_id); ?>"
					data-parent-cat="<?php echo esc_attr($lighthouseschool_cat); ?>"
					data-need-content="<?php echo (false===$lighthouseschool_portfolio_need_content ? 'true' : 'false'); ?>"
				>
					<?php
					if ($lighthouseschool_portfolio_need_content) 
						lighthouseschool_show_portfolio_posts(array(
							'cat' => $lighthouseschool_id,
							'parent_cat' => $lighthouseschool_cat,
							'taxonomy' => $lighthouseschool_taxonomy,
							'post_type' => $lighthouseschool_post_type,
							'page' => 1,
							'sticky' => $lighthouseschool_sticky_out
							)
						);
					?>
				</div>
				<?php
			}
			?>
		</div>
		<?php
	} else {
		lighthouseschool_show_portfolio_posts(array(
			'cat' => $lighthouseschool_cat,
			'parent_cat' => $lighthouseschool_cat,
			'taxonomy' => $lighthouseschool_taxonomy,
			'post_type' => $lighthouseschool_post_type,
			'page' => 1,
			'sticky' => $lighthouseschool_sticky_out
			)
		);
	}

	echo get_query_var('blog_archive_end');

} else {

	if ( is_search() )
		get_template_part( 'content', 'none-search' );
	else
		get_template_part( 'content', 'none-archive' );

}

get_footer();
?>