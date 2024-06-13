<?php
/* Elegro Crypto Payment support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if ( ! function_exists( 'lighthouseschool_elegro_payment_theme_setup9' ) ) {
	add_action( 'after_setup_theme', 'lighthouseschool_elegro_payment_theme_setup9', 9 );
	function lighthouseschool_elegro_payment_theme_setup9() {
		if ( lighthouseschool_exists_elegro_payment() ) {
			add_action( 'wp_enqueue_scripts',							'lighthouseschool_elegro_payment_frontend_scripts', 1100 );
			add_filter( 'lighthouseschool_filter_merge_styles', 'lighthouseschool_elegro_payment_merge_styles' );
		}
		if ( is_admin() ) {
			add_filter( 'lighthouseschool_filter_tgmpa_required_plugins', 'lighthouseschool_elegro_payment_tgmpa_required_plugins' );
		}
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'lighthouseschool_elegro_payment_tgmpa_required_plugins' ) ) {
	
	function lighthouseschool_elegro_payment_tgmpa_required_plugins($list=array()) {
		if (lighthouseschool_storage_isset('required_plugins', 'elegro-payment')) {
			$list[] = array(
				'name' 		=> lighthouseschool_storage_get_array('required_plugins', 'elegro-payment'),
				'slug' 		=> 'elegro-payment',
				'required' 	=> false
			);
		}
		return $list;
	}
}


// Check if this plugin installed and activated
if ( ! function_exists( 'lighthouseschool_exists_elegro_payment' ) ) {
	function lighthouseschool_exists_elegro_payment() {
		return class_exists( 'WC_Elegro_Payment' );
	}
}

// Enqueue custom styles
if ( !function_exists( 'lighthouseschool_elegro_payment_frontend_scripts' ) ) {
	function lighthouseschool_elegro_payment_frontend_scripts() {
		if (lighthouseschool_exists_elegro_payment()) {
			if (lighthouseschool_is_on(lighthouseschool_get_theme_option('debug_mode')) && lighthouseschool_get_file_dir('plugins/elegro-payment/elegro-payment.css')!='')
				wp_enqueue_style( 'lighthouseschool-elegro-payment',  lighthouseschool_get_file_url('plugins/elegro-payment/elegro-payment.css'), array(), null );
		}
	}
}

// Merge custom styles
if ( !function_exists( 'lighthouseschool_elegro_payment_merge_styles' ) ) {
	
	function lighthouseschool_elegro_payment_merge_styles($list) {
		$list[] = 'plugins/elegro-payment/elegro-payment.css';
		return $list;
	}
}
?>