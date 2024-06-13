<?php
/* ThemeREX Addons support functions
------------------------------------------------------------------------------- */

// Add theme-specific functions
require_once LIGHTHOUSESCHOOL_THEME_DIR . 'theme-specific/trx_addons.setup.php';

// Theme init priorities:
// 1 - register filters, that add/remove lists items for the Theme Options
if (!function_exists('lighthouseschool_trx_addons_theme_setup1')) {
	add_action( 'after_setup_theme', 'lighthouseschool_trx_addons_theme_setup1', 1 );
	add_action( 'trx_addons_action_save_options', 'lighthouseschool_trx_addons_theme_setup1', 8 );
	function lighthouseschool_trx_addons_theme_setup1() {
		if (lighthouseschool_exists_trx_addons()) {
			add_filter( 'lighthouseschool_filter_list_posts_types',	'lighthouseschool_trx_addons_list_post_types');
			add_filter( 'lighthouseschool_filter_list_header_styles','lighthouseschool_trx_addons_list_header_styles');
			add_filter( 'lighthouseschool_filter_list_footer_styles','lighthouseschool_trx_addons_list_footer_styles');
			add_filter( 'trx_addons_filter_default_layouts','lighthouseschool_trx_addons_default_layouts');
			add_filter( 'trx_addons_filter_load_options',	'lighthouseschool_trx_addons_default_components');
			add_filter( 'trx_addons_cpt_list_options',		'lighthouseschool_trx_addons_cpt_list_options', 10, 2);
		}
	}
}

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('lighthouseschool_trx_addons_theme_setup9')) {
	add_action( 'after_setup_theme', 'lighthouseschool_trx_addons_theme_setup9', 9 );
	function lighthouseschool_trx_addons_theme_setup9() {
		if (lighthouseschool_exists_trx_addons()) {
			add_filter( 'trx_addons_filter_featured_image',				'lighthouseschool_trx_addons_featured_image', 10, 2);
			add_filter( 'trx_addons_filter_no_image',					'lighthouseschool_trx_addons_no_image' );
			add_filter( 'trx_addons_filter_get_list_icons',				'lighthouseschool_trx_addons_get_list_icons', 10, 2 );
			add_action( 'wp_enqueue_scripts', 							'lighthouseschool_trx_addons_frontend_scripts', 1100 );
			add_filter( 'lighthouseschool_filter_query_sort_order',	 			'lighthouseschool_trx_addons_query_sort_order', 10, 3);
			add_filter( 'lighthouseschool_filter_merge_scripts',					'lighthouseschool_trx_addons_merge_scripts');
			add_filter( 'lighthouseschool_filter_prepare_css',					'lighthouseschool_trx_addons_prepare_css', 10, 2);
			add_filter( 'lighthouseschool_filter_prepare_js',					'lighthouseschool_trx_addons_prepare_js', 10, 2);
			add_filter( 'lighthouseschool_filter_localize_script',				'lighthouseschool_trx_addons_localize_script');
			add_filter( 'lighthouseschool_filter_get_post_categories',		 	'lighthouseschool_trx_addons_get_post_categories');
			add_filter( 'lighthouseschool_filter_get_post_date',		 			'lighthouseschool_trx_addons_get_post_date');
			add_filter( 'trx_addons_filter_get_post_date',		 		'lighthouseschool_trx_addons_get_post_date_wrap');
			add_filter( 'lighthouseschool_filter_post_type_taxonomy',			'lighthouseschool_trx_addons_post_type_taxonomy', 10, 2 );
			if (is_admin()) {
				add_filter( 'lighthouseschool_filter_allow_override_options', 			'lighthouseschool_trx_addons_allow_override_options', 10, 2);
				add_filter( 'lighthouseschool_filter_allow_theme_icons', 		'lighthouseschool_trx_addons_allow_theme_icons', 10, 2);
			} else {
				add_filter( 'trx_addons_filter_theme_logo',				'lighthouseschool_trx_addons_theme_logo');
				add_filter( 'trx_addons_filter_post_meta',				'lighthouseschool_trx_addons_post_meta', 10, 2);
				add_filter( 'lighthouseschool_filter_post_meta_args',			'lighthouseschool_trx_addons_post_meta_args', 10, 3);
				add_filter( 'trx_addons_filter_args_related',			'lighthouseschool_trx_addons_args_related');
				add_filter( 'trx_addons_filter_seo_snippets',			'lighthouseschool_trx_addons_seo_snippets');
				add_action( 'trx_addons_action_before_article',			'lighthouseschool_trx_addons_before_article', 10, 1);
				add_filter( 'lighthouseschool_filter_get_mobile_menu',			'lighthouseschool_trx_addons_get_mobile_menu');
				add_filter( 'lighthouseschool_filter_detect_blog_mode',			'lighthouseschool_trx_addons_detect_blog_mode' );
				add_filter( 'lighthouseschool_filter_get_blog_title', 			'lighthouseschool_trx_addons_get_blog_title');
				add_action( 'lighthouseschool_action_login',						'lighthouseschool_trx_addons_action_login', 10, 2);
				add_action( 'lighthouseschool_action_search',					'lighthouseschool_trx_addons_action_search', 10, 3);
				add_action( 'lighthouseschool_action_breadcrumbs',				'lighthouseschool_trx_addons_action_breadcrumbs');
				add_action( 'lighthouseschool_action_show_layout',				'lighthouseschool_trx_addons_action_show_layout', 10, 1);
				add_filter( 'lighthouseschool_filter_get_translated_layout',		'lighthouseschool_trx_addons_filter_get_translated_layout', 10, 1);
				add_action( 'lighthouseschool_action_user_meta',					'lighthouseschool_trx_addons_action_user_meta');
				add_filter( 'trx_addons_filter_custom_meta_value_strip_tags', 'lighthouseschool_trx_addons_custom_meta_value_strip_tags' );
			}
		}
		
		// Add this filter any time: if plugin exists - load plugin's styles, if not exists - load layouts.css instead plugin's styles
		add_filter( 'lighthouseschool_filter_merge_styles',						'lighthouseschool_trx_addons_merge_styles');
		
		if (is_admin()) {
			add_filter( 'lighthouseschool_filter_tgmpa_required_plugins',		'lighthouseschool_trx_addons_tgmpa_required_plugins' );
			add_action( 'admin_enqueue_scripts', 						'lighthouseschool_trx_addons_editor_load_scripts_admin');
		}
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'lighthouseschool_trx_addons_tgmpa_required_plugins' ) ) {
	
	function lighthouseschool_trx_addons_tgmpa_required_plugins($list=array()) {
		if (lighthouseschool_storage_isset('required_plugins', 'trx_addons')) {
			$path = lighthouseschool_get_file_dir('plugins/trx_addons/trx_addons.zip');
			if (!empty($path) || lighthouseschool_get_theme_setting('tgmpa_upload')) {
				$list[] = array(
					'name' 		=> lighthouseschool_storage_get_array('required_plugins', 'trx_addons'),
					'slug' 		=> 'trx_addons',
					'version'	=> '1.71.34.8',
					'source'	=> !empty($path) ? $path : 'upload://trx_addons.zip',
					'required' 	=> true
				);
			}
		}
		return $list;
	}
}


/* Add options in the Theme Options Customizer
------------------------------------------------------------------------------- */

if (!function_exists('lighthouseschool_trx_addons_cpt_list_options')) {
	
	function lighthouseschool_trx_addons_cpt_list_options($options, $cpt) {
		if (is_array($options)) {
			foreach ($options as $k=>$v) {
				// Store this option in the external (not theme's) storage
				$options[$k]['options_storage'] = 'trx_addons_options';
				// Hide this option from plugin's options (only for overriden options)
				$options[$k]['hidden'] = in_array($cpt, array('cars', 'cars_agents', 'courses', 'dishes', 'properties', 'agents', 'sport'));
			}
		}
		return $options;
	}
}

// Return plugin's specific options for CPT
if (!function_exists('lighthouseschool_trx_addons_get_list_cpt_options')) {
	function lighthouseschool_trx_addons_get_list_cpt_options($cpt) {
		$options = array();
		if ($cpt == 'cars')
			$options = array_merge(
						trx_addons_cpt_cars_agents_get_list_options(),
						trx_addons_cpt_cars_get_list_options()
						);
		else if ($cpt == 'courses')
			$options = trx_addons_cpt_courses_get_list_options();
		else if ($cpt == 'dishes')
			$options = trx_addons_cpt_dishes_get_list_options();
		else if ($cpt == 'portfolio')
			$options = trx_addons_cpt_portfolio_get_list_options();
		else if ($cpt == 'services')
			$options = trx_addons_cpt_services_get_list_options();
		else if ($cpt == 'properties')
			$options = array_merge(
						trx_addons_cpt_agents_get_list_options(),
						trx_addons_cpt_properties_get_list_options()
						);
		else if ($cpt == 'sport')
			$options = trx_addons_cpt_sport_get_list_options();
		// Remove parameter 'hidden'
		foreach ($options as $k=>$v) {
			if (!empty($v['hidden']))
				unset($options[$k]['hidden']);
		}
		return $options;
	}
}

// Theme init priorities:
// 3 - add/remove Theme Options elements
if (!function_exists('lighthouseschool_trx_addons_setup3')) {
	add_action( 'after_setup_theme', 'lighthouseschool_trx_addons_setup3', 3 );
	function lighthouseschool_trx_addons_setup3() {
		
		// Section 'Cars' - settings to show 'Cars' blog archive and single posts
		if (lighthouseschool_exists_cars()) {
			lighthouseschool_storage_merge_array('options', '', array_merge(
				array(
					'cars' => array(
						"title" => esc_html__('Cars', 'lighthouseschool'),
						"desc" => wp_kses_data( __('Select parameters to display the cars pages.', 'lighthouseschool') )
									. '<br>'
									. wp_kses_data( __('Attention! Option "Style" apply only after you save the options!', 'lighthouseschool') ),
						"type" => "section"
						)
				),
				lighthouseschool_trx_addons_get_list_cpt_options('cars'),
				lighthouseschool_options_get_list_cpt_options('cars'),
				array(
					"single_info_cars" => array(
						"title" => esc_html__('Single car', 'lighthouseschool'),
						"desc" => '',
						"type" => "info",
						),
					'show_related_posts_cars' => array(
						"title" => esc_html__('Show related posts', 'lighthouseschool'),
						"desc" => wp_kses_data( __("Show section 'Related cars' on the single car page", 'lighthouseschool') ),
						"std" => 1,
						"type" => "checkbox"
						),
					'related_posts_cars' => array(
						"title" => esc_html__('Related cars', 'lighthouseschool'),
						"desc" => wp_kses_data( __('How many related cars should be displayed on the single car page?', 'lighthouseschool') ),
						"dependency" => array(
							'show_related_posts_cars' => array(1)
						),
						"std" => 3,
						"options" => lighthouseschool_get_list_range(1,9),
						"type" => "select"
						),
					'related_columns_cars' => array(
						"title" => esc_html__('Related columns', 'lighthouseschool'),
						"desc" => wp_kses_data( __('How many columns should be used to output related cars on the single car page?', 'lighthouseschool') ),
						"dependency" => array(
							'show_related_posts_cars' => array(1)
						),
						"std" => 3,
						"options" => lighthouseschool_get_list_range(1,4),
						"type" => "select"
						)
				)
			));
		}
		
		// Section 'Courses' - settings to show 'Courses' blog archive and single posts
		if (lighthouseschool_exists_courses()) {
		
			lighthouseschool_storage_merge_array('options', '', array_merge(
				array(
					'courses' => array(
						"title" => esc_html__('Courses', 'lighthouseschool'),
						"desc" => wp_kses_data( __('Select parameters to display the courses pages', 'lighthouseschool') )
									. '<br>'
									. wp_kses_data( __('Attention! Option "Style" apply only after you save the options!', 'lighthouseschool') ),
						"type" => "section"
						)
				),
				lighthouseschool_trx_addons_get_list_cpt_options('courses'),
				lighthouseschool_options_get_list_cpt_options('courses'),
				array(
					"single_info_courses" => array(
						"title" => esc_html__('Single course', 'lighthouseschool'),
						"desc" => '',
						"type" => "info",
						),
					'show_related_posts_courses' => array(
						"title" => esc_html__('Show related posts', 'lighthouseschool'),
						"desc" => wp_kses_data( __("Show section 'Related courses' on the single course page", 'lighthouseschool') ),
						"std" => 1,
						"type" => "checkbox"
						),
					'related_posts_courses' => array(
						"title" => esc_html__('Related courses', 'lighthouseschool'),
						"desc" => wp_kses_data( __('How many related courses should be displayed on the single course page?', 'lighthouseschool') ),
						"dependency" => array(
							'show_related_posts_courses' => array(1)
						),
						"std" => 3,
						"options" => lighthouseschool_get_list_range(1,9),
						"type" => "select"
						),
					'related_columns_courses' => array(
						"title" => esc_html__('Related columns', 'lighthouseschool'),
						"desc" => wp_kses_data( __('How many columns should be used to output related courses on the single course page?', 'lighthouseschool') ),
						"dependency" => array(
							'show_related_posts_courses' => array(1)
						),
						"std" => 3,
						"options" => lighthouseschool_get_list_range(1,4),
						"type" => "select"
						)
				)
			));
		}
		
		// Section 'Dishes' - settings to show 'Dishes' blog archive and single posts
		if (lighthouseschool_exists_dishes()) {

			lighthouseschool_storage_merge_array('options', '', array_merge(
				array(
					'dishes' => array(
						"title" => esc_html__('Dishes', 'lighthouseschool'),
						"desc" => wp_kses_data( __('Select parameters to display the dishes pages', 'lighthouseschool') )
									. '<br>'
									. wp_kses_data( __('Attention! Option "Style" apply only after you save the options!', 'lighthouseschool') ),
						"type" => "section"
						)
				),
				lighthouseschool_trx_addons_get_list_cpt_options('dishes'),
				lighthouseschool_options_get_list_cpt_options('dishes'),
				array(
					"single_info_dishes" => array(
						"title" => esc_html__('Single dish', 'lighthouseschool'),
						"desc" => '',
						"type" => "info",
						),
					'show_related_posts_dishes' => array(
						"title" => esc_html__('Show related posts', 'lighthouseschool'),
						"desc" => wp_kses_data( __("Show section 'Related dishes' on the single dish page", 'lighthouseschool') ),
						"std" => 1,
						"type" => "checkbox"
						),
					'related_posts_dishes' => array(
						"title" => esc_html__('Related dishes', 'lighthouseschool'),
						"desc" => wp_kses_data( __('How many related dishes should be displayed on the single dish page?', 'lighthouseschool') ),
						"dependency" => array(
							'show_related_posts_dishes' => array(1)
						),
						"std" => 3,
						"options" => lighthouseschool_get_list_range(1,9),
						"type" => "select"
						),
					'related_columns_dishes' => array(
						"title" => esc_html__('Related columns', 'lighthouseschool'),
						"desc" => wp_kses_data( __('How many columns should be used to output related dishes on the single dish page?', 'lighthouseschool') ),
						"dependency" => array(
							'show_related_posts_dishes' => array(1)
						),
						"std" => 3,
						"options" => lighthouseschool_get_list_range(1,4),
						"type" => "select"
						)
					)
				)
			);
		}
		
		// Section 'Portfolio' - settings to show 'Portfolio' blog archive and single posts
		if (lighthouseschool_exists_portfolio()) {
			lighthouseschool_storage_merge_array('options', '', array_merge(
				array(
					'portfolio' => array(
						"title" => esc_html__('Portfolio', 'lighthouseschool'),
						"desc" => wp_kses_data( __('Select parameters to display the portfolio pages', 'lighthouseschool') )
									. '<br>'
									. wp_kses_data( __('Attention! Option "Style" apply only after you save the options!', 'lighthouseschool') ),
						"type" => "section"
						)
				),
				lighthouseschool_trx_addons_get_list_cpt_options('portfolio'),
				lighthouseschool_options_get_list_cpt_options('portfolio'),
				array(
					"single_info_portfolio" => array(
						"title" => esc_html__('Single portfolio item', 'lighthouseschool'),
						"desc" => '',
						"type" => "info",
						),
					'show_related_posts_portfolio' => array(
						"title" => esc_html__('Show related posts', 'lighthouseschool'),
						"desc" => wp_kses_data( __("Show section 'Related portfolio items' on the single portfolio page", 'lighthouseschool') ),
						"std" => 1,
						"type" => "checkbox"
						),
					'related_posts_portfolio' => array(
						"title" => esc_html__('Related portfolio items', 'lighthouseschool'),
						"desc" => wp_kses_data( __('How many related portfolio items should be displayed on the single portfolio page?', 'lighthouseschool') ),
						"dependency" => array(
							'show_related_posts_portfolio' => array(1)
						),
						"std" => 3,
						"options" => lighthouseschool_get_list_range(1,9),
						"type" => "select"
						),
					'related_columns_portfolio' => array(
						"title" => esc_html__('Related columns', 'lighthouseschool'),
						"desc" => wp_kses_data( __('How many columns should be used to output related portfolio on the single portfolio page?', 'lighthouseschool') ),
						"dependency" => array(
							'show_related_posts_portfolio' => array(1)
						),
						"std" => 3,
						"options" => lighthouseschool_get_list_range(1,4),
						"type" => "select"
						)
				)
			));
		}
		
		// Section 'Properties' - settings to show 'Properties' blog archive and single posts
		if (lighthouseschool_exists_properties()) {
		
			lighthouseschool_storage_merge_array('options', '', array_merge(
				array(
					'properties' => array(
						"title" => esc_html__('Properties', 'lighthouseschool'),
						"desc" => wp_kses_data( __('Select parameters to display the properties pages', 'lighthouseschool') )
									. '<br>'
									. wp_kses_data( __('Attention! Option "Style" apply only after you save the options!', 'lighthouseschool') ),
						"type" => "section"
						)
				),
				lighthouseschool_trx_addons_get_list_cpt_options('properties'),
				lighthouseschool_options_get_list_cpt_options('properties'),
				array(
					"single_info_properties" => array(
						"title" => esc_html__('Single property', 'lighthouseschool'),
						"desc" => '',
						"type" => "info",
						),
					'show_related_posts_properties' => array(
						"title" => esc_html__('Show related posts', 'lighthouseschool'),
						"desc" => wp_kses_data( __("Show section 'Related properties' on the single property page", 'lighthouseschool') ),
						"std" => 1,
						"type" => "checkbox"
						),
					'related_posts_properties' => array(
						"title" => esc_html__('Related properties', 'lighthouseschool'),
						"desc" => wp_kses_data( __('How many related properties should be displayed on the single property page?', 'lighthouseschool') ),
						"dependency" => array(
							'show_related_posts_properties' => array(1)
						),
						"std" => 3,
						"options" => lighthouseschool_get_list_range(1,9),
						"type" => "select"
						),
					'related_columns_properties' => array(
						"title" => esc_html__('Related columns', 'lighthouseschool'),
						"desc" => wp_kses_data( __('How many columns should be used to output related properties on the single property page?', 'lighthouseschool') ),
						"dependency" => array(
							'show_related_posts_properties' => array(1)
						),
						"std" => 3,
						"options" => lighthouseschool_get_list_range(1,4),
						"type" => "select"
						)
					)
				)
			);
		}
		
		// Section 'Services' - settings to show 'Services' blog archive and single posts
		if (lighthouseschool_exists_services()) {
		
			lighthouseschool_storage_merge_array('options', '', array_merge(
				array(
					'services' => array(
						"title" => esc_html__('Services', 'lighthouseschool'),
						"desc" => wp_kses_data( __('Select parameters to display the services pages', 'lighthouseschool') )
									. '<br>'
									. wp_kses_data( __('Attention! Option "Style" apply only after you save the options!', 'lighthouseschool') ),
						"type" => "section"
						)
				),
				lighthouseschool_trx_addons_get_list_cpt_options('services'),
				lighthouseschool_options_get_list_cpt_options('services'),
				array(
					"single_info_services" => array(
						"title" => esc_html__('Single service item', 'lighthouseschool'),
						"desc" => '',
						"type" => "info",
						),
					'show_related_posts_services' => array(
						"title" => esc_html__('Show related posts', 'lighthouseschool'),
						"desc" => wp_kses_data( __("Show section 'Related services' on the single service page", 'lighthouseschool') ),
						"std" => 0,
						"type" => "checkbox"
						),
					'related_posts_services' => array(
						"title" => esc_html__('Related services', 'lighthouseschool'),
						"desc" => wp_kses_data( __('How many related services should be displayed on the single service page?', 'lighthouseschool') ),
						"dependency" => array(
							'show_related_posts_services' => array(1)
						),
						"std" => 3,
						"options" => lighthouseschool_get_list_range(1,9),
						"type" => "select"
						),
					'related_columns_services' => array(
						"title" => esc_html__('Related columns', 'lighthouseschool'),
						"desc" => wp_kses_data( __('How many columns should be used to output related services on the single service page?', 'lighthouseschool') ),
						"dependency" => array(
							'show_related_posts_services' => array(1)
						),
						"std" => 3,
						"options" => lighthouseschool_get_list_range(1,4),
						"type" => "select"
						)
				)
			));
		}
		
		// Section 'Sport' - settings to show 'Sport' blog archive and single posts
		if (lighthouseschool_exists_sport()) {
			lighthouseschool_storage_merge_array('options', '', array_merge(
				array(
					'sport' => array(
						"title" => esc_html__('Sport', 'lighthouseschool'),
						"desc" => wp_kses_data( __('Select parameters to display the sport pages', 'lighthouseschool') )
									. '<br>'
									. wp_kses_data( __('Attention! Option "Style" apply only after you save the options!', 'lighthouseschool') ),
						"type" => "section"
						)
				),
				lighthouseschool_trx_addons_get_list_cpt_options('sport'),
				lighthouseschool_options_get_list_cpt_options('sport')
			));
		}
	}
}


// Setup internal plugin's parameters
if (!function_exists('lighthouseschool_trx_addons_init_settings')) {
	add_filter( 'trx_addons_init_settings', 'lighthouseschool_trx_addons_init_settings');
	function lighthouseschool_trx_addons_init_settings($settings) {
		$settings['socials_type']	= lighthouseschool_get_theme_setting('socials_type');
		$settings['icons_type']		= lighthouseschool_get_theme_setting('icons_type');
		$settings['icons_selector']	= lighthouseschool_get_theme_setting('icons_selector');
		return $settings;
	}
}



/* Plugin's support utilities
------------------------------------------------------------------------------- */

// Check if plugin installed and activated
if ( !function_exists( 'lighthouseschool_exists_trx_addons' ) ) {
	function lighthouseschool_exists_trx_addons() {
		return defined('TRX_ADDONS_VERSION');
	}
}

// Return true if cars is supported
if ( !function_exists( 'lighthouseschool_exists_cars' ) ) {
	function lighthouseschool_exists_cars() {
		return defined('TRX_ADDONS_CPT_CARS_PT');
	}
}

// Return true if courses is supported
if ( !function_exists( 'lighthouseschool_exists_courses' ) ) {
	function lighthouseschool_exists_courses() {
		return defined('TRX_ADDONS_CPT_COURSES_PT');
	}
}

// Return true if dishes is supported
if ( !function_exists( 'lighthouseschool_exists_dishes' ) ) {
	function lighthouseschool_exists_dishes() {
		return defined('TRX_ADDONS_CPT_DISHES_PT');
	}
}

// Return true if layouts is supported
if ( !function_exists( 'lighthouseschool_exists_layouts' ) ) {
	function lighthouseschool_exists_layouts() {
		return defined('TRX_ADDONS_CPT_LAYOUTS_PT');
	}
}

// Return true if portfolio is supported
if ( !function_exists( 'lighthouseschool_exists_portfolio' ) ) {
	function lighthouseschool_exists_portfolio() {
		return defined('TRX_ADDONS_CPT_PORTFOLIO_PT');
	}
}

// Return true if properties is supported
if ( !function_exists( 'lighthouseschool_exists_properties' ) ) {
	function lighthouseschool_exists_properties() {
		return defined('TRX_ADDONS_CPT_PROPERTIES_PT');
	}
}

// Return true if services is supported
if ( !function_exists( 'lighthouseschool_exists_services' ) ) {
	function lighthouseschool_exists_services() {
		return defined('TRX_ADDONS_CPT_SERVICES_PT');
	}
}

// Return true if sport is supported
if ( !function_exists( 'lighthouseschool_exists_sport' ) ) {
	function lighthouseschool_exists_sport() {
		return defined('TRX_ADDONS_CPT_COMPETITIONS_PT');
	}
}

// Return true if team is supported
if ( !function_exists( 'lighthouseschool_exists_team' ) ) {
	function lighthouseschool_exists_team() {
		return defined('TRX_ADDONS_CPT_TEAM_PT');
	}
}


// Return true if it's cars page
if ( !function_exists( 'lighthouseschool_is_cars_page' ) ) {
	function lighthouseschool_is_cars_page() {
		return function_exists('trx_addons_is_cars_page') && trx_addons_is_cars_page();
	}
}

// Return true if it's courses page
if ( !function_exists( 'lighthouseschool_is_courses_page' ) ) {
	function lighthouseschool_is_courses_page() {
		return function_exists('trx_addons_is_courses_page') && trx_addons_is_courses_page();
	}
}

// Return true if it's dishes page
if ( !function_exists( 'lighthouseschool_is_dishes_page' ) ) {
	function lighthouseschool_is_dishes_page() {
		return function_exists('trx_addons_is_dishes_page') && trx_addons_is_dishes_page();
	}
}

// Return true if it's properties page
if ( !function_exists( 'lighthouseschool_is_properties_page' ) ) {
	function lighthouseschool_is_properties_page() {
		return function_exists('trx_addons_is_properties_page') && trx_addons_is_properties_page();
	}
}

// Return true if it's portfolio page
if ( !function_exists( 'lighthouseschool_is_portfolio_page' ) ) {
	function lighthouseschool_is_portfolio_page() {
		return function_exists('trx_addons_is_portfolio_page') && trx_addons_is_portfolio_page();
	}
}

// Return true if it's services page
if ( !function_exists( 'lighthouseschool_is_services_page' ) ) {
	function lighthouseschool_is_services_page() {
		return function_exists('trx_addons_is_services_page') && trx_addons_is_services_page();
	}
}

// Return true if it's team page
if ( !function_exists( 'lighthouseschool_is_team_page' ) ) {
	function lighthouseschool_is_team_page() {
		return function_exists('trx_addons_is_team_page') && trx_addons_is_team_page();
	}
}

// Return true if it's sport page
if ( !function_exists( 'lighthouseschool_is_sport_page' ) ) {
	function lighthouseschool_is_sport_page() {
		return function_exists('trx_addons_is_sport_page') && trx_addons_is_sport_page();
	}
}

// Return true if custom layouts are available
if ( !function_exists( 'lighthouseschool_is_layouts_available' ) ) {
	function lighthouseschool_is_layouts_available() {
		return lighthouseschool_exists_trx_addons() 
										&& (
											function_exists('lighthouseschool_exists_sop') && lighthouseschool_exists_sop()
											||
											function_exists('lighthouseschool_exists_visual_composer') && lighthouseschool_exists_visual_composer()
											);
	}
}

// Detect current blog mode
if ( !function_exists( 'lighthouseschool_trx_addons_detect_blog_mode' ) ) {
	
	function lighthouseschool_trx_addons_detect_blog_mode($mode='') {
		if ( lighthouseschool_is_cars_page() )
			$mode = 'cars';
		else if ( lighthouseschool_is_courses_page() )
			$mode = 'courses';
		else if ( lighthouseschool_is_dishes_page() )
			$mode = 'dishes';
		else if ( lighthouseschool_is_properties_page() )
			$mode = 'properties';
		else if ( lighthouseschool_is_portfolio_page() )
			$mode = 'portfolio';
		else if ( lighthouseschool_is_services_page() )
			$mode = 'services';
		else if ( lighthouseschool_is_sport_page() )
			$mode = 'sport';
		else if ( lighthouseschool_is_team_page() )
			$mode = 'team';
		return $mode;
	}
}

// Add team, courses, etc. to the supported posts list
if ( !function_exists( 'lighthouseschool_trx_addons_list_post_types' ) ) {
	
	function lighthouseschool_trx_addons_list_post_types($list=array()) {
		if (function_exists('trx_addons_get_cpt_list')) {
			$cpt_list = trx_addons_get_cpt_list();
			foreach ($cpt_list as $cpt => $title) {
				if (   
					   (defined('TRX_ADDONS_CPT_CARS_PT') && $cpt == TRX_ADDONS_CPT_CARS_PT)
					|| (defined('TRX_ADDONS_CPT_COURSES_PT') && $cpt == TRX_ADDONS_CPT_COURSES_PT)
					|| (defined('TRX_ADDONS_CPT_DISHES_PT') && $cpt == TRX_ADDONS_CPT_DISHES_PT)
					|| (defined('TRX_ADDONS_CPT_PORTFOLIO_PT') && $cpt == TRX_ADDONS_CPT_PORTFOLIO_PT)
					|| (defined('TRX_ADDONS_CPT_PROPERTIES_PT') && $cpt == TRX_ADDONS_CPT_PROPERTIES_PT)
					|| (defined('TRX_ADDONS_CPT_SERVICES_PT') && $cpt == TRX_ADDONS_CPT_SERVICES_PT)
					|| (defined('TRX_ADDONS_CPT_COMPETITIONS_PT') && $cpt == TRX_ADDONS_CPT_COMPETITIONS_PT)
					)
					$list[$cpt] = $title;
			}
		}
		return $list;
	}
}

// Return taxonomy for current post type
if ( !function_exists( 'lighthouseschool_trx_addons_post_type_taxonomy' ) ) {
	
	function lighthouseschool_trx_addons_post_type_taxonomy($tax='', $post_type='') {
		if ( defined('TRX_ADDONS_CPT_CARS_PT') && $post_type == TRX_ADDONS_CPT_CARS_PT )
			$tax = TRX_ADDONS_CPT_CARS_TAXONOMY_MAKER;
		else if ( defined('TRX_ADDONS_CPT_COURSES_PT') && $post_type == TRX_ADDONS_CPT_COURSES_PT )
			$tax = TRX_ADDONS_CPT_COURSES_TAXONOMY;
		else if ( defined('TRX_ADDONS_CPT_DISHES_PT') && $post_type == TRX_ADDONS_CPT_DISHES_PT )
			$tax = TRX_ADDONS_CPT_DISHES_TAXONOMY;
		else if ( defined('TRX_ADDONS_CPT_PORTFOLIO_PT') && $post_type == TRX_ADDONS_CPT_PORTFOLIO_PT )
			$tax = TRX_ADDONS_CPT_PORTFOLIO_TAXONOMY;
		else if ( defined('TRX_ADDONS_CPT_PROPERTIES_PT') && $post_type == TRX_ADDONS_CPT_PROPERTIES_PT )
			$tax = TRX_ADDONS_CPT_PROPERTIES_TAXONOMY_TYPE;
		else if ( defined('TRX_ADDONS_CPT_SERVICES_PT') && $post_type == TRX_ADDONS_CPT_SERVICES_PT )
			$tax = TRX_ADDONS_CPT_SERVICES_TAXONOMY;
		else if ( defined('TRX_ADDONS_CPT_COMPETITIONS_PT') && $post_type == TRX_ADDONS_CPT_COMPETITIONS_PT )
			$tax = TRX_ADDONS_CPT_COMPETITIONS_TAXONOMY;
		else if ( defined('TRX_ADDONS_CPT_TEAM_PT') && $post_type == TRX_ADDONS_CPT_TEAM_PT )
			$tax = TRX_ADDONS_CPT_TEAM_TAXONOMY;
		return $tax;
	}
}

// Show categories of the team, courses, etc.
if ( !function_exists( 'lighthouseschool_trx_addons_get_post_categories' ) ) {
	
	function lighthouseschool_trx_addons_get_post_categories($cats='') {

		if ( defined('TRX_ADDONS_CPT_CARS_PT') ) {
			if (get_post_type()==TRX_ADDONS_CPT_CARS_PT) {
				$cats = lighthouseschool_get_post_terms(', ', get_the_ID(), TRX_ADDONS_CPT_CARS_TAXONOMY_TYPE);
			}
		}
		if ( defined('TRX_ADDONS_CPT_COURSES_PT') ) {
			if (get_post_type()==TRX_ADDONS_CPT_COURSES_PT) {
				$cats = lighthouseschool_get_post_terms(', ', get_the_ID(), TRX_ADDONS_CPT_COURSES_TAXONOMY);
			}
		}
		if ( defined('TRX_ADDONS_CPT_DISHES_PT') ) {
			if (get_post_type()==TRX_ADDONS_CPT_DISHES_PT) {
				$cats = lighthouseschool_get_post_terms(', ', get_the_ID(), TRX_ADDONS_CPT_DISHES_TAXONOMY);
			}
		}
		if ( defined('TRX_ADDONS_CPT_PORTFOLIO_PT') ) {
			if (get_post_type()==TRX_ADDONS_CPT_PORTFOLIO_PT) {
				$cats = lighthouseschool_get_post_terms(', ', get_the_ID(), TRX_ADDONS_CPT_PORTFOLIO_TAXONOMY);
			}
		}
		if ( defined('TRX_ADDONS_CPT_PROPERTIES_PT') ) {
			if (get_post_type()==TRX_ADDONS_CPT_PROPERTIES_PT) {
				$cats = lighthouseschool_get_post_terms(', ', get_the_ID(), TRX_ADDONS_CPT_PROPERTIES_TAXONOMY_TYPE);
			}
		}
		if ( defined('TRX_ADDONS_CPT_SERVICES_PT') ) {
			if (get_post_type()==TRX_ADDONS_CPT_SERVICES_PT) {
				$cats = lighthouseschool_get_post_terms(', ', get_the_ID(), TRX_ADDONS_CPT_SERVICES_TAXONOMY);
			}
		}
		if ( defined('TRX_ADDONS_CPT_COMPETITIONS_PT') ) {
			if (get_post_type()==TRX_ADDONS_CPT_COMPETITIONS_PT) {
				$cats = lighthouseschool_get_post_terms(', ', get_the_ID(), TRX_ADDONS_CPT_COMPETITIONS_TAXONOMY);
			}
		}
		if ( defined('TRX_ADDONS_CPT_TEAM_PT') ) {
			if (get_post_type()==TRX_ADDONS_CPT_TEAM_PT) {
				$cats = lighthouseschool_get_post_terms(', ', get_the_ID(), TRX_ADDONS_CPT_TEAM_TAXONOMY);
			}
		}
		return $cats;
	}
}

// Show post's date with the theme-specific format
if ( !function_exists( 'lighthouseschool_trx_addons_get_post_date_wrap' ) ) {
	
	function lighthouseschool_trx_addons_get_post_date_wrap($dt='') {
		return apply_filters('lighthouseschool_filter_get_post_date', $dt);
	}
}

// Show date of the courses
if ( !function_exists( 'lighthouseschool_trx_addons_get_post_date' ) ) {
	
	function lighthouseschool_trx_addons_get_post_date($dt='') {

		if ( defined('TRX_ADDONS_CPT_COURSES_PT') && get_post_type()==TRX_ADDONS_CPT_COURSES_PT) {
			$meta = get_post_meta(get_the_ID(), 'trx_addons_options', true);
			$dt = $meta['date'];
			$dt = sprintf($dt < date('Y-m-d') 
					? esc_html__('Started on %s', 'lighthouseschool') 
					: esc_html__('Starting %s', 'lighthouseschool'), 
					date(get_option('date_format'), strtotime($dt)));

		} else if ( defined('TRX_ADDONS_CPT_COMPETITIONS_PT') && in_array(get_post_type(), array(TRX_ADDONS_CPT_COMPETITIONS_PT, TRX_ADDONS_CPT_ROUNDS_PT, TRX_ADDONS_CPT_MATCHES_PT))) {
			$meta = get_post_meta(get_the_ID(), 'trx_addons_options', true);
			$dt = $meta['date_start'];
			$dt = sprintf($dt < date('Y-m-d').(!empty($meta['time_start']) ? ' H:i' : '')
					? esc_html__('Started on %s', 'lighthouseschool') 
					: esc_html__('Starting %s', 'lighthouseschool'), 
					date(get_option('date_format') . (!empty($meta['time_start']) ? ' '.get_option('time_format') : ''), strtotime($dt.(!empty($meta['time_start']) ? ' '.trim($meta['time_start']) : ''))));

		} else if ( defined('TRX_ADDONS_CPT_COMPETITIONS_PT') && get_post_type() == TRX_ADDONS_CPT_PLAYERS_PT) {
			if (false) {
				$meta = get_post_meta(get_the_ID(), 'trx_addons_options', true);
				$dt = !empty($meta['birthday']) ? sprintf(esc_html__('Birthday: %s', 'lighthouseschool'), date(get_option('date_format'), strtotime($meta['birthday']))) : '';
			} else
				$dt = '';
		}
		return $dt;
	}
}

// Disable strip tags from the price
if ( ! function_exists( 'lighthouseschool_trx_addons_custom_meta_value_strip_tags' ) ) {
	// Handler of the add_filter( 'trx_addons_filter_custom_meta_value_strip_tags', 'lighthouseschool_trx_addons_custom_meta_value_strip_tags' );
	function lighthouseschool_trx_addons_custom_meta_value_strip_tags( $keys ) {
		return is_array( $keys ) ? lighthouseschool_array_delete_by_value( $keys, 'price' ) : $keys;
	}
}

// Check if override options is allowed
if (!function_exists('lighthouseschool_trx_addons_allow_override_options')) {
	
	function lighthouseschool_trx_addons_allow_override_options($allow, $post_type) {
		return $allow
					|| (defined('TRX_ADDONS_CPT_CARS_PT') && in_array($post_type, array(
																				TRX_ADDONS_CPT_CARS_PT,
																				TRX_ADDONS_CPT_CARS_AGENTS_PT
																				)))
					|| (defined('TRX_ADDONS_CPT_COURSES_PT') && $post_type==TRX_ADDONS_CPT_COURSES_PT)
					|| (defined('TRX_ADDONS_CPT_DISHES_PT') && $post_type==TRX_ADDONS_CPT_DISHES_PT)
					|| (defined('TRX_ADDONS_CPT_PORTFOLIO_PT') && $post_type==TRX_ADDONS_CPT_PORTFOLIO_PT) 
					|| (defined('TRX_ADDONS_CPT_PROPERTIES_PT') && in_array($post_type, array(
																				TRX_ADDONS_CPT_PROPERTIES_PT,
																				TRX_ADDONS_CPT_AGENTS_PT
																				)))
					|| (defined('TRX_ADDONS_CPT_RESUME_PT') && $post_type==TRX_ADDONS_CPT_RESUME_PT) 
					|| (defined('TRX_ADDONS_CPT_SERVICES_PT') && $post_type==TRX_ADDONS_CPT_SERVICES_PT) 
					|| (defined('TRX_ADDONS_CPT_COMPETITIONS_PT') && in_array($post_type, array(
																				TRX_ADDONS_CPT_COMPETITIONS_PT,
																				TRX_ADDONS_CPT_ROUNDS_PT,
																				TRX_ADDONS_CPT_MATCHES_PT
																				)))
					|| (defined('TRX_ADDONS_CPT_TEAM_PT') && $post_type==TRX_ADDONS_CPT_TEAM_PT);
	}
}

// Check if theme icons is allowed
if (!function_exists('lighthouseschool_trx_addons_allow_theme_icons')) {
	
	function lighthouseschool_trx_addons_allow_theme_icons($allow, $post_type) {
		$screen = function_exists('get_current_screen') ? get_current_screen() : false;
		return $allow
					|| (defined('TRX_ADDONS_CPT_LAYOUTS_PT') && $post_type==TRX_ADDONS_CPT_LAYOUTS_PT)
					|| (!empty($screen->id) && in_array($screen->id, array(
																		'appearance_page_trx_addons_options',
																		'profile'
																	)
														)
						);
	}
}

// Set related posts and columns for the plugin's output
if (!function_exists('lighthouseschool_trx_addons_args_related')) {
	
	function lighthouseschool_trx_addons_args_related($args) {
		if (!empty($args['template_args_name']) 
			&& in_array($args['template_args_name'], 
				array('trx_addons_args_sc_cars', 
					  'trx_addons_args_sc_courses',
					  'trx_addons_args_sc_dishes',
					  'trx_addons_args_sc_portfolio',
					  'trx_addons_args_sc_properties',
  					  'trx_addons_args_sc_services'))) {
			$args['posts_per_page'] = (int) lighthouseschool_get_theme_option('show_related_posts')
												? lighthouseschool_get_theme_option('related_posts')
												: 0;
			$args['columns'] = lighthouseschool_get_theme_option('related_columns');
		}
		return $args;
	}
}

// Add layouts to the headers list
if ( !function_exists( 'lighthouseschool_trx_addons_list_header_styles' ) ) {
	
	function lighthouseschool_trx_addons_list_header_styles($list=array()) {
		if (lighthouseschool_exists_layouts()) {
			$layouts = lighthouseschool_get_list_posts(false, array(
							'post_type' => TRX_ADDONS_CPT_LAYOUTS_PT,
							'meta_key' => 'trx_addons_layout_type',
							'meta_value' => 'header',
							'orderby' => 'ID',
							'order' => 'asc',
							'not_selected' => false
							)
						);
			$new_list = array();
			foreach ($layouts as $id=>$title) {
				if ($id != 'none') $new_list['header-custom-'.intval($id)] = $title;
			}
			if (lighthouseschool_get_theme_setting('allow_theme_layouts'))
				$list = lighthouseschool_array_merge($new_list, $list);
			else
				$list = $new_list;
		}
		return $list;
	}
}

// Add layouts to the footers list
if ( !function_exists( 'lighthouseschool_trx_addons_list_footer_styles' ) ) {
	
	function lighthouseschool_trx_addons_list_footer_styles($list=array()) {
		if (lighthouseschool_exists_layouts()) {
			$layouts = lighthouseschool_get_list_posts(false, array(
							'post_type' => TRX_ADDONS_CPT_LAYOUTS_PT,
							'meta_key' => 'trx_addons_layout_type',
							'meta_value' => 'footer',
							'orderby' => 'ID',
							'order' => 'asc',
							'not_selected' => false
							)
						);
			$new_list = array();
			foreach ($layouts as $id=>$title) {
				if ($id != 'none') $new_list['footer-custom-'.intval($id)] = $title;
			}
			if (lighthouseschool_get_theme_setting('allow_theme_layouts'))
				$list = lighthouseschool_array_merge($new_list, $list);
			else
				$list = $new_list;
		}
		return $list;
	}
}


// Add theme-specific layouts to the list
if (!function_exists('lighthouseschool_trx_addons_default_layouts')) {
	
	function lighthouseschool_trx_addons_default_layouts($default_layouts=array()) {
		if (lighthouseschool_storage_isset('trx_addons_default_layouts')) {
			$layouts = lighthouseschool_storage_get('trx_addons_default_layouts');
		} else {
			require_once LIGHTHOUSESCHOOL_THEME_DIR . 'theme-specific/trx_addons.layouts.php';
			if (!isset($layouts) || !is_array($layouts))
				$layouts = array();
			lighthouseschool_storage_set('trx_addons_default_layouts', $layouts);
		}
		if (count($layouts) > 0)
			$default_layouts = array_merge($default_layouts, $layouts);
		return $default_layouts;
	}
}


// Add theme-specific components to the plugin's options
if (!function_exists('lighthouseschool_trx_addons_default_components')) {
	
	function lighthouseschool_trx_addons_default_components($options=array()) {
		if (empty($options['components_present'])) {
			if (lighthouseschool_storage_isset('trx_addons_default_components')) {
				$components = lighthouseschool_storage_get('trx_addons_default_components');
			} else {
				require_once LIGHTHOUSESCHOOL_THEME_DIR . 'theme-specific/trx_addons.components.php';
				if (!isset($components) || !is_array($components))
					$components = array();
				lighthouseschool_storage_set('trx_addons_default_components', $components);
			}
			$options = is_array($options) && count($components) > 0
									? array_merge($options, $components)
									: $components;
		}
		return $options;
	}
}


// Enqueue custom styles
if ( !function_exists( 'lighthouseschool_trx_addons_frontend_scripts' ) ) {
	
	function lighthouseschool_trx_addons_frontend_scripts() {
		if (lighthouseschool_exists_trx_addons()) {
			if (lighthouseschool_is_on(lighthouseschool_get_theme_option('debug_mode')) && lighthouseschool_get_file_dir('plugins/trx_addons/trx_addons.css')!='') {
				wp_enqueue_style( 'lighthouseschool-trx-addons',  lighthouseschool_get_file_url('plugins/trx_addons/trx_addons.css'), array(), null );
				wp_enqueue_style( 'lighthouseschool-trx-addons-editor',  lighthouseschool_get_file_url('plugins/trx_addons/trx_addons.editor.css'), array(), null );
			}
			if (lighthouseschool_is_on(lighthouseschool_get_theme_option('debug_mode')) && lighthouseschool_get_file_dir('plugins/trx_addons/trx_addons.js')!='')
				wp_enqueue_script( 'lighthouseschool-trx-addons', lighthouseschool_get_file_url('plugins/trx_addons/trx_addons.js'), array('jquery'), null, true );
		}
		// Load custom layouts from the theme if plugin not exists
		if (lighthouseschool_get_theme_option("header_style")=='header-default' || !lighthouseschool_is_layouts_available()) {
			if ( lighthouseschool_is_on(lighthouseschool_get_theme_option('debug_mode')) ) {
				wp_enqueue_style( 'lighthouseschool-layouts', lighthouseschool_get_file_url('plugins/trx_addons/layouts/layouts.css') );
				wp_enqueue_style( 'lighthouseschool-layouts-logo', lighthouseschool_get_file_url('plugins/trx_addons/layouts/logo.css') );
				wp_enqueue_style( 'lighthouseschool-layouts-menu', lighthouseschool_get_file_url('plugins/trx_addons/layouts/menu.css') );
				wp_enqueue_style( 'lighthouseschool-layouts-search', lighthouseschool_get_file_url('plugins/trx_addons/layouts/search.css') );
				wp_enqueue_style( 'lighthouseschool-layouts-title', lighthouseschool_get_file_url('plugins/trx_addons/layouts/title.css') );
				wp_enqueue_style( 'lighthouseschool-layouts-featured', lighthouseschool_get_file_url('plugins/trx_addons/layouts/featured.css') );
			}
		}
	}
}
	
// Merge custom styles
if ( !function_exists( 'lighthouseschool_trx_addons_merge_styles' ) ) {
	
	function lighthouseschool_trx_addons_merge_styles($list) {
		// ALWAYS merge custom layouts from the theme
		$list[] = 'plugins/trx_addons/layouts/layouts.css';
		$list[] = 'plugins/trx_addons/layouts/logo.css';
		$list[] = 'plugins/trx_addons/layouts/menu.css';
		$list[] = 'plugins/trx_addons/layouts/search.css';
		$list[] = 'plugins/trx_addons/layouts/title.css';
		$list[] = 'plugins/trx_addons/layouts/featured.css';
		if (lighthouseschool_exists_trx_addons()) {
			$list[] = 'plugins/trx_addons/trx_addons.css';
			$list[] = 'plugins/trx_addons/trx_addons.editor.css';
		}
		return $list;
	}
}
	
// Merge custom scripts
if ( !function_exists( 'lighthouseschool_trx_addons_merge_scripts' ) ) {
	
	function lighthouseschool_trx_addons_merge_scripts($list) {
		$list[] = 'plugins/trx_addons/trx_addons.js';
		return $list;
	}
}


// Return text for the "I agree ..." checkbox
if ( ! function_exists( 'lighthouseschool_trx_donations_privacy_text' ) ) {
	add_filter( 'trx_donations_filter_privacy_text', 'lighthouseschool_trx_donations_privacy_text', 30, 1 );
	function lighthouseschool_trx_donations_privacy_text( $text ) {
		return lighthouseschool_get_privacy_text();
	}
}


// Return text for the "I agree ..." checkbox
if ( ! function_exists( 'lighthouseschool_trx_addons_privacy_text' ) ) {
	add_filter( 'trx_addons_filter_privacy_text', 'lighthouseschool_trx_addons_privacy_text' );
	function lighthouseschool_trx_addons_privacy_text( $text='' ) {
		return lighthouseschool_get_privacy_text();
	}
}



// WP Editor addons
//------------------------------------------------------------------------

// Load required styles and scripts for admin mode
if ( !function_exists( 'lighthouseschool_trx_addons_editor_load_scripts_admin' ) ) {
	
	function lighthouseschool_trx_addons_editor_load_scripts_admin() {
		// Add styles in the WP text editor
		add_editor_style( array(
							lighthouseschool_get_file_url('plugins/trx_addons/trx_addons.editor.css')
							)
						 );	
	}
}



// Plugin API - theme-specific wrappers for plugin functions
//------------------------------------------------------------------------

// Debug functions wrappers
if (!function_exists('ddo')) { function ddo($obj, $level=-1) { return var_dump($obj); } }
if (!function_exists('dco')) { function dco($obj, $level=-1) { print_r($obj); } }
if (!function_exists('dcl')) { function dcl($msg, $level=-1) { echo '<br><pre>' . esc_html($msg) . '</pre><br>'; } }

// Check if URL contain specified string
if (!function_exists('lighthouseschool_check_url')) {
	function lighthouseschool_check_url($val='', $defa=false) {
		return function_exists('trx_addons_check_url') 
					? trx_addons_check_url($val) 
					: $defa;
	}
}

// Check if layouts components are showed or set new state
if (!function_exists('lighthouseschool_sc_layouts_showed')) {
	function lighthouseschool_sc_layouts_showed($name, $val=null) {
		if (function_exists('trx_addons_sc_layouts_showed')) {
			if ($val!==null)
				trx_addons_sc_layouts_showed($name, $val);
			else
				return trx_addons_sc_layouts_showed($name);
		} else {
			if ($val!==null)
				return lighthouseschool_storage_set_array('sc_layouts_components', $name, $val);
			else
				return lighthouseschool_storage_get_array('sc_layouts_components', $name);
		}
	}
}

// Return image size multiplier
if (!function_exists('lighthouseschool_get_retina_multiplier')) {
	function lighthouseschool_get_retina_multiplier($force_retina=0) {
		static $mult = 0;
		if ($mult == 0) $mult = function_exists('trx_addons_get_retina_multiplier') ? trx_addons_get_retina_multiplier($force_retina) : 1;
		return max(1, $mult);
	}
}

// Return slider layout
if (!function_exists('lighthouseschool_get_slider_layout')) {
	function lighthouseschool_get_slider_layout($args) {
		return function_exists('trx_addons_get_slider_layout') 
					? trx_addons_get_slider_layout($args) 
					: '';
	}
}

// Return video player layout
if (!function_exists('lighthouseschool_get_video_layout')) {
	function lighthouseschool_get_video_layout($args) {
		return function_exists('trx_addons_get_video_layout') 
					? trx_addons_get_video_layout($args) 
					: '';
	}
}

// Return theme specific layout of the featured image block
if ( !function_exists( 'lighthouseschool_trx_addons_featured_image' ) ) {
	
	function lighthouseschool_trx_addons_featured_image($processed=false, $args=array()) {
		$args['show_no_image'] = true;
		$args['singular'] = false;
		$args['hover'] = isset($args['hover']) && $args['hover']=='' ? '' : lighthouseschool_get_theme_option('image_hover');
		lighthouseschool_show_post_featured($args);
		return true;
	}
}

// Return theme specific 'no-image' picture
if ( !function_exists( 'lighthouseschool_trx_addons_no_image' ) ) {
	
	function lighthouseschool_trx_addons_no_image($no_image='') {
		return lighthouseschool_get_no_image($no_image);
	}
}

// Return theme-specific icons
if ( !function_exists( 'lighthouseschool_trx_addons_get_list_icons' ) ) {
	
	function lighthouseschool_trx_addons_get_list_icons($list, $prepend_inherit) {
		return lighthouseschool_get_list_icons($prepend_inherit);
	}
}

// Return links to the social profiles
if (!function_exists('lighthouseschool_get_socials_links')) {
	function lighthouseschool_get_socials_links($style='icons') {
		return function_exists('trx_addons_get_socials_links') 
					? trx_addons_get_socials_links($style)
					: '';
	}
}

// Return links to share post
if (!function_exists('lighthouseschool_get_share_links')) {
	function lighthouseschool_get_share_links($args=array()) {
		return function_exists('trx_addons_get_share_links') 
					? trx_addons_get_share_links($args)
					: '';
	}
}

// Display links to share post
if (!function_exists('lighthouseschool_show_share_links')) {
	function lighthouseschool_show_share_links($args=array()) {
		if (function_exists('trx_addons_get_share_links')) {
			$args['echo'] = true;
			trx_addons_get_share_links($args);
		}
	}
}


// Return image from the category
if (!function_exists('lighthouseschool_get_category_image')) {
	function lighthouseschool_get_category_image($term_id=0) {
		return function_exists('trx_addons_get_category_image') 
					? trx_addons_get_category_image($term_id)
					: '';
	}
}

// Return small image (icon) from the category
if (!function_exists('lighthouseschool_get_category_icon')) {
	function lighthouseschool_get_category_icon($term_id=0) {
		return function_exists('trx_addons_get_category_icon') 
					? trx_addons_get_category_icon($term_id)
					: '';
	}
}

// Return string with counters items
if (!function_exists('lighthouseschool_get_post_counters')) {
	function lighthouseschool_get_post_counters($counters='views') {
		return function_exists('trx_addons_get_post_counters')
					? str_replace('post_counters_item', 'post_meta_item post_counters_item', trx_addons_get_post_counters($counters))
					: '';
	}
}

// Return list with animation effects
if (!function_exists('lighthouseschool_get_list_animations_in')) {
	function lighthouseschool_get_list_animations_in() {
		return function_exists('trx_addons_get_list_animations_in') 
					? trx_addons_get_list_animations_in()
					: array();
	}
}

// Return classes list for the specified animation
if (!function_exists('lighthouseschool_get_animation_classes')) {
	function lighthouseschool_get_animation_classes($animation, $speed='normal', $loop='none') {
		return function_exists('trx_addons_get_animation_classes') 
					? trx_addons_get_animation_classes($animation, $speed, $loop)
					: '';
	}
}

// Return string with the likes counter for the specified comment
if (!function_exists('lighthouseschool_get_comment_counters')) {
	function lighthouseschool_get_comment_counters($counters = 'likes') {
		return function_exists('trx_addons_get_comment_counters') 
					? trx_addons_get_comment_counters($counters)
					: '';
	}
}

// Display likes counter for the specified comment
if (!function_exists('lighthouseschool_show_comment_counters')) {
	function lighthouseschool_show_comment_counters($counters = 'likes') {
		if (function_exists('trx_addons_get_comment_counters'))
			trx_addons_get_comment_counters($counters, true);
	}
}

// Add query params to sort posts by views or likes
if (!function_exists('lighthouseschool_trx_addons_query_sort_order')) {
	
	function lighthouseschool_trx_addons_query_sort_order($q=array(), $orderby='date', $order='desc') {
		if ($orderby == 'views') {
			$q['orderby'] = 'meta_value_num';
			$q['meta_key'] = 'trx_addons_post_views_count';
		} else if ($orderby == 'likes') {
			$q['orderby'] = 'meta_value_num';
			$q['meta_key'] = 'trx_addons_post_likes_count';
		}
		return $q;
	}
}

// Return theme-specific logo to the plugin
if ( !function_exists( 'lighthouseschool_trx_addons_theme_logo' ) ) {
	
	function lighthouseschool_trx_addons_theme_logo($logo) {
		return lighthouseschool_get_logo_image();
	}
}

// Return theme-specific post meta to the plugin
if ( !function_exists( 'lighthouseschool_trx_addons_post_meta' ) ) {
	
	function lighthouseschool_trx_addons_post_meta($meta, $args=array()) {
		return lighthouseschool_show_post_meta(apply_filters('lighthouseschool_filter_post_meta_args', $args, 'trx_addons', 1));
	}
}

// Return theme-specific post meta args
if ( !function_exists( 'lighthouseschool_trx_addons_post_meta_args' ) ) {
	
	function lighthouseschool_trx_addons_post_meta_args($args=array(), $from='', $columns=1) {
		if (is_single() && $from=='trx_addons') {
			$args['components'] = lighthouseschool_array_get_keys_by_value(lighthouseschool_get_theme_option('meta_parts'));
			$args['counters'] = lighthouseschool_array_get_keys_by_value(lighthouseschool_get_theme_option('counters'));
			$args['seo'] = lighthouseschool_is_on(lighthouseschool_get_theme_option('seo_snippets'));
		}
		return $args;
	}
}
	
// Redirect action 'get_mobile_menu' to the plugin
// Return stored items as mobile menu
if ( !function_exists( 'lighthouseschool_trx_addons_get_mobile_menu' ) ) {
	
	function lighthouseschool_trx_addons_get_mobile_menu($menu) {
		return apply_filters('trx_addons_filter_get_mobile_menu', $menu);
	}
}

// Redirect action 'login' to the plugin
if (!function_exists('lighthouseschool_trx_addons_action_login')) {
	
	function lighthouseschool_trx_addons_action_login($link_text='', $link_title='') {
		do_action( 'trx_addons_action_login', $link_text, $link_title );
	}
}

// Redirect action 'search' to the plugin
if (!function_exists('lighthouseschool_trx_addons_action_search')) {
	
	function lighthouseschool_trx_addons_action_search($style, $class, $ajax) {
		do_action( 'trx_addons_action_search', $style, $class, $ajax );
	}
}

// Redirect action 'breadcrumbs' to the plugin
if (!function_exists('lighthouseschool_trx_addons_action_breadcrumbs')) {
	
	function lighthouseschool_trx_addons_action_breadcrumbs() {
		do_action( 'trx_addons_action_breadcrumbs' );
	}
}

// Redirect action 'show_layout' to the plugin
if (!function_exists('lighthouseschool_trx_addons_action_show_layout')) {
	
	function lighthouseschool_trx_addons_action_show_layout($layout_id='') {
		do_action( 'trx_addons_action_show_layout', $layout_id );
	}
}

// Redirect filter 'get_translated_layout' to the plugin
if (!function_exists('lighthouseschool_trx_addons_filter_get_translated_layout')) {
	
	function lighthouseschool_trx_addons_filter_get_translated_layout($layout_id='') {
		return apply_filters( 'trx_addons_filter_get_translated_layout', $layout_id );
	}
}

// Show user meta (socials)
if (!function_exists('lighthouseschool_trx_addons_action_user_meta')) {
	
	function lighthouseschool_trx_addons_action_user_meta() {
		do_action( 'trx_addons_action_user_meta' );
	}
}

// Redirect filter 'get_blog_title' to the plugin
if ( !function_exists( 'lighthouseschool_trx_addons_get_blog_title' ) ) {
	
	function lighthouseschool_trx_addons_get_blog_title($title='') {
		return apply_filters('trx_addons_filter_get_blog_title', $title);
	}
}

// Return true, if theme need a SEO snippets
if (!function_exists('lighthouseschool_trx_addons_seo_snippets')) {
	
	function lighthouseschool_trx_addons_seo_snippets($enable=false) {
		return lighthouseschool_is_on(lighthouseschool_get_theme_option('seo_snippets'));
	}
}

// Show user meta (socials)
if (!function_exists('lighthouseschool_trx_addons_before_article')) {
	
	function lighthouseschool_trx_addons_before_article($page='') {
		if (lighthouseschool_is_on(lighthouseschool_get_theme_option('seo_snippets')))
			get_template_part('templates/seo');
	}
}

// Redirect filter 'prepare_css' to the plugin
if (!function_exists('lighthouseschool_trx_addons_prepare_css')) {
	
	function lighthouseschool_trx_addons_prepare_css($css='', $remove_spaces=true) {
		return apply_filters( 'trx_addons_filter_prepare_css', $css, $remove_spaces );
	}
}

// Redirect filter 'prepare_js' to the plugin
if (!function_exists('lighthouseschool_trx_addons_prepare_js')) {
	
	function lighthouseschool_trx_addons_prepare_js($js='', $remove_spaces=true) {
		return apply_filters( 'trx_addons_filter_prepare_js', $js, $remove_spaces );
	}
}

// Add plugin's specific variables to the scripts
if (!function_exists('lighthouseschool_trx_addons_localize_script')) {
	
	function lighthouseschool_trx_addons_localize_script($arr) {
		$arr['trx_addons_exists'] = lighthouseschool_exists_trx_addons();
		return $arr;
	}
}

// Add plugin-specific colors and fonts to the custom CSS
if (lighthouseschool_exists_trx_addons()) { require_once LIGHTHOUSESCHOOL_THEME_DIR . 'plugins/trx_addons/trx_addons.styles.php'; }
if (lighthouseschool_exists_trx_addons()) { require_once LIGHTHOUSESCHOOL_THEME_DIR . 'plugins/trx_addons/trx_addons.my_styles.php'; }
?>