<?php
/* Tribe Events Calendar support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 1 - register filters, that add/remove lists items for the Theme Options
if (!function_exists('lighthouseschool_tribe_events_theme_setup1')) {
	add_action( 'after_setup_theme', 'lighthouseschool_tribe_events_theme_setup1', 1 );
	function lighthouseschool_tribe_events_theme_setup1() {
		add_filter( 'lighthouseschool_filter_list_sidebars', 'lighthouseschool_tribe_events_list_sidebars' );
	}
}

// Theme init priorities:
// 3 - add/remove Theme Options elements
if (!function_exists('lighthouseschool_tribe_events_theme_setup3')) {
	add_action( 'after_setup_theme', 'lighthouseschool_tribe_events_theme_setup3', 3 );
	function lighthouseschool_tribe_events_theme_setup3() {
		if (lighthouseschool_exists_tribe_events()) {
		
			// Section 'Tribe Events'
			lighthouseschool_storage_merge_array('options', '', array_merge(
				array(
					'events' => array(
						"title" => esc_html__('Events', 'lighthouseschool'),
						"desc" => wp_kses_data( __('Select parameters to display the events pages', 'lighthouseschool') ),
						"type" => "section"
						)
				),
				lighthouseschool_options_get_list_cpt_options('events')
			));
		}
	}
}

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('lighthouseschool_tribe_events_theme_setup9')) {
	add_action( 'after_setup_theme', 'lighthouseschool_tribe_events_theme_setup9', 9 );
	function lighthouseschool_tribe_events_theme_setup9() {
		
		if (lighthouseschool_exists_tribe_events()) {
			add_action( 'wp_enqueue_scripts', 								'lighthouseschool_tribe_events_frontend_scripts', 1100 );
			add_filter( 'lighthouseschool_filter_merge_styles',						'lighthouseschool_tribe_events_merge_styles' );
			add_filter( 'lighthouseschool_filter_post_type_taxonomy',				'lighthouseschool_tribe_events_post_type_taxonomy', 10, 2 );
			if (!is_admin()) {
				add_filter( 'lighthouseschool_filter_detect_blog_mode',				'lighthouseschool_tribe_events_detect_blog_mode' );
				add_filter( 'lighthouseschool_filter_get_post_categories', 			'lighthouseschool_tribe_events_get_post_categories');
				add_filter( 'lighthouseschool_filter_get_post_date',		 			'lighthouseschool_tribe_events_get_post_date');
			} else {
				add_action( 'admin_enqueue_scripts',						'lighthouseschool_tribe_events_admin_scripts' );
			}
		}
		if (is_admin()) {
			add_filter( 'lighthouseschool_filter_tgmpa_required_plugins',			'lighthouseschool_tribe_events_tgmpa_required_plugins' );
		}

		add_filter( 'tribe_events_the_next_month_link', 'lighthouseschool_tribe_events_next_month' );
		add_filter( 'tribe_events_the_previous_month_link', 'lighthouseschool_tribe_events_previous_month' );

	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'lighthouseschool_tribe_events_tgmpa_required_plugins' ) ) {
	
	function lighthouseschool_tribe_events_tgmpa_required_plugins($list=array()) {
		if (lighthouseschool_storage_isset('required_plugins', 'the-events-calendar')) {
			$list[] = array(
					'name' 		=> lighthouseschool_storage_get_array('required_plugins', 'the-events-calendar'),
					'slug' 		=> 'the-events-calendar',
					'required' 	=> false
				);
		}
		return $list;
	}
}


// Remove 'Tribe Events' section from Customizer
if (!function_exists('lighthouseschool_tribe_events_customizer_register_controls')) {
	add_action( 'customize_register', 'lighthouseschool_tribe_events_customizer_register_controls', 100 );
	function lighthouseschool_tribe_events_customizer_register_controls( $wp_customize ) {
		$wp_customize->remove_panel( 'tribe_customizer');
	}
}


// Check if Tribe Events is installed and activated
if ( !function_exists( 'lighthouseschool_exists_tribe_events' ) ) {
	function lighthouseschool_exists_tribe_events() {
		return class_exists( 'Tribe__Events__Main' );
	}
}

// Return true, if current page is any tribe_events page
if ( !function_exists( 'lighthouseschool_is_tribe_events_page' ) ) {
	function lighthouseschool_is_tribe_events_page() {
		$rez = false;
		if (lighthouseschool_exists_tribe_events())
			if (!is_search()) $rez = tribe_is_event() || tribe_is_event_query() || tribe_is_event_category() || tribe_is_event_venue() || tribe_is_event_organizer();
		return $rez;
	}
}

// Detect current blog mode
if ( !function_exists( 'lighthouseschool_tribe_events_detect_blog_mode' ) ) {
	
	function lighthouseschool_tribe_events_detect_blog_mode($mode='') {
		if (lighthouseschool_is_tribe_events_page())
			$mode = 'events';
		return $mode;
	}
}

// Return taxonomy for current post type
if ( !function_exists( 'lighthouseschool_tribe_events_post_type_taxonomy' ) ) {
	
	function lighthouseschool_tribe_events_post_type_taxonomy($tax='', $post_type='') {
		if (lighthouseschool_exists_tribe_events() && $post_type == Tribe__Events__Main::POSTTYPE)
			$tax = Tribe__Events__Main::TAXONOMY;
		return $tax;
	}
}

// Show categories of the current event
if ( !function_exists( 'lighthouseschool_tribe_events_get_post_categories' ) ) {
	
	function lighthouseschool_tribe_events_get_post_categories($cats='') {
		if (get_post_type() == Tribe__Events__Main::POSTTYPE)
			$cats = lighthouseschool_get_post_terms(', ', get_the_ID(), Tribe__Events__Main::TAXONOMY);
		return $cats;
	}
}

// Return date of the current event
if ( !function_exists( 'lighthouseschool_tribe_events_get_post_date' ) ) {
	
	function lighthouseschool_tribe_events_get_post_date($dt='') {
		if (get_post_type() == Tribe__Events__Main::POSTTYPE) {
			$dt = tribe_get_start_date(null, true, 'Y-m-d');
			$dt = sprintf($dt < date('Y-m-d') 
								? esc_html__('Started on %s', 'lighthouseschool') 
								: esc_html__('Starting %s', 'lighthouseschool'),
								date(get_option('date_format'), strtotime($dt)));
		}
		return $dt;
	}
}
	
// Enqueue Tribe Events admin scripts and styles
if ( !function_exists( 'lighthouseschool_tribe_events_admin_scripts' ) ) {
	
	function lighthouseschool_tribe_events_admin_scripts() {
	}
}

// Enqueue Tribe Events custom scripts and styles
if ( !function_exists( 'lighthouseschool_tribe_events_frontend_scripts' ) ) {
	function lighthouseschool_tribe_events_frontend_scripts() {
		if (lighthouseschool_is_tribe_events_page()) {
			if (lighthouseschool_is_on(lighthouseschool_get_theme_option('debug_mode')) && lighthouseschool_get_file_dir('plugins/the-events-calendar/the-events-calendar.css')!='')
				wp_enqueue_style( 'lighthouseschool-the-events-calendar',  lighthouseschool_get_file_url('plugins/the-events-calendar/the-events-calendar.css'), array(), null );
			if (lighthouseschool_is_on(lighthouseschool_get_theme_option('debug_mode')) && lighthouseschool_get_file_dir('css/the-events-calendar.css')!='')
				wp_enqueue_style( 'lighthouseschool-the-events-calendar-images',  lighthouseschool_get_file_url('css/the-events-calendar.css'), array(), null );
		}
	}
}

// Merge custom styles
if ( !function_exists( 'lighthouseschool_tribe_events_merge_styles' ) ) {
	
	function lighthouseschool_tribe_events_merge_styles($list) {
		$list[] = 'plugins/the-events-calendar/the-events-calendar.css';
		$list[] = 'css/the-events-calendar.css';
		return $list;
	}
}


if ( !function_exists( 'lighthouseschool_tribe_events_next_month' ) ) {
	function lighthouseschool_tribe_events_next_month() {
		$url = tribe_get_next_month_link();
		$text = tribe_get_next_month_text();
		$date = Tribe__Events__Main::instance()->nextMonth( tribe_get_month_view_date() );
		return '<a data-month="' . $date . '" href="' . esc_url($url) . '" rel="next">' . $text . ' <span>&raquo;</span></a>';
	}
}

if ( !function_exists( 'lighthouseschool_tribe_events_previous_month' ) ) {
	function lighthouseschool_tribe_events_previous_month() {
		$url = tribe_get_previous_month_link();
		$text = tribe_get_previous_month_text();
		$date = Tribe__Events__Main::instance()->previousMonth( tribe_get_month_view_date() );
		return '<a data-month="' . $date . '" href="' . esc_url($url) . '" rel="prev"><span>&laquo;</span> ' . $text . ' </a>';
	}
}




// Add Tribe Events specific items into lists
//------------------------------------------------------------------------

// Add sidebar
if ( !function_exists( 'lighthouseschool_tribe_events_list_sidebars' ) ) {
	
	function lighthouseschool_tribe_events_list_sidebars($list=array()) {
		$list['tribe_events_widgets'] = array(
											'name' => esc_html__('Tribe Events Widgets', 'lighthouseschool'),
											'description' => esc_html__('Widgets to be shown on the Tribe Events pages', 'lighthouseschool')
											);
		return $list;
	}
}


// Add plugin-specific colors and fonts to the custom CSS
if (lighthouseschool_exists_tribe_events()) { require_once LIGHTHOUSESCHOOL_THEME_DIR . 'plugins/the-events-calendar/the-events-calendar.styles.php'; }
?>