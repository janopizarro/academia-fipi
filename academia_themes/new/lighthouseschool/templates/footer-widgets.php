<?php
/**
 * The template to display the widgets area in the footer
 *
 * @package WordPress
 * @subpackage LIGHTHOUSESCHOOL
 * @since LIGHTHOUSESCHOOL 1.0.10
 */

// Footer sidebar
$lighthouseschool_footer_name = lighthouseschool_get_theme_option('footer_widgets');
$lighthouseschool_footer_present = !lighthouseschool_is_off($lighthouseschool_footer_name) && is_active_sidebar($lighthouseschool_footer_name);
if ($lighthouseschool_footer_present) { 
	lighthouseschool_storage_set('current_sidebar', 'footer');
	$lighthouseschool_footer_wide = lighthouseschool_get_theme_option('footer_wide');
	ob_start();
	if ( is_active_sidebar($lighthouseschool_footer_name) ) {
		dynamic_sidebar($lighthouseschool_footer_name);
	}
	$lighthouseschool_out = trim(ob_get_contents());
	ob_end_clean();
	if (!empty($lighthouseschool_out)) {
		$lighthouseschool_out = preg_replace("/<\\/aside>[\r\n\s]*<aside/", "</aside><aside", $lighthouseschool_out);
		$lighthouseschool_need_columns = true;
		if ($lighthouseschool_need_columns) {
			$lighthouseschool_columns = max(0, (int) lighthouseschool_get_theme_option('footer_columns'));
			if ($lighthouseschool_columns == 0) $lighthouseschool_columns = min(4, max(1, substr_count($lighthouseschool_out, '<aside ')));
			if ($lighthouseschool_columns > 1)
				$lighthouseschool_out = preg_replace("/class=\"widget /", "class=\"column-1_".esc_attr($lighthouseschool_columns).' widget ', $lighthouseschool_out);
			else
				$lighthouseschool_need_columns = false;
		}
		?>
		<div class="footer_widgets_wrap widget_area<?php echo !empty($lighthouseschool_footer_wide) ? ' footer_fullwidth' : ''; ?> sc_layouts_row  sc_layouts_row_type_normal">
			<div class="footer_widgets_inner widget_area_inner">
				<?php 
				if (!$lighthouseschool_footer_wide) { 
					?><div class="content_wrap"><?php
				}
				if ($lighthouseschool_need_columns) {
					?><div class="columns_wrap"><?php
				}
				do_action( 'lighthouseschool_action_before_sidebar' );
				lighthouseschool_show_layout($lighthouseschool_out);
				do_action( 'lighthouseschool_action_after_sidebar' );
				if ($lighthouseschool_need_columns) {
					?></div><!-- /.columns_wrap --><?php
				}
				if (!$lighthouseschool_footer_wide) {
					?></div><!-- /.content_wrap --><?php
				}
				?>
			</div><!-- /.footer_widgets_inner -->
		</div><!-- /.footer_widgets_wrap -->
		<?php
	}
}
?>