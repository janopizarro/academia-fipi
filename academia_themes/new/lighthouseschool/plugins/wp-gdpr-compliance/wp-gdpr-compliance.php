<?php
/* Cookie Information support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if ( ! function_exists( 'lighthouseschool_wp_gdpr_compliance_theme_setup9' ) ) {
	add_action( 'after_setup_theme', 'lighthouseschool_wp_gdpr_compliance_theme_setup9', 9 );
	function lighthouseschool_wp_gdpr_compliance_theme_setup9() {
		if ( is_admin() ) {
			add_filter( 'lighthouseschool_filter_tgmpa_required_plugins', 'lighthouseschool_wp_gdpr_compliance_tgmpa_required_plugins' );
		}
	}
}




// Filter to add in the required plugins list
if ( !function_exists( 'lighthouseschool_wp_gdpr_compliance_tgmpa_required_plugins' ) ) {
	
	function lighthouseschool_wp_gdpr_compliance_tgmpa_required_plugins($list=array()) {
		if (lighthouseschool_storage_isset('required_plugins', 'wp-gdpr-compliance')) {
			$list[] = array(
				'name' 		=> lighthouseschool_storage_get_array('required_plugins', 'wp-gdpr-compliance'),
				'slug' 		=> 'wp-gdpr-compliance',
				'required' 	=> false
			);
		}
		return $list;
	}
}


// Check if this plugin installed and activated
if ( ! function_exists( 'lighthouseschool_exists_wp_gdpr_compliance' ) ) {
	function lighthouseschool_exists_wp_gdpr_compliance() {
		return class_exists( 'WPGDPRC\WPGDPRC' );
	}
}
