<?php
/**
 * The template to show mobile menu
 *
 * @package WordPress
 * @subpackage LIGHTHOUSESCHOOL
 * @since LIGHTHOUSESCHOOL 1.0
 */
?>
<div class="menu_mobile_overlay"></div>
<div class="menu_mobile menu_mobile_<?php echo esc_attr(lighthouseschool_get_theme_option('menu_mobile_fullscreen') > 0 ? 'fullscreen' : 'narrow'); ?> scheme_dark">
	<div class="menu_mobile_inner">
		<a class="menu_mobile_close icon-cancel"></a><?php

		// Logo
		set_query_var('lighthouseschool_logo_args', array('type' => 'mobile'));
		get_template_part( 'templates/header-logo' );
		set_query_var('lighthouseschool_logo_args', array());

		// Mobile menu
		$lighthouseschool_menu_mobile = lighthouseschool_get_nav_menu('menu_mobile');
		if (empty($lighthouseschool_menu_mobile)) {
			$lighthouseschool_menu_mobile = apply_filters('lighthouseschool_filter_get_mobile_menu', '');
			if (empty($lighthouseschool_menu_mobile)) $lighthouseschool_menu_mobile = lighthouseschool_get_nav_menu('menu_main');
			if (empty($lighthouseschool_menu_mobile)) $lighthouseschool_menu_mobile = lighthouseschool_get_nav_menu();
		}
		if (!empty($lighthouseschool_menu_mobile)) {
			if (!empty($lighthouseschool_menu_mobile))
				$lighthouseschool_menu_mobile = str_replace(
					array('menu_main', 'id="menu-', 'sc_layouts_menu_nav', 'sc_layouts_hide_on_mobile', 'hide_on_mobile'),
					array('menu_mobile', 'id="menu_mobile-', '', '', ''),
					$lighthouseschool_menu_mobile
					);
			if (strpos($lighthouseschool_menu_mobile, '<nav ')===false)
				$lighthouseschool_menu_mobile = sprintf('<nav class="menu_mobile_nav_area">%s</nav>', $lighthouseschool_menu_mobile);
			lighthouseschool_show_layout(apply_filters('lighthouseschool_filter_menu_mobile_layout', $lighthouseschool_menu_mobile));
		}

		// Search field
		do_action('lighthouseschool_action_search', 'normal', 'search_mobile', false);
		
		// Social icons
		?>
	</div>
</div>
