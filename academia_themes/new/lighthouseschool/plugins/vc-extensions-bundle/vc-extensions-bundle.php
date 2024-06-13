<?php
/* WPBakery Page Builder Extensions Bundle support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('lighthouseschool_vc_extensions_theme_setup9')) {
	add_action( 'after_setup_theme', 'lighthouseschool_vc_extensions_theme_setup9', 9 );
	function lighthouseschool_vc_extensions_theme_setup9() {
		if (lighthouseschool_exists_visual_composer()) {
			add_action( 'wp_enqueue_scripts', 								'lighthouseschool_vc_extensions_frontend_scripts', 1100 );
			add_filter( 'lighthouseschool_filter_merge_styles',						'lighthouseschool_vc_extensions_merge_styles' );
		}
	
		if (is_admin()) {
			add_filter( 'lighthouseschool_filter_tgmpa_required_plugins',		'lighthouseschool_vc_extensions_tgmpa_required_plugins' );
		}
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'lighthouseschool_vc_extensions_tgmpa_required_plugins' ) ) {
	
	function lighthouseschool_vc_extensions_tgmpa_required_plugins($list=array()) {
		if (lighthouseschool_storage_isset('required_plugins', 'vc-extensions-bundle')) {
			$path = lighthouseschool_get_file_dir('plugins/vc-extensions-bundle/vc-extensions-bundle.zip');
			if (!empty($path) || lighthouseschool_get_theme_setting('tgmpa_upload')) {
				$list[] = array(
					'name' 		=> lighthouseschool_storage_get_array('required_plugins', 'vc-extensions-bundle'),
					'slug' 		=> 'vc-extensions-bundle',
					'version'	=> '3.6.5',
					'source'	=> !empty($path) ? $path : 'upload://vc-extensions-bundle.zip',
					'required' 	=> false
				);
			}
		}
		return $list;
	}
}

// Check if VC Extensions installed and activated
if ( !function_exists( 'lighthouseschool_exists_vc_extensions' ) ) {
	function lighthouseschool_exists_vc_extensions() {
		return class_exists('Vc_Manager') && class_exists('VC_Extensions_CQBundle');
	}
}
	
// Enqueue VC custom styles
if ( !function_exists( 'lighthouseschool_vc_extensions_frontend_scripts' ) ) {
	function lighthouseschool_vc_extensions_frontend_scripts() {
		if (lighthouseschool_is_on(lighthouseschool_get_theme_option('debug_mode')) && lighthouseschool_get_file_dir('plugins/vc-extensions-bundle/vc-extensions-bundle.css')!='')
			wp_enqueue_style( 'lighthouseschool-vc-extensions-bundle',  lighthouseschool_get_file_url('plugins/vc-extensions-bundle/vc-extensions-bundle.css'), array(), null );
	}
}
	
// Merge custom styles
if ( !function_exists( 'lighthouseschool_vc_extensions_merge_styles' ) ) {
	
	function lighthouseschool_vc_extensions_merge_styles($list) {
		$list[] = 'plugins/vc-extensions-bundle/vc-extensions-bundle.css';
		return $list;
	}
}
?>