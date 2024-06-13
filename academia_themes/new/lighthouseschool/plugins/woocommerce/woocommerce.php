<?php
/* Woocommerce support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 1 - register filters, that add/remove lists items for the Theme Options
if (!function_exists('lighthouseschool_woocommerce_theme_setup1')) {
	add_action( 'after_setup_theme', 'lighthouseschool_woocommerce_theme_setup1', 1 );
	function lighthouseschool_woocommerce_theme_setup1() {

		add_theme_support( 'woocommerce', array( 'product_grid' => array( 'max_columns' => 4 ) ) );

		// Next setting from the WooCommerce 3.0+ enable built-in image zoom on the single product page
		add_theme_support( 'wc-product-gallery-zoom' );

		// Next setting from the WooCommerce 3.0+ enable built-in image slider on the single product page
		add_theme_support( 'wc-product-gallery-slider' ); 

		// Next setting from the WooCommerce 3.0+ enable built-in image lightbox on the single product page
		add_theme_support( 'wc-product-gallery-lightbox' );

		add_filter( 'lighthouseschool_filter_list_sidebars', 	'lighthouseschool_woocommerce_list_sidebars' );
		add_filter( 'lighthouseschool_filter_list_posts_types',	'lighthouseschool_woocommerce_list_post_types');
	}
}

// Theme init priorities:
// 3 - add/remove Theme Options elements
if (!function_exists('lighthouseschool_woocommerce_theme_setup3')) {
	add_action( 'after_setup_theme', 'lighthouseschool_woocommerce_theme_setup3', 3 );
	function lighthouseschool_woocommerce_theme_setup3() {
		if (lighthouseschool_exists_woocommerce()) {
		
			// Section 'WooCommerce'
			lighthouseschool_storage_set_array_before('options', 'fonts', array_merge(
				array(
					'shop' => array(
						"title" => esc_html__('Shop', 'lighthouseschool'),
						"desc" => wp_kses_data( __('Select parameters to display the shop pages', 'lighthouseschool') ),
						"priority" => 80,
						"type" => "section"
						),

					'products_info_shop' => array(
						"title" => esc_html__('Products list', 'lighthouseschool'),
						"desc" => '',
						"type" => "info",
						),
					'shop_mode' => array(
						"title" => esc_html__('Shop mode', 'lighthouseschool'),
						"desc" => wp_kses_data( __('Select style for the products list', 'lighthouseschool') ),
						"std" => 'thumbs',
						"options" => array(
							'thumbs'=> esc_html__('Thumbnails', 'lighthouseschool'),
							'list'	=> esc_html__('List', 'lighthouseschool'),
						),
						"type" => "select"
						),
					'shop_hover' => array(
						"title" => esc_html__('Hover style', 'lighthouseschool'),
						"desc" => wp_kses_data( __('Hover style on the products in the shop archive', 'lighthouseschool') ),
						"std" => 'none',
						"options" => apply_filters('lighthouseschool_filter_shop_hover', array(
							'none' => esc_html__('None', 'lighthouseschool'),
							'shop' => esc_html__('Icons', 'lighthouseschool'),
							'shop_buttons' => esc_html__('Buttons', 'lighthouseschool')
						)),
						"type" => "select"
						),

					'single_info_shop' => array(
						"title" => esc_html__('Single product', 'lighthouseschool'),
						"desc" => '',
						"type" => "info",
						),
					'stretch_tabs_area' => array(
						"title" => esc_html__('Stretch tabs area', 'lighthouseschool'),
						"desc" => wp_kses_data( __('Stretch area with tabs on the single product to the screen width if the sidebar is hidden', 'lighthouseschool') ),
						"std" => 1,
						"type" => "checkbox"
						),
					'show_related_posts_shop' => array(
						"title" => esc_html__('Show related products', 'lighthouseschool'),
						"desc" => wp_kses_data( __("Show section 'Related products' on the single product page", 'lighthouseschool') ),
						"std" => 1,
						"type" => "checkbox"
						),
					'related_posts_shop' => array(
						"title" => esc_html__('Related products', 'lighthouseschool'),
						"desc" => wp_kses_data( __('How many related products should be displayed on the single product page?', 'lighthouseschool') ),
						"dependency" => array(
							'show_related_posts_shop' => array(1)
						),
						"std" => 3,
						"options" => lighthouseschool_get_list_range(1,9),
						"type" => "select"
						),
					'related_columns_shop' => array(
						"title" => esc_html__('Related columns', 'lighthouseschool'),
						"desc" => wp_kses_data( __('How many columns should be used to output related products on the single product page?', 'lighthouseschool') ),
						"dependency" => array(
							'show_related_posts_shop' => array(1)
						),
						"std" => 3,
						"options" => lighthouseschool_get_list_range(1,4),
						"type" => "select"
						)
				),
				lighthouseschool_options_get_list_cpt_options('shop')
			));
		}
	}
}


// Add section 'Products' to the Front Page option
if (!function_exists('lighthouseschool_woocommerce_front_page_options')) {
	if (!LIGHTHOUSESCHOOL_THEME_FREE) add_filter( 'lighthouseschool_filter_front_page_options', 'lighthouseschool_woocommerce_front_page_options' );
	function lighthouseschool_woocommerce_front_page_options($options) {
		if (lighthouseschool_exists_woocommerce()) {

			$options['front_page_sections']['std'] .= (!empty($options['front_page_sections']['std']) ? '|' : '') . 'woocommerce=1';
			$options['front_page_sections']['options'] = array_merge($options['front_page_sections']['options'], 
																	array(
																		'woocommerce' => esc_html__('Products', 'lighthouseschool')
																		)
																	);
			$options = array_merge($options, array(
			
				// Front Page Sections - WooCommerce
				'front_page_woocommerce' => array(
					"title" => esc_html__('Products', 'lighthouseschool'),
					"desc" => '',
					"priority" => 200,
					"type" => "section",
					),
				'front_page_woocommerce_layout_info' => array(
					"title" => esc_html__('Layout', 'lighthouseschool'),
					"desc" => '',
					"type" => "info",
					),
				'front_page_woocommerce_fullheight' => array(
					"title" => esc_html__('Full height', 'lighthouseschool'),
					"desc" => wp_kses_data( __('Stretch this section to the window height', 'lighthouseschool') ),
					"std" => 0,
					"refresh" => false,
					"type" => "checkbox"
					),
				'front_page_woocommerce_paddings' => array(
					"title" => esc_html__('Paddings', 'lighthouseschool'),
					"desc" => wp_kses_data( __('Select paddings inside this section', 'lighthouseschool') ),
					"std" => 'medium',
					"options" => lighthouseschool_get_list_paddings(),
					"refresh" => false,
					"type" => "switch"
					),
				'front_page_woocommerce_heading_info' => array(
					"title" => esc_html__('Title', 'lighthouseschool'),
					"desc" => '',
					"type" => "info",
					),
				'front_page_woocommerce_caption' => array(
					"title" => esc_html__('Section title', 'lighthouseschool'),
					"desc" => '',
					"refresh" => false,
					"std" => wp_kses_data(__('This text can be changed in the section "Products"', 'lighthouseschool')),
					"type" => "text"
					),
				'front_page_woocommerce_description' => array(
					"title" => esc_html__('Description', 'lighthouseschool'),
					"desc" => wp_kses_data( __("Short description after the section's title", 'lighthouseschool') ),
					"refresh" => false,
					"std" => wp_kses_data(__('This text can be changed in the section "Products"', 'lighthouseschool')),
					"type" => "textarea"
					),
				'front_page_woocommerce_products_info' => array(
					"title" => esc_html__('Products parameters', 'lighthouseschool'),
					"desc" => '',
					"type" => "info",
					),
				'front_page_woocommerce_products' => array(
					"title" => esc_html__('Type of the products', 'lighthouseschool'),
					"desc" => '',
					"std" => 'products',
					"options" => array(
									'recent_products' => esc_html__('Recent products', 'lighthouseschool'),
									'featured_products' => esc_html__('Featured products', 'lighthouseschool'),
									'top_rated_products' => esc_html__('Top rated products', 'lighthouseschool'),
									'sale_products' => esc_html__('Sale products', 'lighthouseschool'),
									'best_selling_products' => esc_html__('Best selling products', 'lighthouseschool'),
									'product_category' => esc_html__('Products from categories', 'lighthouseschool'),
									'products' => esc_html__('Products by IDs', 'lighthouseschool')
									),
					"type" => "select"
					),
				'front_page_woocommerce_products_categories' => array(
					"title" => esc_html__('Categories', 'lighthouseschool'),
					"desc" => esc_html__('Comma separated category slugs. Used only with "Products from categories"', 'lighthouseschool'),
					"dependency" => array(
						'front_page_woocommerce_products' => array('product_category')
					),
					"std" => '',
					"type" => "text"
					),
				'front_page_woocommerce_products_per_page' => array(
					"title" => esc_html__('Per page', 'lighthouseschool'),
					"desc" => wp_kses_data( __('How many products will be displayed on the page. Attention! For "Products by IDs" specify comma separated list of the IDs', 'lighthouseschool') ),
					"std" => 3,
					"type" => "text"
					),
				'front_page_woocommerce_products_columns' => array(
					"title" => esc_html__('Columns', 'lighthouseschool'),
					"desc" => wp_kses_data( __("How many columns will be used", 'lighthouseschool') ),
					"std" => 3,
					"type" => "text"
					),
				'front_page_woocommerce_products_orderby' => array(
					"title" => esc_html__('Order by', 'lighthouseschool'),
					"desc" => wp_kses_data( __("Not used with Best selling products", 'lighthouseschool') ),
					"std" => 'date',
					"options" => array(
									'date' => esc_html__('Date', 'lighthouseschool'),
									'title' => esc_html__('Title', 'lighthouseschool')
									),
					"type" => "switch"
					),
				'front_page_woocommerce_products_order' => array(
					"title" => esc_html__('Order', 'lighthouseschool'),
					"desc" => wp_kses_data( __("Not used with Best selling products", 'lighthouseschool') ),
					"std" => 'desc',
					"options" => array(
									'asc' => esc_html__('Ascending', 'lighthouseschool'),
									'desc' => esc_html__('Descending', 'lighthouseschool')
									),
					"type" => "switch"
					),
				'front_page_woocommerce_color_info' => array(
					"title" => esc_html__('Colors and images', 'lighthouseschool'),
					"desc" => '',
					"type" => "info",
					),
				'front_page_woocommerce_scheme' => array(
					"title" => esc_html__('Color scheme', 'lighthouseschool'),
					"desc" => wp_kses_data( __('Color scheme for this section', 'lighthouseschool') ),
					"std" => 'inherit',
					"options" => array(),
					"refresh" => false,
					"type" => "switch"
					),
				'front_page_woocommerce_bg_image' => array(
					"title" => esc_html__('Background image', 'lighthouseschool'),
					"desc" => wp_kses_data( __('Select or upload background image for this section', 'lighthouseschool') ),
					"refresh" => '.front_page_section_woocommerce',
					"refresh_wrapper" => true,
					"std" => '',
					"type" => "image"
					),
				'front_page_woocommerce_bg_color' => array(
					"title" => esc_html__('Background color', 'lighthouseschool'),
					"desc" => wp_kses_data( __('Background color for this section', 'lighthouseschool') ),
					"std" => '',
					"refresh" => false,
					"type" => "color"
					),
				'front_page_woocommerce_bg_mask' => array(
					"title" => esc_html__('Background mask', 'lighthouseschool'),
					"desc" => wp_kses_data( __('Use Background color as section mask with specified opacity. If 0 - mask is not used', 'lighthouseschool') ),
					"std" => 1,
					"max" => 1,
					"step" => 0.1,
					"refresh" => false,
					"type" => "slider"
					),
				'front_page_woocommerce_anchor_info' => array(
					"title" => esc_html__('Anchor', 'lighthouseschool'),
					"desc" => wp_kses_data( __('You can select icon and/or specify a text to create anchor for this section and show it in the side menu (if selected in the section "Header - Menu".', 'lighthouseschool'))
								. '<br>'
								. wp_kses_data(__('Attention! Anchors available only if plugin "ThemeREX Addons is installed and activated!', 'lighthouseschool')),
					"type" => "info",
					),
				'front_page_woocommerce_anchor_icon' => array(
					"title" => esc_html__('Anchor icon', 'lighthouseschool'),
					"desc" => '',
					"std" => '',
					"type" => "icon"
					),
				'front_page_woocommerce_anchor_text' => array(
					"title" => esc_html__('Anchor text', 'lighthouseschool'),
					"desc" => '',
					"std" => '',
					"type" => "text"
					)
			));
		}
		return $options;
	}
}

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('lighthouseschool_woocommerce_theme_setup9')) {
	add_action( 'after_setup_theme', 'lighthouseschool_woocommerce_theme_setup9', 9 );
	function lighthouseschool_woocommerce_theme_setup9() {
		
		if (lighthouseschool_exists_woocommerce()) {
			add_action( 'wp_enqueue_scripts', 								'lighthouseschool_woocommerce_frontend_scripts', 1100 );
			add_filter( 'lighthouseschool_filter_merge_styles',						'lighthouseschool_woocommerce_merge_styles' );
			add_filter( 'lighthouseschool_filter_merge_scripts',						'lighthouseschool_woocommerce_merge_scripts');
			add_filter( 'lighthouseschool_filter_get_post_info',		 				'lighthouseschool_woocommerce_get_post_info');
			add_filter( 'lighthouseschool_filter_post_type_taxonomy',				'lighthouseschool_woocommerce_post_type_taxonomy', 10, 2 );
			add_action( 'lighthouseschool_action_override_theme_options',			'lighthouseschool_woocommerce_override_theme_options');
			if (!is_admin()) {
				add_filter( 'lighthouseschool_filter_detect_blog_mode',				'lighthouseschool_woocommerce_detect_blog_mode');
				add_filter( 'lighthouseschool_filter_get_post_categories', 			'lighthouseschool_woocommerce_get_post_categories');
				add_filter( 'lighthouseschool_filter_allow_override_header_image',	'lighthouseschool_woocommerce_allow_override_header_image');
				add_filter( 'lighthouseschool_filter_get_blog_title',				'lighthouseschool_woocommerce_get_blog_title');
				add_action( 'lighthouseschool_action_before_post_meta',				'lighthouseschool_woocommerce_action_before_post_meta');
				add_action( 'pre_get_posts',								'lighthouseschool_woocommerce_pre_get_posts');
				add_filter( 'lighthouseschool_filter_localize_script',				'lighthouseschool_woocommerce_localize_script');
			}
		}
		if (is_admin()) {
			add_filter( 'lighthouseschool_filter_tgmpa_required_plugins',			'lighthouseschool_woocommerce_tgmpa_required_plugins' );
		}

		// Add wrappers and classes to the standard WooCommerce output
		if (lighthouseschool_exists_woocommerce()) {

			// Remove WOOC sidebar
			remove_action( 'woocommerce_sidebar', 						'woocommerce_get_sidebar', 10 );

			// Remove link around product item
			remove_action('woocommerce_before_shop_loop_item',			'woocommerce_template_loop_product_link_open', 10);
			remove_action('woocommerce_after_shop_loop_item',			'woocommerce_template_loop_product_link_close', 5);

			// Remove link around product category
			remove_action('woocommerce_before_subcategory',				'woocommerce_template_loop_category_link_open', 10);
			remove_action('woocommerce_after_subcategory',				'woocommerce_template_loop_category_link_close', 10);
			
			// Open main content wrapper - <article>
			remove_action( 'woocommerce_before_main_content',			'woocommerce_output_content_wrapper', 10);
			add_action(    'woocommerce_before_main_content',			'lighthouseschool_woocommerce_wrapper_start', 10);
			// Close main content wrapper - </article>
			remove_action( 'woocommerce_after_main_content',			'woocommerce_output_content_wrapper_end', 10);		
			add_action(    'woocommerce_after_main_content',			'lighthouseschool_woocommerce_wrapper_end', 10);

			// Close header section
			add_action(    'woocommerce_before_shop_loop',				'lighthouseschool_woocommerce_archive_description', 5 );
			add_action(    'woocommerce_no_products_found',				'lighthouseschool_woocommerce_archive_description', 5 );

			// Add theme specific search form
			add_filter(    'get_product_search_form',					'lighthouseschool_woocommerce_get_product_search_form' );

			// Change text on 'Add to cart' button
			add_filter(    'woocommerce_product_add_to_cart_text',		'lighthouseschool_woocommerce_add_to_cart_text' );
			add_filter(    'woocommerce_product_single_add_to_cart_text','lighthouseschool_woocommerce_add_to_cart_text' );

			// Add list mode buttons
			add_action(    'woocommerce_before_shop_loop', 				'lighthouseschool_woocommerce_before_shop_loop', 10 );

			// Open product/category item wrapper
			add_action(    'woocommerce_before_subcategory_title',		'lighthouseschool_woocommerce_item_wrapper_start', 9 );
			add_action(    'woocommerce_before_shop_loop_item_title',	'lighthouseschool_woocommerce_item_wrapper_start', 9 );
			// Close featured image wrapper and open title wrapper
			add_action(    'woocommerce_before_subcategory_title',		'lighthouseschool_woocommerce_title_wrapper_start', 20 );
			add_action(    'woocommerce_before_shop_loop_item_title',	'lighthouseschool_woocommerce_title_wrapper_start', 20 );

			// Wrap product title into link
			add_action(    'the_title',									'lighthouseschool_woocommerce_the_title');
			// Wrap category title into link
			add_action(		'woocommerce_before_subcategory_title',		'lighthouseschool_woocommerce_before_subcategory_title', 22, 1 );
			add_action(		'woocommerce_after_subcategory_title',		'lighthouseschool_woocommerce_after_subcategory_title', 2, 1 );


			// Close title wrapper and add description in the list mode
			add_action(    'woocommerce_after_shop_loop_item_title',	'lighthouseschool_woocommerce_title_wrapper_end', 7);
			add_action(    'woocommerce_after_subcategory_title',		'lighthouseschool_woocommerce_title_wrapper_end2', 10 );
			// Close product/category item wrapper
			add_action(    'woocommerce_after_subcategory',				'lighthouseschool_woocommerce_item_wrapper_end', 20 );
			add_action(    'woocommerce_after_shop_loop_item',			'lighthouseschool_woocommerce_item_wrapper_end', 20 );

			// Add product ID into product meta section (after categories and tags)
			add_action(    'woocommerce_product_meta_end',				'lighthouseschool_woocommerce_show_product_id', 10);
			
			// Set columns number for the product's thumbnails
			add_filter(    'woocommerce_product_thumbnails_columns',	'lighthouseschool_woocommerce_product_thumbnails_columns' );

			// Add 'Out of stock' label
			add_action( 'lighthouseschool_action_woocommerce_item_featured_link_start', 'lighthouseschool_woocommerce_add_out_of_stock_label' );


			// Detect current shop mode
			if (!is_admin()) {
				$shop_mode = lighthouseschool_get_value_gpc('lighthouseschool_shop_mode');
				if (empty($shop_mode) && lighthouseschool_check_theme_option('shop_mode'))
					$shop_mode = lighthouseschool_get_theme_option('shop_mode');
				if (empty($shop_mode))
					$shop_mode = 'thumbs';
				lighthouseschool_storage_set('shop_mode', $shop_mode);
			}
		}
	}
}

// Theme init priorities:
// Action 'wp'
// 1 - detect override mode. Attention! Only after this step you can use overriden options (separate values for the shop, courses, etc.)
if (!function_exists('lighthouseschool_woocommerce_theme_setup_wp')) {
	add_action( 'wp', 'lighthouseschool_woocommerce_theme_setup_wp' );
	function lighthouseschool_woocommerce_theme_setup_wp() {
		if (lighthouseschool_exists_woocommerce()) {
			// Set columns number for the related products
			if ((int) lighthouseschool_get_theme_option('show_related_posts') == 0 || (int) lighthouseschool_get_theme_option('related_posts') == 0) {
				remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
			} else {
				add_filter(    'woocommerce_output_related_products_args',	'lighthouseschool_woocommerce_output_related_products_args' );
				add_filter(    'woocommerce_related_products_columns',		'lighthouseschool_woocommerce_related_products_columns' );
			}
		}
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'lighthouseschool_woocommerce_tgmpa_required_plugins' ) ) {
	
	function lighthouseschool_woocommerce_tgmpa_required_plugins($list=array()) {
		if (lighthouseschool_storage_isset('required_plugins', 'woocommerce')) {
			$list[] = array(
					'name' 		=> lighthouseschool_storage_get_array('required_plugins', 'woocommerce'),
					'slug' 		=> 'woocommerce',
					'required' 	=> false
				);
		}
		return $list;
	}
}


// Check if WooCommerce installed and activated
if ( !function_exists( 'lighthouseschool_exists_woocommerce' ) ) {
	function lighthouseschool_exists_woocommerce() {
		return class_exists('Woocommerce');
	}
}

// Return true, if current page is any woocommerce page
if ( !function_exists( 'lighthouseschool_is_woocommerce_page' ) ) {
	function lighthouseschool_is_woocommerce_page() {
		$rez = false;
		if (lighthouseschool_exists_woocommerce())
			$rez = is_woocommerce() || is_shop() || is_product() || is_product_category() || is_product_tag() || is_product_taxonomy() || is_cart() || is_checkout() || is_account_page();
		return $rez;
	}
}

// Detect current blog mode
if ( !function_exists( 'lighthouseschool_woocommerce_detect_blog_mode' ) ) {
	
	function lighthouseschool_woocommerce_detect_blog_mode($mode='') {
		if (is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy())
			$mode = 'shop';
		else if (is_product() || is_cart() || is_checkout() || is_account_page())
			$mode = 'shop';
		return $mode;
	}
}

// Override options with stored page meta on 'Shop' pages
if ( !function_exists('lighthouseschool_woocommerce_override_theme_options') ) {
	
	function lighthouseschool_woocommerce_override_theme_options() {
		if (is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy()) {
			if (($id = lighthouseschool_woocommerce_get_shop_page_id()) > 0)
				lighthouseschool_storage_set('options_meta', get_post_meta($id, 'lighthouseschool_options', true));
		}
	}
}

// Return current page title
if ( !function_exists( 'lighthouseschool_woocommerce_get_blog_title' ) ) {
	
	function lighthouseschool_woocommerce_get_blog_title($title='') {
		if (!lighthouseschool_exists_trx_addons() && lighthouseschool_exists_woocommerce() && lighthouseschool_is_woocommerce_page() && is_shop()) {
			$id = lighthouseschool_woocommerce_get_shop_page_id();
			$title = $id ? get_the_title($id) : esc_html__('Shop', 'lighthouseschool');
		}
		return $title;
	}
}


// Return taxonomy for current post type
if ( !function_exists( 'lighthouseschool_woocommerce_post_type_taxonomy' ) ) {
	
	function lighthouseschool_woocommerce_post_type_taxonomy($tax='', $post_type='') {
		if ($post_type == 'product')
			$tax = 'product_cat';
		return $tax;
	}
}

// Return true if page title section is allowed
if ( !function_exists( 'lighthouseschool_woocommerce_allow_override_header_image' ) ) {
	
	function lighthouseschool_woocommerce_allow_override_header_image($allow=true) {
		return is_product() ? false : $allow;
	}
}

// Return shop page ID
if ( !function_exists( 'lighthouseschool_woocommerce_get_shop_page_id' ) ) {
	function lighthouseschool_woocommerce_get_shop_page_id() {
		return get_option('woocommerce_shop_page_id');
	}
}

// Return shop page link
if ( !function_exists( 'lighthouseschool_woocommerce_get_shop_page_link' ) ) {
	function lighthouseschool_woocommerce_get_shop_page_link() {
		$url = '';
		$id = lighthouseschool_woocommerce_get_shop_page_id();
		if ($id) $url = get_permalink($id);
		return $url;
	}
}

// Show categories of the current product
if ( !function_exists( 'lighthouseschool_woocommerce_get_post_categories' ) ) {
	
	function lighthouseschool_woocommerce_get_post_categories($cats='') {
		if (get_post_type()=='product') {
			$cats = lighthouseschool_get_post_terms(', ', get_the_ID(), 'product_cat');
		}
		return $cats;
	}
}

// Add 'product' to the list of the supported post-types
if ( !function_exists( 'lighthouseschool_woocommerce_list_post_types' ) ) {
	
	function lighthouseschool_woocommerce_list_post_types($list=array()) {
		$list['product'] = esc_html__('Products', 'lighthouseschool');
		return $list;
	}
}

// Show price of the current product in the widgets and search results
if ( !function_exists( 'lighthouseschool_woocommerce_get_post_info' ) ) {
	
	function lighthouseschool_woocommerce_get_post_info($post_info='') {
		if (get_post_type()=='product') {
			global $product;
			if ( $price_html = $product->get_price_html() ) {
				$post_info = '<div class="post_price product_price price">' . trim($price_html) . '</div>' . $post_info;
			}
		}
		return $post_info;
	}
}

// Show price of the current product in the search results streampage
if ( !function_exists( 'lighthouseschool_woocommerce_action_before_post_meta' ) ) {
	
	function lighthouseschool_woocommerce_action_before_post_meta() {
		if (!is_single() && get_post_type()=='product') {
			global $product;
			if ( $price_html = $product->get_price_html() ) {
				?><div class="post_price product_price price"><?php lighthouseschool_show_layout($price_html); ?></div><?php
			}
		}
	}
}
	
// Enqueue WooCommerce custom styles
if ( !function_exists( 'lighthouseschool_woocommerce_frontend_scripts' ) ) {
	function lighthouseschool_woocommerce_frontend_scripts() {
			if (lighthouseschool_is_on(lighthouseschool_get_theme_option('debug_mode')) && lighthouseschool_get_file_dir('plugins/woocommerce/woocommerce.css')!='')
				wp_enqueue_style( 'lighthouseschool-woocommerce',  lighthouseschool_get_file_url('plugins/woocommerce/woocommerce.css'), array(), null );
			if (lighthouseschool_is_on(lighthouseschool_get_theme_option('debug_mode')) && lighthouseschool_get_file_dir('plugins/woocommerce/woocommerce.js')!='')
				wp_enqueue_script( 'lighthouseschool-woocommerce', lighthouseschool_get_file_url('plugins/woocommerce/woocommerce.js'), array('jquery'), null, true );
	}
}
	
// Merge custom styles
if ( !function_exists( 'lighthouseschool_woocommerce_merge_styles' ) ) {
	
	function lighthouseschool_woocommerce_merge_styles($list) {
		$list[] = 'plugins/woocommerce/woocommerce.css';
		return $list;
	}
}
	
// Merge custom scripts
if ( !function_exists( 'lighthouseschool_woocommerce_merge_scripts' ) ) {
	
	function lighthouseschool_woocommerce_merge_scripts($list) {
		$list[] = 'plugins/woocommerce/woocommerce.js';
		return $list;
	}
}



// Add WooCommerce specific items into lists
//------------------------------------------------------------------------

// Add sidebar
if ( !function_exists( 'lighthouseschool_woocommerce_list_sidebars' ) ) {
	
	function lighthouseschool_woocommerce_list_sidebars($list=array()) {
		$list['woocommerce_widgets'] = array(
											'name' => esc_html__('WooCommerce Widgets', 'lighthouseschool'),
											'description' => esc_html__('Widgets to be shown on the WooCommerce pages', 'lighthouseschool')
											);
		return $list;
	}
}




// Decorate WooCommerce output: Loop
//------------------------------------------------------------------------

// Add query vars to set products per page
if (!function_exists('lighthouseschool_woocommerce_pre_get_posts')) {
	
	function lighthouseschool_woocommerce_pre_get_posts($query) {
		if (!$query->is_main_query()) return;
		if ($query->get('post_type') == 'product') {
			$ppp = get_theme_mod('posts_per_page_shop', 0);
			if ($ppp > 0)
				$query->set('posts_per_page', $ppp);
		}
	}
}


// Before main content
if ( !function_exists( 'lighthouseschool_woocommerce_wrapper_start' ) ) {
	
	function lighthouseschool_woocommerce_wrapper_start() {
		if (is_product() || is_cart() || is_checkout() || is_account_page()) {
			?>
			<article class="post_item_single post_type_product">
			<?php
		} else {
			?>
			<div class="list_products shop_mode_<?php echo !lighthouseschool_storage_empty('shop_mode') ? lighthouseschool_storage_get('shop_mode') : 'thumbs'; ?>">
				<div class="list_products_header">
			<?php
		}
	}
}

// After main content
if ( !function_exists( 'lighthouseschool_woocommerce_wrapper_end' ) ) {
	
	function lighthouseschool_woocommerce_wrapper_end() {
		if (is_product() || is_cart() || is_checkout() || is_account_page()) {
			?>
			</article><!-- /.post_item_single -->
			<?php
		} else {
			?>
			</div><!-- /.list_products -->
			<?php
		}
	}
}

// Close header section
if ( !function_exists( 'lighthouseschool_woocommerce_archive_description' ) ) {
	
	
	function lighthouseschool_woocommerce_archive_description() {
		?>
		</div><!-- /.list_products_header -->
		<?php
	}
}

// Add list mode buttons
if ( !function_exists( 'lighthouseschool_woocommerce_before_shop_loop' ) ) {
	
	function lighthouseschool_woocommerce_before_shop_loop() {
		?>
		<div class="lighthouseschool_shop_mode_buttons"><form action="<?php echo esc_url(lighthouseschool_get_current_url()); ?>" method="post"><input type="hidden" name="lighthouseschool_shop_mode" value="<?php echo esc_attr(lighthouseschool_storage_get('shop_mode')); ?>" /><a href="#" class="woocommerce_thumbs icon-th" title="<?php esc_attr_e('Show products as thumbs', 'lighthouseschool'); ?>"></a><a href="#" class="woocommerce_list icon-th-list" title="<?php esc_attr_e('Show products as list', 'lighthouseschool'); ?>"></a></form></div><!-- /.lighthouseschool_shop_mode_buttons -->
		<?php
	}
}

// Number of columns for the shop streampage
if ( !function_exists( 'lighthouseschool_woocommerce_loop_shop_columns' ) ) {
	
	function lighthouseschool_woocommerce_loop_shop_columns($cols) {
		return max(2, min(4, lighthouseschool_get_theme_option('blog_columns')));
	}
}

// Add column class into product item in shop streampage
if ( !function_exists( 'lighthouseschool_woocommerce_loop_shop_columns_class' ) ) {
	
	
	function lighthouseschool_woocommerce_loop_shop_columns_class($classes, $class='', $cat='') {
		global $woocommerce_loop;
		if (is_product()) {
			if (!empty($woocommerce_loop['columns'])) {
				$classes[] = ' column-1_'.esc_attr($woocommerce_loop['columns']);
			}
		} else if (is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy()) {
			$classes[] = ' column-1_'.esc_attr(max(2, min(4, lighthouseschool_get_theme_option('blog_columns'))));
		}
		return $classes;
	}
}


// Open item wrapper for categories and products
if ( !function_exists( 'lighthouseschool_woocommerce_item_wrapper_start' ) ) {
	
	
	function lighthouseschool_woocommerce_item_wrapper_start($cat='') {
		lighthouseschool_storage_set('in_product_item', true);
		$hover = lighthouseschool_get_theme_option('shop_hover');
		?>
		<div class="post_item post_layout_<?php echo esc_attr(lighthouseschool_storage_get('shop_mode')); ?>">
			<div class="post_featured hover_<?php echo esc_attr($hover); ?>">
				<?php do_action('lighthouseschool_action_woocommerce_item_featured_start'); ?>
				<a href="<?php echo esc_url(is_object($cat) ? get_term_link($cat->slug, 'product_cat') : get_permalink()); ?>">
				<?php
				do_action( 'lighthouseschool_action_woocommerce_item_featured_link_start' );
	}
}

// Open item wrapper for categories and products
if ( !function_exists( 'lighthouseschool_woocommerce_open_item_wrapper' ) ) {
	
	
	function lighthouseschool_woocommerce_title_wrapper_start($cat='') {
				?></a><?php
				if (($hover = lighthouseschool_get_theme_option('shop_hover')) != 'none') {
					?><div class="mask"></div><?php
					lighthouseschool_hovers_add_icons($hover, array('cat'=>$cat));
				}
				do_action('lighthouseschool_action_woocommerce_item_featured_end');
				?>
			</div><!-- /.post_featured -->
			<div class="post_data">
				<div class="post_data_inner">
					<div class="post_header entry-header">
					<?php
	}
}


// Display product's tags before the title
if ( !function_exists( 'lighthouseschool_woocommerce_title_tags' ) ) {
	
	function lighthouseschool_woocommerce_title_tags() {
		global $product;
		lighthouseschool_show_layout(wc_get_product_tag_list( $product->get_id(), ', ', '<div class="post_tags product_tags">', '</div>' ));
	}
}

// Wrap product title into link
if ( !function_exists( 'lighthouseschool_woocommerce_the_title' ) ) {
	
	function lighthouseschool_woocommerce_the_title($title) {
		if (lighthouseschool_storage_get('in_product_item') && get_post_type()=='product') {
			$title = '<a href="'.esc_url(get_permalink()).'">'.esc_html($title).'</a>';
		}
		return $title;
	}
}

// Wrap category title to the link: open tag
if ( !function_exists( 'lighthouseschool_woocommerce_before_subcategory_title' ) ) {
	
function lighthouseschool_woocommerce_before_subcategory_title($cat) {
if (lighthouseschool_storage_get('in_product_item') && is_object($cat)) {
	?><a href="<?php echo esc_url(get_term_link($cat->slug, 'product_cat')); ?>"><?php
}
}
}

// Wrap category title to the link: close tag
if ( !function_exists( 'lighthouseschool_woocommerce_after_subcategory_title' ) ) {
	
function lighthouseschool_woocommerce_after_subcategory_title($cat) {
if (lighthouseschool_storage_get('in_product_item') && is_object($cat)) {
	?></a><?php
}
}
}

// Add excerpt in output for the product in the list mode
if ( !function_exists( 'lighthouseschool_woocommerce_title_wrapper_end' ) ) {
	
	function lighthouseschool_woocommerce_title_wrapper_end() {
			?>
			</div><!-- /.post_header -->
		<?php
		if (lighthouseschool_storage_get('shop_mode') == 'list' && (is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy()) && !is_product()) {
		    $excerpt = apply_filters('the_excerpt', get_the_excerpt());
			?>
			<div class="post_content entry-content"><?php lighthouseschool_show_layout($excerpt); ?></div>
			<?php
		}
	}
}

// Add excerpt in output for the product in the list mode
if ( !function_exists( 'lighthouseschool_woocommerce_title_wrapper_end2' ) ) {
	
	function lighthouseschool_woocommerce_title_wrapper_end2($category) {
			?>
			</div><!-- /.post_header -->
		<?php
		if (lighthouseschool_storage_get('shop_mode') == 'list' && is_shop() && !is_product()) {
			?>
			<div class="post_content entry-content"><?php lighthouseschool_show_layout($category->description); ?></div><!-- /.post_content -->
			<?php
		}
	}
}

// Close item wrapper for categories and products
if ( !function_exists( 'lighthouseschool_woocommerce_close_item_wrapper' ) ) {
	
	
	function lighthouseschool_woocommerce_item_wrapper_end($cat='') {
				?>
				</div><!-- /.post_data_inner -->
			</div><!-- /.post_data -->
		</div><!-- /.post_item -->
		<?php
		lighthouseschool_storage_set('in_product_item', false);
	}
}

// Change text on 'Add to cart' button
if ( !function_exists( 'lighthouseschool_woocommerce_add_to_cart_text' ) ) {
    
    
    function lighthouseschool_woocommerce_add_to_cart_text($text='') {
        global $product;
        $product_type = $product->get_type();
        switch ( $product_type ) {
            case 'external':
                return $product->get_button_text();
                break;
            default:
                return esc_html__('Buy now', 'lighthouseschool');
        }
    }
}

// Decorate price
if ( !function_exists( 'lighthouseschool_woocommerce_get_price_html' ) ) {
	
	function lighthouseschool_woocommerce_get_price_html($price='') {
		if (!is_admin() && !empty($price)) {
			$sep = get_option('woocommerce_price_decimal_sep');
			if (empty($sep)) $sep = '.';
			$price = preg_replace('/([0-9,]+)(\\'.trim($sep).')([0-9]{2})/', '\\1<span class="decimals">\\3</span>', $price);
		}
		return $price;
	}
}



// Decorate WooCommerce output: Single product
//------------------------------------------------------------------------

// Add WooCommerce specific vars into localize array
if (!function_exists('lighthouseschool_woocommerce_localize_script')) {
	
	function lighthouseschool_woocommerce_localize_script($arr) {
		$arr['stretch_tabs_area'] = !lighthouseschool_sidebar_present() ? lighthouseschool_get_theme_option('stretch_tabs_area') : 0;
		return $arr;
	}
}

// Add Product ID for the single product
if ( !function_exists( 'lighthouseschool_woocommerce_show_product_id' ) ) {
	
	function lighthouseschool_woocommerce_show_product_id() {
		$authors = wp_get_post_terms(get_the_ID(), 'pa_product_author');
		if (is_array($authors) && count($authors)>0) {
			echo '<span class="product_author">'.esc_html__('Author: ', 'lighthouseschool');
			$delim = '';
			foreach ($authors as $author) {
				echo  esc_html($delim) . '<span>' . esc_html($author->name) . '</span>';
				$delim = ', ';
			}
			echo '</span>';
		}
		echo '<span class="product_id">'.esc_html__('Product ID: ', 'lighthouseschool') . '<span>' . get_the_ID() . '</span></span>';
	}
}

// Number columns for the product's thumbnails
if ( !function_exists( 'lighthouseschool_woocommerce_product_thumbnails_columns' ) ) {
	
	function lighthouseschool_woocommerce_product_thumbnails_columns($cols) {
		return 4;
	}
}

// Set products number for the related products
if ( !function_exists( 'lighthouseschool_woocommerce_output_related_products_args' ) ) {
	
	function lighthouseschool_woocommerce_output_related_products_args($args) {
		$args['posts_per_page'] = (int) lighthouseschool_get_theme_option('show_related_posts') 
										? max(0, min(9, lighthouseschool_get_theme_option('related_posts'))) 
										: 0;
		$args['columns'] = max(1, min(4, lighthouseschool_get_theme_option('related_columns')));
		return $args;
	}
}

// Set columns number for the related products
if ( !function_exists( 'lighthouseschool_woocommerce_related_products_columns' ) ) {
	
	function lighthouseschool_woocommerce_related_products_columns($columns) {
		$columns = max(1, min(4, lighthouseschool_get_theme_option('related_columns')));
		return $columns;
	}
}



// Decorate WooCommerce output: Widgets
//------------------------------------------------------------------------

// Search form
if ( !function_exists( 'lighthouseschool_woocommerce_get_product_search_form' ) ) {
	
	function lighthouseschool_woocommerce_get_product_search_form($form) {
		return '
		<form role="search" method="get" class="search_form" action="' . esc_url(home_url('/')) . '">
			<input type="text" class="search_field" placeholder="' . esc_attr__('Search for products &hellip;', 'lighthouseschool') . '" value="' . get_search_query() . '" name="s" /><button class="search_button" type="submit">' . esc_html__('Search', 'lighthouseschool') . '</button>
			<input type="hidden" name="post_type" value="product" />
		</form>
		';
	}
}


// Filter Price steo
if ( ! function_exists( 'lighthouseschool_woocommerce_price_filter_widget_step' ) ) {
    add_filter('woocommerce_price_filter_widget_step', 'lighthouseschool_woocommerce_price_filter_widget_step');
    function lighthouseschool_woocommerce_price_filter_widget_step( $step = '' ) {
        $step = 1;
        return $step;
    }
}


// Add label 'out of stock'
if ( ! function_exists( 'lighthouseschool_woocommerce_add_out_of_stock_label' ) ) {
	
	function lighthouseschool_woocommerce_add_out_of_stock_label() {
		global $product;
		$cat = lighthouseschool_storage_get( 'in_product_category' );
		if ( empty($cat) || ! is_object($cat) ) {
			if ( is_object( $product ) && ! $product->is_in_stock() ) {
				?>
				<span class="outofstock_label"><?php esc_html_e( 'Out of stock', 'lighthouseschool' ); ?></span>
				<?php
			}
		}
	}
}


// Add plugin-specific colors and fonts to the custom CSS
if (lighthouseschool_exists_woocommerce()) { require_once LIGHTHOUSESCHOOL_THEME_DIR . 'plugins/woocommerce/woocommerce.styles.php'; }
?>