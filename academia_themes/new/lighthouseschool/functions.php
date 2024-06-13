<?php
/**
 * Theme functions: init, enqueue scripts and styles, include required files and widgets
 *
 * @package WordPress
 * @subpackage LIGHTHOUSESCHOOL
 * @since LIGHTHOUSESCHOOL 1.0
 */

if (!defined("LIGHTHOUSESCHOOL_THEME_DIR")) define("LIGHTHOUSESCHOOL_THEME_DIR", trailingslashit( get_template_directory() ));
if (!defined("LIGHTHOUSESCHOOL_CHILD_DIR")) define("LIGHTHOUSESCHOOL_CHILD_DIR", trailingslashit( get_stylesheet_directory() ));

//-------------------------------------------------------
//-- Theme init
//-------------------------------------------------------

// Theme init priorities:
// Action 'after_setup_theme'
// 1 - register filters to add/remove lists items in the Theme Options
// 2 - create Theme Options
// 3 - add/remove Theme Options elements
// 5 - load Theme Options. Attention! After this step you can use only basic options (not overriden)
// 9 - register other filters (for installer, etc.)
//10 - standard Theme init procedures (not ordered)
// Action 'wp_loaded'
// 1 - detect override mode. Attention! Only after this step you can use overriden options (separate values for the shop, courses, etc.)

if ( !function_exists('lighthouseschool_theme_setup1') ) {
	add_action( 'after_setup_theme', 'lighthouseschool_theme_setup1', 1 );
	function lighthouseschool_theme_setup1() {
		// Make theme available for translation
		// Translations can be filed in the /languages directory
		// Attention! Translations must be loaded before first call any translation functions!
		load_theme_textdomain( 'lighthouseschool', get_template_directory() . '/languages' );

		// Set theme content width
		$GLOBALS['content_width'] = apply_filters( 'lighthouseschool_filter_content_width', 1170 );
	}
}

if ( !function_exists('lighthouseschool_theme_setup') ) {
	add_action( 'after_setup_theme', 'lighthouseschool_theme_setup' );
	function lighthouseschool_theme_setup() {

		// Add default posts and comments RSS feed links to head 
		add_theme_support( 'automatic-feed-links' );
		
		// Custom header setup
		add_theme_support( 'custom-header', array(
			'header-text'=>false,
			'video' => true
			)
		);

		// Custom backgrounds setup
		add_theme_support( 'custom-background', array()	);

		// Partial refresh support in the Customize
		add_theme_support( 'customize-selective-refresh-widgets' );
		
		// Supported posts formats
		add_theme_support( 'post-formats', array('gallery', 'video', 'audio', 'link', 'quote', 'image', 'status', 'aside', 'chat') ); 
 
 		// Autogenerate title tag
		add_theme_support('title-tag');
 		
		// Add theme menus
		add_theme_support('nav-menus');
		
		// Switch default markup for search form, comment form, and comments to output valid HTML5.
		add_theme_support( 'html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption') );
		
		// Editor custom stylesheet - for user
		add_editor_style( array_merge(
			array(
				'css/editor-style.css',
				lighthouseschool_get_file_url('css/font-icons/css/fontello-embedded.css')
			),
			lighthouseschool_theme_fonts_for_editor()
			)
		);	
	
		// Register navigation menu
		register_nav_menus(array(
			'menu_main' => esc_html__('Main Menu', 'lighthouseschool'),
			'menu_mobile' => esc_html__('Mobile Menu', 'lighthouseschool'),
			'menu_footer' => esc_html__('Footer Menu', 'lighthouseschool')
			)
		);

		// Excerpt filters
		add_filter( 'excerpt_length',						'lighthouseschool_excerpt_length' );
		add_filter( 'excerpt_more',							'lighthouseschool_excerpt_more' );
		
		// Add required meta tags in the head
		add_action('wp_head',		 						'lighthouseschool_wp_head', 0);
		
		// Load current page/post customization (if present)
		add_action('wp_footer',		 						'lighthouseschool_wp_footer');
		add_action('admin_footer',	 						'lighthouseschool_wp_footer');
		
		// Enqueue scripts and styles for frontend
		add_action('wp_enqueue_scripts', 					'lighthouseschool_wp_scripts', 1000);			// priority 1000 - load styles
																									// before the plugin's support custom styles
																									// (with priority 1100)
																									// and child-theme styles
																									// (with priority 1200)
		add_action('wp_enqueue_scripts', 					'lighthouseschool_wp_scripts_child', 1200);		// priority 1200 - load styles
																									// after the plugin's support custom styles
																									// (with priority 1100)
		add_action('wp_enqueue_scripts', 					'lighthouseschool_wp_scripts_responsive', 2000);	// priority 2000 - load responsive
																									// after all other styles
		add_action('wp_footer',		 						'lighthouseschool_localize_scripts');
		
		// Add body classes
		add_filter( 'body_class',							'lighthouseschool_add_body_classes' );

		// Register sidebars
		add_action('widgets_init',							'lighthouseschool_register_sidebars');
	}

}


//-------------------------------------------------------
//-- Theme scripts and styles
//-------------------------------------------------------

// Load frontend scripts
if ( !function_exists( 'lighthouseschool_wp_scripts' ) ) {
	
	function lighthouseschool_wp_scripts() {
		
		// Enqueue styles
		//------------------------
		
		// Links to selected fonts
		$links = lighthouseschool_theme_fonts_links();
		if (count($links) > 0) {
			foreach ($links as $slug => $link) {
				wp_enqueue_style( sprintf('lighthouseschool-font-%s', $slug), $link );
			}
		}
		
		// Font icons styles must be loaded before main stylesheet
		// This style NEED the theme prefix, because style 'fontello' in some plugin contain different set of characters
		// and can't be used instead this style!
		wp_enqueue_style( 'fontello-style',  lighthouseschool_get_file_url('css/font-icons/css/fontello-embedded.css') );

		// Load main stylesheet
		$main_stylesheet = get_template_directory_uri() . '/style.css';
		wp_enqueue_style( 'lighthouseschool-main', $main_stylesheet, array(), null );

		// Add custom bg image for the Front page
		if ( is_front_page() 
			&& lighthouseschool_is_on(lighthouseschool_get_theme_option('front_page_enabled'))
			&& ($bg_image = lighthouseschool_get_theme_option('front_page_bg_image')) != '' )
			wp_add_inline_style( 'lighthouseschool-main', 'body.frontpage { background-image:url('.esc_url($bg_image).') !important }' );

		// Add custom bg image for the body_style == 'boxed'
		else if ( lighthouseschool_get_theme_option('body_style') == 'boxed' && ($bg_image = lighthouseschool_get_theme_option('boxed_bg_image')) != '' )
			wp_add_inline_style( 'lighthouseschool-main', '.body_style_boxed { background-image:url('.esc_url($bg_image).') !important }' );

		// Merged styles
		if ( lighthouseschool_is_off(lighthouseschool_get_theme_option('debug_mode')) )
			wp_enqueue_style( 'lighthouseschool-styles', lighthouseschool_get_file_url('css/__styles.css') );

		// Custom colors
		if ( !is_customize_preview() && !isset($_GET['color_scheme']) && lighthouseschool_is_off(lighthouseschool_get_theme_option('debug_mode')) )
			wp_enqueue_style( 'lighthouseschool-colors', lighthouseschool_get_file_url('css/__colors.css') );
		else
			wp_add_inline_style( 'lighthouseschool-main', lighthouseschool_customizer_get_css() );

		// Add post nav background
		lighthouseschool_add_bg_in_post_nav();

		// Disable loading JQuery UI CSS
		wp_deregister_style('jquery_ui');
		wp_deregister_style('date-picker-css');


		// Enqueue scripts	
		//------------------------
		
		// Modernizr will load in head before other scripts and styles
		if ( in_array(substr(lighthouseschool_get_theme_option('blog_style'), 0, 7), array('gallery', 'portfol', 'masonry')) )
			wp_enqueue_script( 'modernizr', lighthouseschool_get_file_url('js/theme.gallery/modernizr.min.js'), array(), null, false );

		// Superfish Menu
		// Attention! To prevent duplicate this script in the plugin and in the menu, don't merge it!
		wp_enqueue_script( 'superfish', lighthouseschool_get_file_url('js/superfish.js'), array('jquery'), null, true );
		
		// Merged scripts
		if ( lighthouseschool_is_off(lighthouseschool_get_theme_option('debug_mode')) )
			wp_enqueue_script( 'lighthouseschool-init', lighthouseschool_get_file_url('js/__scripts.js'), array('jquery'), null, true );
		else {
			// Skip link focus
			wp_enqueue_script( 'skip-link-focus-fix', lighthouseschool_get_file_url('js/skip-link-focus-fix.js'), null, true );
			// Background video
			$header_video = lighthouseschool_get_header_video();
			if (!empty($header_video) && !lighthouseschool_is_inherit($header_video)) {
				if (lighthouseschool_is_youtube_url($header_video))
					wp_enqueue_script( 'tubular', lighthouseschool_get_file_url('js/jquery.tubular.js'), array('jquery'), null, true );
				else
					wp_enqueue_script( 'bideo', lighthouseschool_get_file_url('js/bideo.js'), array(), null, true );
			}
			// Theme scripts
			wp_enqueue_script( 'lighthouseschool-utils', lighthouseschool_get_file_url('js/_utils.js'), array('jquery'), null, true );
			wp_enqueue_script( 'lighthouseschool-init', lighthouseschool_get_file_url('js/_init.js'), array('jquery'), null, true );
		}

        // Load scripts for 'Masonry' layout
        if (substr(lighthouseschool_get_theme_option('blog_style'), 0, 7) == 'masonry') {
            wp_enqueue_script( 'imagesloaded' );
            wp_enqueue_script( 'masonry' );
            wp_enqueue_script( 'classie', lighthouseschool_get_file_url('js/theme.gallery/classie.min.js'), array(), null, true );
            wp_enqueue_script( 'lighthouseschool-gallery-script', lighthouseschool_get_file_url('js/theme.gallery/theme.gallery.js'), array(), null, true );
        }

        // Load scripts for both 'Gallery' and 'Portfolio' layouts!
        if ( substr(lighthouseschool_get_theme_option('blog_style'), 0, 7) == 'gallery' || substr(lighthouseschool_get_theme_option('blog_style'), 0, 9) == 'portfolio')  {
            wp_enqueue_script( 'imagesloaded' );
            wp_enqueue_script( 'masonry' );
            wp_enqueue_script( 'classie', lighthouseschool_get_file_url('js/theme.gallery/classie.min.js'), array(), null, true );
            wp_enqueue_script( 'lighthouseschool-gallery-script', lighthouseschool_get_file_url('js/theme.gallery/theme.gallery.js'), array(), null, true );
            wp_enqueue_script('jquery-ui-tabs', false, array('jquery', 'jquery-ui-core'), null, true);
        }
		
		// Comments
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		// Media elements library	
		if (lighthouseschool_get_theme_setting('use_mediaelements')) {
			wp_enqueue_style ( 'mediaelement' );
			wp_enqueue_style ( 'wp-mediaelement' );
			wp_enqueue_script( 'mediaelement' );
			wp_enqueue_script( 'wp-mediaelement' );
		}
	}
}

// Load child-theme stylesheet (if different) after all styles (with priorities 1000 and 1100)
if ( !function_exists( 'lighthouseschool_wp_scripts_child' ) ) {
	
	function lighthouseschool_wp_scripts_child() {
		$main_stylesheet = get_template_directory_uri() . '/style.css';
		$child_stylesheet = get_stylesheet_directory_uri() . '/style.css';
		if ($child_stylesheet != $main_stylesheet) {
			wp_enqueue_style( 'lighthouseschool-child', $child_stylesheet, array('lighthouseschool-main'), null );
		}
	}
}

// Add variables to the scripts in the frontend
if ( !function_exists( 'lighthouseschool_localize_scripts' ) ) {
	
	function lighthouseschool_localize_scripts() {

		$video = lighthouseschool_get_header_video();

		wp_localize_script( 'lighthouseschool-init', 'LIGHTHOUSESCHOOL_STORAGE', apply_filters( 'lighthouseschool_filter_localize_script', array(
			// AJAX parameters
			'ajax_url' => esc_url(admin_url('admin-ajax.php')),
			'ajax_nonce' => esc_attr(wp_create_nonce(admin_url('admin-ajax.php'))),
			
			// Site base url
			'site_url' => get_site_url(),
			'theme_url' => get_template_directory_uri(),
						
			// Site color scheme
			'site_scheme' => sprintf('scheme_%s', lighthouseschool_get_theme_option('color_scheme')),
			
			// User logged in
			'user_logged_in' => is_user_logged_in() ? true : false,
			
			// Window width to switch the site header to the mobile layout
			'mobile_layout_width' => 767,
			'mobile_device' => wp_is_mobile(),
						
			// Sidemenu options
			'menu_side_stretch' => lighthouseschool_get_theme_option('menu_side_stretch') > 0 ? true : false,
			'menu_side_icons' => lighthouseschool_get_theme_option('menu_side_icons') > 0 ? true : false,

			// Video background
			'background_video' => lighthouseschool_is_from_uploads($video) ? $video : '',

			// Video and Audio tag wrapper
			'use_mediaelements' => lighthouseschool_get_theme_setting('use_mediaelements') ? true : false,

			// Messages max length
			'comment_maxlength'	=> intval(lighthouseschool_get_theme_setting('comment_maxlength')),

			
			// Internal vars - do not change it!
			
			// Flag for review mechanism
			'admin_mode' => false,

			// E-mail mask
			'email_mask' => '^([a-zA-Z0-9_\\-]+\\.)*[a-zA-Z0-9_\\-]+@[a-z0-9_\\-]+(\\.[a-z0-9_\\-]+)*\\.[a-z]{2,6}$',
			
			// Strings for translation
			'strings' => array(
					'ajax_error'		=> esc_html__('Invalid server answer!', 'lighthouseschool'),
					'error_global'		=> esc_html__('Error data validation!', 'lighthouseschool'),
					'name_empty' 		=> esc_html__("The name can't be empty", 'lighthouseschool'),
					'name_long'			=> esc_html__('Too long name', 'lighthouseschool'),
					'email_empty'		=> esc_html__('Too short (or empty) email address', 'lighthouseschool'),
					'email_long'		=> esc_html__('Too long email address', 'lighthouseschool'),
					'email_not_valid'	=> esc_html__('Invalid email address', 'lighthouseschool'),
					'text_empty'		=> esc_html__("The message text can't be empty", 'lighthouseschool'),
					'text_long'			=> esc_html__('Too long message text', 'lighthouseschool')
					)
			))
		);
	}
}

// Load responsive styles (priority 2000 - load it after main styles and plugins custom styles)
if ( !function_exists( 'lighthouseschool_wp_scripts_responsive' ) ) {
	
	function lighthouseschool_wp_scripts_responsive() {
		wp_enqueue_style( 'lighthouseschool-responsive', lighthouseschool_get_file_url('css/responsive.css') );
	}
}

//  Add meta tags and inline scripts in the header for frontend
if (!function_exists('lighthouseschool_wp_head')) {
	
	function lighthouseschool_wp_head() {
		?>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<meta name="format-detection" content="telephone=no">
		<link rel="profile" href="//gmpg.org/xfn/11">
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
		<?php
	}
}

// Add theme specified classes to the body
if ( !function_exists('lighthouseschool_add_body_classes') ) {
	
	function lighthouseschool_add_body_classes( $classes ) {
		$classes[] = 'body_tag';	// Need for the .scheme_self
		$classes[] = 'scheme_' . esc_attr(lighthouseschool_get_theme_option('color_scheme'));

		$blog_mode = lighthouseschool_storage_get('blog_mode');
		$classes[] = 'blog_mode_' . esc_attr($blog_mode);
		$classes[] = 'body_style_' . esc_attr(lighthouseschool_get_theme_option('body_style'));

		if (in_array($blog_mode, array('post', 'page'))) {
			$classes[] = 'is_single';
		} else {
			$classes[] = ' is_stream';
			$classes[] = 'blog_style_'.esc_attr(lighthouseschool_get_theme_option('blog_style'));
			if (lighthouseschool_storage_get('blog_template') > 0)
				$classes[] = 'blog_template';
		}
		
		if (lighthouseschool_sidebar_present()) {
			$classes[] = 'sidebar_show sidebar_' . esc_attr(lighthouseschool_get_theme_option('sidebar_position')) ;
		} else {
			$classes[] = 'sidebar_hide';
			if (lighthouseschool_is_on(lighthouseschool_get_theme_option('expand_content')))
				 $classes[] = 'expand_content';
		}
		
		if (lighthouseschool_is_on(lighthouseschool_get_theme_option('remove_margins')))
			 $classes[] = 'remove_margins';

		if ( is_front_page() 
			&& lighthouseschool_is_on(lighthouseschool_get_theme_option('front_page_enabled')) 
			&& ($bg_image = lighthouseschool_get_theme_option('front_page_bg_image')) != '' )
			$classes[] = 'with_bg_image';

		$classes[] = 'header_style_' . esc_attr(lighthouseschool_get_theme_option("header_style"));
		$classes[] = 'header_position_' . esc_attr(lighthouseschool_get_theme_option("header_position"));

		$classes[] = 'trx_addons_' . esc_attr(lighthouseschool_exists_trx_addons() ? 'present' : 'absent');

		$menu_style= lighthouseschool_get_theme_option("menu_style");
		$classes[] = 'menu_style_' . esc_attr($menu_style) . (in_array($menu_style, array('left', 'right'))	? ' menu_style_side' : '');
		$classes[] = 'no_layout';
		
		return $classes;
	}
}
	
// Load current page/post customization (if present)
if ( !function_exists( 'lighthouseschool_wp_footer' ) ) {
	
	//and add_action('admin_footer', 'lighthouseschool_wp_footer');
	function lighthouseschool_wp_footer() {
		if (($css = lighthouseschool_get_inline_css()) != '') {
			wp_enqueue_style(  'lighthouseschool-inline-styles',  lighthouseschool_get_file_url('css/__inline.css') );
			wp_add_inline_style( 'lighthouseschool-inline-styles', $css );
		}
	}
}


/**
 * Fire the wp_body_open action.
 *
 * Added for backwards compatibility to support pre 5.2.0 WordPress versions.
 */
if ( ! function_exists( 'wp_body_open' ) ) {
	function wp_body_open() {
		/**
		 * Triggered after the opening <body> tag.
		 */
		do_action('wp_body_open');
	}
}


//-------------------------------------------------------
//-- Sidebars and widgets
//-------------------------------------------------------

// Register widgetized areas
if ( !function_exists('lighthouseschool_register_sidebars') ) {
	
	function lighthouseschool_register_sidebars() {
		$sidebars = lighthouseschool_get_sidebars();
		if (is_array($sidebars) && count($sidebars) > 0) {
			foreach ($sidebars as $id=>$sb) {
				register_sidebar( array(
										'name'          => $sb['name'],
										'description'   => $sb['description'],
										'id'            => esc_attr($id),
										'before_widget' => '<aside id="%1$s" class="widget %2$s">',
										'after_widget'  => '</aside>',
										'before_title'  => '<h5 class="widget_title">',
										'after_title'   => '</h5>'
										)
								);
			}
		}
	}
}

// Return theme specific widgetized areas
if ( !function_exists('lighthouseschool_get_sidebars') ) {
	function lighthouseschool_get_sidebars() {
		$list = apply_filters('lighthouseschool_filter_list_sidebars', array(
			'sidebar_widgets'		=> array(
							'name' => esc_html__('Sidebar Widgets', 'lighthouseschool'),
							'description' => esc_html__('Widgets to be shown on the main sidebar', 'lighthouseschool')
							),
			'header_widgets'		=> array(
							'name' => esc_html__('Header Widgets', 'lighthouseschool'),
							'description' => esc_html__('Widgets to be shown at the top of the page (in the page header area)', 'lighthouseschool')
							),
			'above_page_widgets'	=> array(
							'name' => esc_html__('Top Page Widgets', 'lighthouseschool'),
							'description' => esc_html__('Widgets to be shown below the header, but above the content and sidebar', 'lighthouseschool')
							),
			'above_content_widgets' => array(
							'name' => esc_html__('Above Content Widgets', 'lighthouseschool'),
							'description' => esc_html__('Widgets to be shown above the content, near the sidebar', 'lighthouseschool')
							),
			'below_content_widgets' => array(
							'name' => esc_html__('Below Content Widgets', 'lighthouseschool'),
							'description' => esc_html__('Widgets to be shown below the content, near the sidebar', 'lighthouseschool')
							),
			'below_page_widgets' 	=> array(
							'name' => esc_html__('Bottom Page Widgets', 'lighthouseschool'),
							'description' => esc_html__('Widgets to be shown below the content and sidebar, but above the footer', 'lighthouseschool')
							),
			'footer_widgets'		=> array(
							'name' => esc_html__('Footer Widgets', 'lighthouseschool'),
							'description' => esc_html__('Widgets to be shown at the bottom of the page (in the page footer area)', 'lighthouseschool')
							)
			)
		);
		return $list;
	}
}


//-------------------------------------------------------
//-- Theme fonts
//-------------------------------------------------------

// Return links for all theme fonts
if ( !function_exists('lighthouseschool_theme_fonts_links') ) {
	function lighthouseschool_theme_fonts_links() {
		$links = array();
		
		/*
		Translators: If there are characters in your language that are not supported
		by chosen font(s), translate this to 'off'. Do not translate into your own language.
		*/
		$google_fonts_enabled = ( 'off' !== esc_html_x( 'on', 'Google fonts: on or off', 'lighthouseschool' ) );
		$custom_fonts_enabled = ( 'off' !== esc_html_x( 'on', 'Custom fonts (included in the theme): on or off', 'lighthouseschool' ) );
		
		if ( ($google_fonts_enabled || $custom_fonts_enabled) && !lighthouseschool_storage_empty('load_fonts') ) {
			$load_fonts = lighthouseschool_storage_get('load_fonts');
			if (count($load_fonts) > 0) {
				$google_fonts = '';
				foreach ($load_fonts as $font) {
					$slug = lighthouseschool_get_load_fonts_slug($font['name']);
					$url  = lighthouseschool_get_file_url( sprintf('css/font-face/%s/stylesheet.css', $slug));
					if ($url != '') {
						if ($custom_fonts_enabled) {
							$links[$slug] = $url;
						}
					} else {
						if ($google_fonts_enabled) {
							$google_fonts .= ($google_fonts ? '|' : '') 
											. str_replace(' ', '+', $font['name'])
											. ':' 
											. (empty($font['styles']) ? '400,400italic,700,700italic' : $font['styles']);
						}
					}
				}
				if ($google_fonts && $google_fonts_enabled) {
					$links['google_fonts'] = sprintf('//fonts.googleapis.com/css?family=%s&subset=%s', $google_fonts, lighthouseschool_get_theme_option('load_fonts_subset'));
				}
			}
		}
		return $links;
	}
}

// Return links for WP Editor
if ( !function_exists('lighthouseschool_theme_fonts_for_editor') ) {
	function lighthouseschool_theme_fonts_for_editor() {
		$links = array_values(lighthouseschool_theme_fonts_links());
		if (is_array($links) && count($links) > 0) {
			for ($i=0; $i<count($links); $i++) {
				$links[$i] = str_replace(',', '%2C', $links[$i]);
			}
		}
		return $links;
	}
}


//-------------------------------------------------------
//-- The Excerpt
//-------------------------------------------------------
if ( !function_exists('lighthouseschool_excerpt_length') ) {
	function lighthouseschool_excerpt_length( $length ) {
		return max(1, lighthouseschool_get_theme_option('excerpt_length'));
	}
}

if ( !function_exists('lighthouseschool_excerpt_more') ) {
	function lighthouseschool_excerpt_more( $more ) {
		return '&hellip;';
	}
}



//-------------------------------------------------------
//-- Include theme (or child) PHP-files
//-------------------------------------------------------

require_once LIGHTHOUSESCHOOL_THEME_DIR . 'includes/utils.php';
require_once LIGHTHOUSESCHOOL_THEME_DIR . 'includes/storage.php';
require_once LIGHTHOUSESCHOOL_THEME_DIR . 'includes/lists.php';
require_once LIGHTHOUSESCHOOL_THEME_DIR . 'includes/wp.php';

if (is_admin()) {
	require_once LIGHTHOUSESCHOOL_THEME_DIR . 'includes/tgmpa/class-tgm-plugin-activation.php';
	require_once LIGHTHOUSESCHOOL_THEME_DIR . 'includes/admin.php';
}

require_once LIGHTHOUSESCHOOL_THEME_DIR . 'theme-options/theme.customizer.php';

require_once LIGHTHOUSESCHOOL_THEME_DIR . 'front-page/front-page.options.php';

require_once LIGHTHOUSESCHOOL_THEME_DIR . 'theme-specific/theme.tags.php';
require_once LIGHTHOUSESCHOOL_THEME_DIR . 'theme-specific/theme.hovers/theme.hovers.php';
require_once LIGHTHOUSESCHOOL_THEME_DIR . 'theme-specific/theme.about/theme.about.php';


// Plugins support
if (is_array($LIGHTHOUSESCHOOL_STORAGE['required_plugins']) && count($LIGHTHOUSESCHOOL_STORAGE['required_plugins']) > 0) {
	foreach ($LIGHTHOUSESCHOOL_STORAGE['required_plugins'] as $plugin_slug => $plugin_name) {
		$plugin_slug = lighthouseschool_esc($plugin_slug);
		$plugin_path = LIGHTHOUSESCHOOL_THEME_DIR . sprintf('plugins/%s/%s.php', $plugin_slug, $plugin_slug);
		if (file_exists($plugin_path)) { require_once $plugin_path; }
	}
}
?>