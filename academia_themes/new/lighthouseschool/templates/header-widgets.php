<?php
/**
 * The template to display the widgets area in the header
 *
 * @package WordPress
 * @subpackage LIGHTHOUSESCHOOL
 * @since LIGHTHOUSESCHOOL 1.0
 */

// Header sidebar
$lighthouseschool_header_name = lighthouseschool_get_theme_option('header_widgets');
$lighthouseschool_header_present = !lighthouseschool_is_off($lighthouseschool_header_name) && is_active_sidebar($lighthouseschool_header_name);
if ($lighthouseschool_header_present) { 
	lighthouseschool_storage_set('current_sidebar', 'header');
	$lighthouseschool_header_wide = lighthouseschool_get_theme_option('header_wide');
	ob_start();
	if ( is_active_sidebar($lighthouseschool_header_name) ) {
		dynamic_sidebar($lighthouseschool_header_name);
	}
	$lighthouseschool_widgets_output = ob_get_contents();
	ob_end_clean();
	if (!empty($lighthouseschool_widgets_output)) {
		$lighthouseschool_widgets_output = preg_replace("/<\/aside>[\r\n\s]*<aside/", "</aside><aside", $lighthouseschool_widgets_output);
		$lighthouseschool_need_columns = strpos($lighthouseschool_widgets_output, 'columns_wrap')===false;
		if ($lighthouseschool_need_columns) {
			$lighthouseschool_columns = max(0, (int) lighthouseschool_get_theme_option('header_columns'));
			if ($lighthouseschool_columns == 0) $lighthouseschool_columns = min(6, max(1, substr_count($lighthouseschool_widgets_output, '<aside ')));
			if ($lighthouseschool_columns > 1)
				$lighthouseschool_widgets_output = preg_replace("/class=\"widget /", "class=\"column-1_".esc_attr($lighthouseschool_columns).' widget ', $lighthouseschool_widgets_output);
			else
				$lighthouseschool_need_columns = false;
		}
		?>
		<div class="header_widgets_wrap widget_area<?php echo !empty($lighthouseschool_header_wide) ? ' header_fullwidth' : ' header_boxed'; ?>">
			<div class="header_widgets_inner widget_area_inner">
				<?php 
				if (!$lighthouseschool_header_wide) { 
					?><div class="content_wrap"><?php
				}
				if ($lighthouseschool_need_columns) {
					?><div class="columns_wrap"><?php
				}
				do_action( 'lighthouseschool_action_before_sidebar' );
				lighthouseschool_show_layout($lighthouseschool_widgets_output);
				do_action( 'lighthouseschool_action_after_sidebar' );
				if ($lighthouseschool_need_columns) {
					?></div>	<!-- /.columns_wrap --><?php
				}
				if (!$lighthouseschool_header_wide) {
					?></div>	<!-- /.content_wrap --><?php
				}
				?>
			</div>	<!-- /.header_widgets_inner -->
		</div>	<!-- /.header_widgets_wrap -->
		<?php
	}
}
?>