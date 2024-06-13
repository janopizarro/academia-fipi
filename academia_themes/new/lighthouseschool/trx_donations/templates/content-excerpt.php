<?php
/**
 * The template for displaying donation's content on the archive page
 *
 * @package ThemeREX Donations
 * @since ThemeREX Donations 1.0
 */

$plugin = TRX_DONATIONS::get_instance();
$column_class = isset($columns) && $columns > 1 
	? str_replace( array('$1', '$2'), array('1', $columns), $plugin->get_option('column_class')!='' ? esc_attr($plugin->get_option('column_class')) : 'sc_donations_column-$1_$2' )
	: '';

if (!empty($in_shortcode)) {
	?><div id="post-<?php the_ID(); ?>" class="post_item_<?php echo esc_attr($plugin->get_option('blog_style')); ?> post_type_<?php echo esc_attr(get_post_type()).' '.esc_attr($column_class); ?>"><?php
} else {
	?><article id="post-<?php the_ID(); ?>" <?php post_class( 'post_item_'.esc_attr($plugin->get_option('blog_style')).' post_type_'.esc_attr(get_post_type()).' '.esc_attr($column_class) ); ?>><?php
}
	// Post thumbnail
	if ( has_post_thumbnail() ) {
		?>
		<div class="post_featured">
			<?php the_post_thumbnail( 'masonry', array( 'alt' => the_title_attribute( array( 'echo' => false ) ) ) ); ?>
		</div><!-- .post_featured -->
		<?php
	}
	?>

	<div class="post_body">

		<div class="post_header entry-header">
			<div class="post_info">
				<span class="post_info_item post_date"><?php printf(esc_html__('%s', 'lighthouseschool'), get_the_date()); ?></span>
				<?php
				// Goal and raised
				$goal = get_post_meta( get_the_ID(), 'trx_donations_goal', true );
				if (!empty($goal)) {
					$raised = get_post_meta( get_the_ID(), 'trx_donations_raised', true );
					if (empty($raised)) $raised = 0;
					$manual = get_post_meta( get_the_ID(), 'trx_donations_manual', true );
					?><span class="post_info_item post_goal"><span class="post_counters_label"><?php esc_html_e('Group goal:', 'lighthouseschool'); ?></span> <span class="post_counters_number"><?php echo trim($plugin->get_money($goal)); ?></span></span><?php
					?><span class="post_info_item post_raised"><span class="post_counters_label"><?php esc_html_e('Raised:', 'lighthouseschool'); ?></span> <span class="post_counters_number"><?php echo trim($plugin->get_money($raised+$manual)) . ' (' . round(($raised+$manual)*100/$goal, 2) . '%)'; ?></span></span><?php
				}
				?>
			</div>
			<?php
			$tag = 'h' . (isset($columns) && $columns > 1 ? 6 : 2);
			the_title( sprintf( '<%s class="entry-title"><a href="%s" rel="bookmark">', esc_attr($tag), esc_url(get_permalink()) ), '</a></'.esc_attr($tag).'>' ); ?>
			<div class="post_meta">
				<div class="post_categories"><?php printf(esc_html__('%s', 'lighthouseschool'), get_the_term_list( get_the_ID(), TRX_DONATIONS::TAXONOMY, '', ' ', '' )); ?></div>
			</div><!-- .post_meta -->
		</div><!-- .entry-header -->
	
		<div class="post_content entry-content">
			<?php
			global $post;
			$show_learn_more = true;
			if (!empty($post->post_excerpt)) {
				the_excerpt();
			} else if (strpos($post->post_content, '<!--more')!==false) {
				the_content( esc_html__( 'Donate', 'lighthouseschool' ) );
				$show_learn_more = false;
			} else {
				the_excerpt();
			}
			if ( $show_learn_more ) {
				?><p><a class="more-link theme_button" href="<?php the_permalink(); ?>"><?php esc_html_e('Donate', 'lighthouseschool'); ?></a></p><?php
			}
			?>
		</div><!-- .entry-content -->

	</div><!-- .post_body -->

<?php
if (empty($in_shortcode)) {
	?></article><?php
} else {
	?></div><?php
}
?>