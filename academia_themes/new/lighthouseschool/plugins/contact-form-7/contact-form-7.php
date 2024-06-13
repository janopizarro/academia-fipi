<?php
/* Contact Form 7 support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('lighthouseschool_cf7_theme_setup9')) {
	add_action( 'after_setup_theme', 'lighthouseschool_cf7_theme_setup9', 9 );
	function lighthouseschool_cf7_theme_setup9() {
		
		if (lighthouseschool_exists_cf7()) {
			add_action( 'wp_enqueue_scripts', 								'lighthouseschool_cf7_frontend_scripts', 1100 );
			add_filter( 'lighthouseschool_filter_merge_styles',				'lighthouseschool_cf7_merge_styles' );
		}
		if (is_admin()) {
			add_filter( 'lighthouseschool_filter_tgmpa_required_plugins',			'lighthouseschool_cf7_tgmpa_required_plugins' );
		}
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'lighthouseschool_cf7_tgmpa_required_plugins' ) ) {
	
	function lighthouseschool_cf7_tgmpa_required_plugins($list=array()) {
		if (lighthouseschool_storage_isset('required_plugins', 'contact-form-7')) {
			// CF7 plugin
			$list[] = array(
					'name' 		=> lighthouseschool_storage_get_array('required_plugins', 'contact-form-7'),
					'slug' 		=> 'contact-form-7',
					'required' 	=> false
			);
		}
		return $list;
	}
}

// Check if cf7 installed and activated
if ( !function_exists( 'lighthouseschool_exists_cf7' ) ) {
	function lighthouseschool_exists_cf7() {
		return class_exists('WPCF7');
	}
}
	
// Enqueue custom styles
if ( !function_exists( 'lighthouseschool_cf7_frontend_scripts' ) ) {
	
	function lighthouseschool_cf7_frontend_scripts() {
		if (lighthouseschool_is_on(lighthouseschool_get_theme_option('debug_mode')) && lighthouseschool_get_file_dir('plugins/contact-form-7/contact-form-7.css')!='')
			wp_enqueue_style( 'lighthouseschool-contact-form-7',  lighthouseschool_get_file_url('plugins/contact-form-7/contact-form-7.css'), array(), null );
	}
}
	
// Merge custom styles
if ( !function_exists( 'lighthouseschool_cf7_merge_styles' ) ) {
	
	function lighthouseschool_cf7_merge_styles($list) {
		$list[] = 'plugins/contact-form-7/contact-form-7.css';
		return $list;
	}
}
?>