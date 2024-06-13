<?php
/* Give (donation forms) support functions
------------------------------------------------------------------------------- */

if ( ! defined( 'LIGHTHOUSESCHOOL_GIVE_FORMS_PT_FORMS' ) )			define( 'LIGHTHOUSESCHOOL_GIVE_FORMS_PT_FORMS', 'give_forms' );
if ( ! defined( 'LIGHTHOUSESCHOOL_GIVE_FORMS_PT_PAYMENT' ) )			define( 'LIGHTHOUSESCHOOL_GIVE_FORMS_PT_PAYMENT', 'give_payment' );
if ( ! defined( 'LIGHTHOUSESCHOOL_GIVE_FORMS_TAXONOMY_CATEGORY' ) )	define( 'LIGHTHOUSESCHOOL_GIVE_FORMS_TAXONOMY_CATEGORY', 'give_forms_category' );
if ( ! defined( 'LIGHTHOUSESCHOOL_GIVE_FORMS_TAXONOMY_TAG' ) )		define( 'LIGHTHOUSESCHOOL_GIVE_FORMS_TAXONOMY_TAG', 'give_forms_tag' );


// Theme init priorities:
// 3 - add/remove Theme Options elements
if ( ! function_exists( 'lighthouseschool_give_theme_setup3' ) ) {
	add_action( 'after_setup_theme', 'lighthouseschool_give_theme_setup3', 3 );
	function lighthouseschool_give_theme_setup3() {
		if ( lighthouseschool_exists_give() ) {
			// Section 'Give'
			lighthouseschool_storage_merge_array(
				'options', '', array_merge(
					array(
						'give' => array(
							'title' => esc_html__( 'Give Donations', 'lighthouseschool' ),
							'desc'  => wp_kses_data( __( 'Select parameters to display the Give Donations pages', 'lighthouseschool' ) ),
							'type'  => 'section',
						),
					),
					lighthouseschool_options_get_list_cpt_options( 'give', esc_html__( 'Give Donations', 'lighthouseschool' ) )
				)
			);
		}
	}
}

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if ( ! function_exists( 'lighthouseschool_give_theme_setup9' ) ) {
	add_action( 'after_setup_theme', 'lighthouseschool_give_theme_setup9', 9 );
	function lighthouseschool_give_theme_setup9() {
		if ( lighthouseschool_exists_give() ) {
			add_action( 'wp_enqueue_scripts', 'lighthouseschool_give_frontend_scripts', 1100 );
			add_filter( 'lighthouseschool_filter_merge_styles', 'lighthouseschool_give_merge_styles' );
			add_filter( 'lighthouseschool_filter_get_post_categories', 'lighthouseschool_give_get_post_categories');
			add_filter( 'lighthouseschool_filter_post_type_taxonomy', 'lighthouseschool_give_post_type_taxonomy', 10, 2 );
			add_filter( 'lighthouseschool_filter_detect_blog_mode', 'lighthouseschool_give_detect_blog_mode' );
		}
		if ( is_admin() ) {
			add_filter( 'lighthouseschool_filter_tgmpa_required_plugins', 'lighthouseschool_give_tgmpa_required_plugins' );
		}
	}
}

// Filter to add in the required plugins list
if ( ! function_exists( 'lighthouseschool_give_tgmpa_required_plugins' ) ) {
	
	function lighthouseschool_give_tgmpa_required_plugins( $list = array() ) {
		if ( lighthouseschool_storage_isset( 'required_plugins', 'give' ) ) {
			$list[] = array(
				'name'     => lighthouseschool_storage_get_array( 'required_plugins', 'give'),
				'slug'     => 'give',
				'required' => false,
			);
		}
		return $list;
	}
}

// Check if plugin installed and activated
if ( ! function_exists( 'lighthouseschool_exists_give' ) ) {
	function lighthouseschool_exists_give() {
		return class_exists( 'Give' );
	}
}

// Enqueue styles for frontend
if ( ! function_exists( 'lighthouseschool_give_frontend_scripts' ) ) {
	
	function lighthouseschool_give_frontend_scripts() {
		if ( lighthouseschool_is_on( lighthouseschool_get_theme_option( 'debug_mode' ) ) ) {
			$lighthouseschool_url = lighthouseschool_get_file_url( 'plugins/give/give.css' );
			if ( '' != $lighthouseschool_url ) {
				wp_enqueue_style( 'lighthouseschool-give', $lighthouseschool_url, array(), null );
			}
		}
	}
}

// Merge custom styles
if ( ! function_exists( 'lighthouseschool_give_merge_styles' ) ) {
	
	function lighthouseschool_give_merge_styles( $list ) {
		$list[] = 'plugins/give/give.css';
		return $list;
	}
}

// Return true, if current page is any give page
if ( ! function_exists( 'lighthouseschool_is_give_page' ) ) {
	function lighthouseschool_is_give_page() {
		$rez = lighthouseschool_exists_give()
					&& ! is_search()
					&& (
						is_singular( LIGHTHOUSESCHOOL_GIVE_FORMS_PT_FORMS )
						|| is_post_type_archive( LIGHTHOUSESCHOOL_GIVE_FORMS_PT_FORMS )
						|| is_tax( LIGHTHOUSESCHOOL_GIVE_FORMS_TAXONOMY_CATEGORY )
						|| is_tax( LIGHTHOUSESCHOOL_GIVE_FORMS_TAXONOMY_TAG )
						|| ( function_exists( 'is_give_form' ) && is_give_form() )
						|| ( function_exists( 'is_give_category' ) && is_give_category() )
						|| ( function_exists( 'is_give_tag' ) && is_give_tag() )
						);
		return $rez;
	}
}

// Detect current blog mode
if ( ! function_exists( 'lighthouseschool_give_detect_blog_mode' ) ) {
	
	function lighthouseschool_give_detect_blog_mode( $mode = '' ) {
		if ( lighthouseschool_is_give_page() ) {
			$mode = 'give';
		}
		return $mode;
	}
}

// Return taxonomy for current post type
if ( ! function_exists( 'lighthouseschool_give_post_type_taxonomy' ) ) {
	
	function lighthouseschool_give_post_type_taxonomy( $tax = '', $post_type = '' ) {
		if ( lighthouseschool_exists_give() && LIGHTHOUSESCHOOL_GIVE_FORMS_PT_FORMS == $post_type ) {
			$tax = LIGHTHOUSESCHOOL_GIVE_FORMS_TAXONOMY_CATEGORY;
		}
		return $tax;
	}
}

// Show categories of the current product
if ( ! function_exists( 'lighthouseschool_give_get_post_categories' ) ) {
	
	function lighthouseschool_give_get_post_categories( $cats = '' ) {
		if ( get_post_type() == LIGHTHOUSESCHOOL_GIVE_FORMS_PT_FORMS ) {
			$cats = lighthouseschool_get_post_terms( ', ', get_the_ID(), LIGHTHOUSESCHOOL_GIVE_FORMS_TAXONOMY_CATEGORY );
		}
		return $cats;
	}
}


// Add plugin-specific colors and fonts to the custom CSS

// Add plugin-specific colors and fonts to the custom CSS
if (lighthouseschool_exists_give()) { require_once LIGHTHOUSESCHOOL_THEME_DIR . 'plugins/give/give-styles.php'; }