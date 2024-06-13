<?php
/* Revolution Slider support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('lighthouseschool_revslider_theme_setup9')) {
	add_action( 'after_setup_theme', 'lighthouseschool_revslider_theme_setup9', 9 );
	function lighthouseschool_revslider_theme_setup9() {
		if (lighthouseschool_exists_revslider()) {
			add_action( 'wp_enqueue_scripts', 					'lighthouseschool_revslider_frontend_scripts', 1100 );
			add_filter( 'lighthouseschool_filter_merge_styles',			'lighthouseschool_revslider_merge_styles' );
		}
		if (is_admin()) {
			add_filter( 'lighthouseschool_filter_tgmpa_required_plugins','lighthouseschool_revslider_tgmpa_required_plugins' );
		}
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'lighthouseschool_revslider_tgmpa_required_plugins' ) ) {
	
	function lighthouseschool_revslider_tgmpa_required_plugins($list=array()) {
		if (lighthouseschool_storage_isset('required_plugins', 'revslider')) {
			$path = lighthouseschool_get_file_dir('plugins/revslider/revslider.zip');
			if (!empty($path) || lighthouseschool_get_theme_setting('tgmpa_upload')) {
				$list[] = array(
					'name' 		=> lighthouseschool_storage_get_array('required_plugins', 'revslider'),
					'slug' 		=> 'revslider',
					'version'	=> '6.6.14',
					'source'	=> !empty($path) ? $path : 'upload://revslider.zip',
					'required' 	=> false
				);
			}
		}
		return $list;
	}
}

// Check if RevSlider installed and activated
if ( !function_exists( 'lighthouseschool_exists_revslider' ) ) {
	function lighthouseschool_exists_revslider() {
		return function_exists('rev_slider_shortcode');
	}
}
	
// Enqueue custom styles
if ( !function_exists( 'lighthouseschool_revslider_frontend_scripts' ) ) {
	function lighthouseschool_revslider_frontend_scripts() {
		if (lighthouseschool_is_on(lighthouseschool_get_theme_option('debug_mode')) && lighthouseschool_get_file_dir('plugins/revslider/revslider.css')!='')
			wp_enqueue_style( 'lighthouseschool-revslider',  lighthouseschool_get_file_url('plugins/revslider/revslider.css'), array(), null );
	}
}
	
// Merge custom styles
if ( !function_exists( 'lighthouseschool_revslider_merge_styles' ) ) {
	
	function lighthouseschool_revslider_merge_styles($list) {
		$list[] = 'plugins/revslider/revslider.css';
		return $list;
	}
}
?>