<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package WordPress
 * @subpackage LIGHTHOUSESCHOOL
 * @since LIGHTHOUSESCHOOL 1.0
 */

if (lighthouseschool_sidebar_present()) {
	ob_start();
	$lighthouseschool_sidebar_name = lighthouseschool_get_theme_option('sidebar_widgets');
	lighthouseschool_storage_set('current_sidebar', 'sidebar');
	if ( is_active_sidebar($lighthouseschool_sidebar_name) ) {
		dynamic_sidebar($lighthouseschool_sidebar_name);
	}
	$lighthouseschool_out = trim(ob_get_contents());
	ob_end_clean();
	if (!empty($lighthouseschool_out)) {
		$lighthouseschool_sidebar_position = lighthouseschool_get_theme_option('sidebar_position');
		?>
		<div class="sidebar <?php echo esc_attr($lighthouseschool_sidebar_position); ?> widget_area<?php if (!lighthouseschool_is_inherit(lighthouseschool_get_theme_option('sidebar_scheme'))) echo ' scheme_'.esc_attr(lighthouseschool_get_theme_option('sidebar_scheme')); ?>" role="complementary">
			<div class="sidebar_inner">
				<?php
				do_action( 'lighthouseschool_action_before_sidebar' );
				lighthouseschool_show_layout(preg_replace("/<\/aside>[\r\n\s]*<aside/", "</aside><aside", $lighthouseschool_out));
				do_action( 'lighthouseschool_action_after_sidebar' );
				?>
			</div><!-- /.sidebar_inner -->
		</div><!-- /.sidebar -->
		<?php
	}
}
?>