<?php
/**
 * Setup theme-specific fonts and colors
 *
 * @package WordPress
 * @subpackage LIGHTHOUSESCHOOL
 * @since LIGHTHOUSESCHOOL 1.0.22
 */

if (!defined("LIGHTHOUSESCHOOL_THEME_FREE")) define("LIGHTHOUSESCHOOL_THEME_FREE", false);

// Theme storage
$LIGHTHOUSESCHOOL_STORAGE = array(
	// Theme required plugin's slugs
	'required_plugins' => array_merge(

		// List of plugins for both - FREE and PREMIUM versions
		//-----------------------------------------------------
		array(
			// Required plugins
			// DON'T COMMENT OR REMOVE NEXT LINES!
			'trx_addons'					=> esc_html__('ThemeREX Addons', 'lighthouseschool'),
			
			// Recommended (supported) plugins
			// If plugin not need - comment (or remove) it
			'contact-form-7'				=> esc_html__('Contact Form 7', 'lighthouseschool'),
			'elegro-payment'				=> esc_html__('elegro Crypto Payment', 'lighthouseschool'),
			'give'							=> esc_html__('GiveWP (Donations)', 'lighthouseschool'),
            'instagram-feed'			    => esc_html__('Smash Balloon Instagram Feed', 'lighthouseschool'),
			'mailchimp-for-wp'				=> esc_html__('MailChimp for WP', 'lighthouseschool'),
			'woocommerce'					=> esc_html__('WooCommerce', 'lighthouseschool'),
			'wp-gdpr-compliance'			=> esc_html__('Cookie Information', 'lighthouseschool')
		),

		// List of plugins for PREMIUM version only
		//-----------------------------------------------------
		LIGHTHOUSESCHOOL_THEME_FREE ? array() : array(

			// Recommended (supported) plugins
			// If plugin not need - comment (or remove) it
			'js_composer'					=> esc_html__('WPBakery Page Builder', 'lighthouseschool'),
			'vc-extensions-bundle'		=> esc_html__('WPBakery Page Builder extensions bundle', 'lighthouseschool'),
			'essential-grid'				=> esc_html__('Essential Grid', 'lighthouseschool'),			
			'the-events-calendar'		=> esc_html__('The Events Calendar', 'lighthouseschool'),
            'trx_updater'				   => esc_html__('ThemeREX Updater', 'lighthouseschool'),
			'revslider'						=> esc_html__('Revolution Slider', 'lighthouseschool'),
        )
	),
	
	// Theme-specific URLs (will be escaped in place of the output)
	'theme_demo_url' => '//lighthouse.ancorathemes.com/',
	'theme_doc_url' => 'http://lighthouse.ancorathemes.com/doc',
	'theme_support_url' => 'https://themerex.net/support/',
	'theme_download_url' => 'https://themeforest.net/item/lighthouse-school-for-kids-with-special-needs/20811397'
);

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

if ( !function_exists('lighthouseschool_customizer_theme_setup1') ) {
	add_action( 'after_setup_theme', 'lighthouseschool_customizer_theme_setup1', 1 );
	function lighthouseschool_customizer_theme_setup1() {

		// -----------------------------------------------------------------
		// -- ONLY FOR PROGRAMMERS, NOT FOR CUSTOMER
		// -- Internal theme settings
		// -----------------------------------------------------------------
		lighthouseschool_storage_set('settings', array(
			
			'duplicate_options'		=> 'child',		// none  - use separate options for template and child-theme
													// child - duplicate theme options from the main theme to the child-theme only
													// both  - sinchronize changes in the theme options between main and child themes
			
			'custmize_refresh'		=> 'auto',		// Refresh method for preview area in the Appearance - Customize:
													// auto - refresh preview area on change each field with Theme Options
													// manual - refresh only obn press button 'Refresh' at the top of Customize frame
		
			'max_load_fonts'		=> 5,			// Max fonts number to load from Google fonts or from uploaded fonts
		
			'comment_maxlength'		=> 1000,		// Max length of the message from contact form

			'comment_after_name'	=> true,		// Place 'comment' field before the 'name' and 'email'
			
			'socials_type'			=> 'icons',		// Type of socials:
													// icons - use font icons to present social networks
													// images - use images from theme's folder trx_addons/css/icons.png
			
			'icons_type'			=> 'icons',		// Type of other icons:
													// icons - use font icons to present icons
													// images - use images from theme's folder trx_addons/css/icons.png
			
			'icons_selector'		=> 'internal',	// Icons selector in the shortcodes:
													// vc (default) - standard VC icons selector (very slow and don't support images)
													// internal - internal popup with plugin's or theme's icons list (fast)
			'disable_jquery_ui'		=> false,		// Prevent loading custom jQuery UI libraries in the third-party plugins
		
			'use_mediaelements'		=> true,		// Load script "Media Elements" to play video and audio
			
			'tgmpa_upload'			=> false,		// Allow upload not pre-packaged plugins via TGMPA
			
			'allow_theme_layouts'	=> false		// Include theme's default headers and footers to the list after custom layouts
													// or leave in the list only custom layouts
		));


		// -----------------------------------------------------------------
		// -- Theme fonts (Google and/or custom fonts)
		// -----------------------------------------------------------------
		
		// Fonts to load when theme start
		// It can be Google fonts or uploaded fonts, placed in the folder /css/font-face/font-name inside the theme folder
		// Attention! Font's folder must have name equal to the font's name, with spaces replaced on the dash '-'
		
		lighthouseschool_storage_set('load_fonts', array(
			// Google font
			array(
				'name'	 => 'Nunito',
				'family' => 'sans-serif',
				'styles' => '600,600italic,700,700italic,800,800italic,900,900italic'
				),
			array(
				'name'   => 'Supermercado One',
				'family' => 'cursive',
                'styles' => '400'
				),
            array(
                'name'   => 'Meddon',
                'family' => 'cursive',
                'styles' => '400'
            )
		));
		
		// Characters subset for the Google fonts. Available values are: latin,latin-ext,cyrillic,cyrillic-ext,greek,greek-ext,vietnamese
		lighthouseschool_storage_set('load_fonts_subset', 'latin,latin-ext');
		
		// Settings of the main tags
		lighthouseschool_storage_set('theme_fonts', array(
			'p' => array(
				'title'				=> esc_html__('Main text', 'lighthouseschool'),
				'description'		=> esc_html__('Font settings of the main text of the site', 'lighthouseschool'),
				'font-family'		=> '"Nunito",sans-serif',
				'font-size' 		=> '1em',
				'font-weight'		=> '600',
				'font-style'		=> 'normal',
				'line-height'		=> '1.57',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'none',
				'letter-spacing'	=> '',
				'margin-top'		=> '0em',
				'margin-bottom'		=> '1.6em'
				),
			'h1' => array(
				'title'				=> esc_html__('Heading 1', 'lighthouseschool'),
				'font-family'		=> '"Supermercado One",cursive',
				'font-size' 		=> '6.875em',
				'font-weight'		=> '400',
				'font-style'		=> 'normal',
				'line-height'		=> '0.91',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'none',
				'letter-spacing'	=> '-3.2px',
				'margin-top'		=> '0.9583em',
				'margin-bottom'		=> '0.355em'
				),
			'h2' => array(
				'title'				=> esc_html__('Heading 2', 'lighthouseschool'),
				'font-family'		=> '"Supermercado One",cursive',
				'font-size' 		=> '5.938em',
				'font-weight'		=> '400',
				'font-style'		=> 'normal',
				'line-height'		=> '1',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'none',
				'letter-spacing'	=> '-2.8px',
				'margin-top'		=> '0.75em',
				'margin-bottom'		=> '0.42em'
				),
			'h3' => array(
				'title'				=> esc_html__('Heading 3', 'lighthouseschool'),
				'font-family'		=> '"Supermercado One",cursive',
				'font-size' 		=> '5.313em',
				'font-weight'		=> '400',
				'font-style'		=> 'normal',
				'line-height'		=> '1',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'none',
				'letter-spacing'	=> '-2.6px',
				'margin-top'		=> '0.85em',
				'margin-bottom'		=> '0.44em'
				),
			'h4' => array(
				'title'				=> esc_html__('Heading 4', 'lighthouseschool'),
				'font-family'		=> '"Nunito",sans-serif',
				'font-size' 		=> '3.75em',
				'font-weight'		=> '800',
				'font-style'		=> 'normal',
				'line-height'		=> '1',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'uppercase',
				'letter-spacing'	=> '1px',
				'margin-top'		=> '1.45em',
				'margin-bottom'		=> '0.575em'
				),
			'h5' => array(
				'title'				=> esc_html__('Heading 5', 'lighthouseschool'),
				'font-family'		=> '"Nunito",sans-serif',
				'font-size' 		=> '2.813em',
				'font-weight'		=> '800',
				'font-style'		=> 'normal',
				'line-height'		=> '1',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'uppercase',
				'letter-spacing'	=> '0.6px',
				'margin-top'		=> '2.075em',
				'margin-bottom'		=> '0.925em'
				),
			'h6' => array(
				'title'				=> esc_html__('Heading 6', 'lighthouseschool'),
				'font-family'		=> '"Meddon", cursive',
				'font-size' 		=> '2.188em',
				'font-weight'		=> '400',
				'font-style'		=> 'normal',
				'line-height'		=> '1.14',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'none',
				'letter-spacing'	=> '-1px',
				'margin-top'		=> '1.6em',
				'margin-bottom'		=> '1.15em'
				),
			'logo' => array(
				'title'				=> esc_html__('Logo text', 'lighthouseschool'),
				'description'		=> esc_html__('Font settings of the text case of the logo', 'lighthouseschool'),
				'font-family'		=> '"Supermercado One",cursive',
				'font-size' 		=> '1.875em',
				'font-weight'		=> '400',
				'font-style'		=> 'normal',
				'line-height'		=> '1.33',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'none',
				'letter-spacing'	=> '-1px'
				),
			'button' => array(
				'title'				=> esc_html__('Buttons', 'lighthouseschool'),
				'font-family'		=> '"Nunito",sans-serif',
				'font-size' 		=> '14px',
				'font-weight'		=> '800',
				'font-style'		=> 'normal',
				'line-height'		=> 'normal',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'uppercase',
				'letter-spacing'	=> '0.4px'
				),
			'input' => array(
				'title'				=> esc_html__('Input fields', 'lighthouseschool'),
				'description'		=> esc_html__('Font settings of the input fields, dropdowns and textareas', 'lighthouseschool'),
				'font-family'		=> 'inherit',
				'font-size' 		=> '1em',
				'font-weight'		=> '400',
				'font-style'		=> 'normal',
				'line-height'		=> 'normal',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'none',
				'letter-spacing'	=> '0px'
				),
			'info' => array(
				'title'				=> esc_html__('Post meta', 'lighthouseschool'),
				'description'		=> esc_html__('Font settings of the post meta: date, counters, share, etc.', 'lighthouseschool'),
				'font-family'		=> 'inherit',
				'font-size' 		=> '14px',
				'font-weight'		=> '800',
				'font-style'		=> 'normal',
				'line-height'		=> '1.5em',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'uppercase',
				'letter-spacing'	=> '0px',
				'margin-top'		=> '0.4em',
				'margin-bottom'		=> ''
				),
			'menu' => array(
				'title'				=> esc_html__('Main menu', 'lighthouseschool'),
				'description'		=> esc_html__('Font settings of the main menu items', 'lighthouseschool'),
				'font-family'		=> '"Nunito",sans-serif',
				'font-size' 		=> '14px',
				'font-weight'		=> '800',
				'font-style'		=> 'normal',
				'line-height'		=> 'normal',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'uppercase',
				'letter-spacing'	=> '0px'
				),
			'submenu' => array(
				'title'				=> esc_html__('Dropdown menu', 'lighthouseschool'),
				'description'		=> esc_html__('Font settings of the dropdown menu items', 'lighthouseschool'),
				'font-family'		=> '"Nunito",sans-serif',
				'font-size' 		=> '14px',
				'font-weight'		=> '800',
				'font-style'		=> 'normal',
				'line-height'		=> 'normal',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'uppercase',
				'letter-spacing'	=> '0px'
				),
			'special' => array(
				'title'				=> esc_html__('Accent blocks', 'lighthouseschool'),
				'font-family'		=> '"Meddon", cursive',
				'font-size' 		=> '2.188em',
				'font-weight'		=> '400',
				'font-style'		=> 'normal',
				'line-height'		=> '1.14',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'none',
				'letter-spacing'	=> '-1px',
				'margin-top'		=> '1.6em',
				'margin-bottom'		=> '1.15em'
			),
		));
		
		
		// -----------------------------------------------------------------
		// -- Theme colors for customizer
		// -- Attention! Inner scheme must be last in the array below
		// -----------------------------------------------------------------
		lighthouseschool_storage_set('scheme_color_groups', array(
			'main'	=> array(
							'title'			=> esc_html__('Main', 'lighthouseschool'),
							'description'	=> esc_html__('Colors of the main content area', 'lighthouseschool')
							),
			'alter'	=> array(
							'title'			=> esc_html__('Alter', 'lighthouseschool'),
							'description'	=> esc_html__('Colors of the alternative blocks (sidebars, etc.)', 'lighthouseschool')
							),
			'extra'	=> array(
							'title'			=> esc_html__('Extra', 'lighthouseschool'),
							'description'	=> esc_html__('Colors of the extra blocks (dropdowns, price blocks, table headers, etc.)', 'lighthouseschool')
							),
			'inverse' => array(
							'title'			=> esc_html__('Inverse', 'lighthouseschool'),
							'description'	=> esc_html__('Colors of the inverse blocks - when link color used as background of the block (dropdowns, blockquotes, etc.)', 'lighthouseschool')
							),
			'input'	=> array(
							'title'			=> esc_html__('Input', 'lighthouseschool'),
							'description'	=> esc_html__('Colors of the form fields (text field, textarea, select, etc.)', 'lighthouseschool')
							),
			)
		);
		lighthouseschool_storage_set('scheme_color_names', array(
			'bg_color'	=> array(
							'title'			=> esc_html__('Background color', 'lighthouseschool'),
							'description'	=> esc_html__('Background color of this block in the normal state', 'lighthouseschool')
							),
			'bg_hover'	=> array(
							'title'			=> esc_html__('Background hover', 'lighthouseschool'),
							'description'	=> esc_html__('Background color of this block in the hovered state', 'lighthouseschool')
							),
			'bd_color'	=> array(
							'title'			=> esc_html__('Border color', 'lighthouseschool'),
							'description'	=> esc_html__('Border color of this block in the normal state', 'lighthouseschool')
							),
			'bd_hover'	=>  array(
							'title'			=> esc_html__('Border hover', 'lighthouseschool'),
							'description'	=> esc_html__('Border color of this block in the hovered state', 'lighthouseschool')
							),
			'text'		=> array(
							'title'			=> esc_html__('Text', 'lighthouseschool'),
							'description'	=> esc_html__('Color of the plain text inside this block', 'lighthouseschool')
							),
			'text_dark'	=> array(
							'title'			=> esc_html__('Text dark', 'lighthouseschool'),
							'description'	=> esc_html__('Color of the dark text (bold, header, etc.) inside this block', 'lighthouseschool')
							),
			'text_light'=> array(
							'title'			=> esc_html__('Text light', 'lighthouseschool'),
							'description'	=> esc_html__('Color of the light text (post meta, etc.) inside this block', 'lighthouseschool')
							),
			'text_link'	=> array(
							'title'			=> esc_html__('Link', 'lighthouseschool'),
							'description'	=> esc_html__('Color of the links inside this block', 'lighthouseschool')
							),
			'text_hover'=> array(
							'title'			=> esc_html__('Link hover', 'lighthouseschool'),
							'description'	=> esc_html__('Color of the hovered state of links inside this block', 'lighthouseschool')
							),
			'text_link2'=> array(
							'title'			=> esc_html__('Link 2', 'lighthouseschool'),
							'description'	=> esc_html__('Color of the accented texts (areas) inside this block', 'lighthouseschool')
							),
			'text_hover2'=> array(
							'title'			=> esc_html__('Link 2 hover', 'lighthouseschool'),
							'description'	=> esc_html__('Color of the hovered state of accented texts (areas) inside this block', 'lighthouseschool')
							),
			'text_link3'=> array(
							'title'			=> esc_html__('Link 3', 'lighthouseschool'),
							'description'	=> esc_html__('Color of the other accented texts (buttons) inside this block', 'lighthouseschool')
							),
			'text_hover3'=> array(
							'title'			=> esc_html__('Link 3 hover', 'lighthouseschool'),
							'description'	=> esc_html__('Color of the hovered state of other accented texts (buttons) inside this block', 'lighthouseschool')
							)
			)
		);
		lighthouseschool_storage_set('schemes', array(
		
			// Color scheme: 'default'
			'default' => array(
				'title'	 => esc_html__('Default', 'lighthouseschool'),
				'colors' => array(
					
					// Whole block border and background
					'bg_color'			=> '#ffffff',
					'bd_color'			=> '#e5e5e5',
		
					// Text and links colors
					'text'				=> '#a1a1a1', //+
					'text_light'		=> '#b7b7b7',
					'text_dark'			=> '#162c5a', //+
					'text_link'			=> '#43cec6', //+
					'text_hover'		=> '#ffd223', //+
					'text_link2'		=> '#80d572',
					'text_hover2'		=> '#8be77c',
					'text_link3'		=> '#ddb837',
					'text_hover3'		=> '#eec432',
		
					// Alternative blocks (sidebar, tabs, alternative blocks, etc.)
					'alter_bg_color'	=> '#f7f7f7', //+
					'alter_bg_hover'	=> '#e6e8eb',
					'alter_bd_color'	=> '#efefef', //+
					'alter_bd_hover'	=> '#dadada',
					'alter_text'		=> '#333333',
					'alter_light'		=> '#b7b7b7',
					'alter_dark'		=> '#162c5a', //+
					'alter_link'		=> '#fe7259',
					'alter_hover'		=> '#72cfd5',
					'alter_link2'		=> '#8be77c',
					'alter_hover2'		=> '#80d572',
					'alter_link3'		=> '#eec432',
					'alter_hover3'		=> '#ddb837',
		
					// Extra blocks (submenu, tabs, color blocks, etc.)
					'extra_bg_color'	=> '#1e1d22',
					'extra_bg_hover'	=> '#28272e',
					'extra_bd_color'	=> '#313131',
					'extra_bd_hover'	=> '#3d3d3d',
					'extra_text'		=> '#bfbfbf',
					'extra_light'		=> '#afafaf',
					'extra_dark'		=> '#ffffff',
					'extra_link'		=> '#72cfd5',
					'extra_hover'		=> '#fe7259',
					'extra_link2'		=> '#80d572',
					'extra_hover2'		=> '#8be77c',
					'extra_link3'		=> '#ddb837',
					'extra_hover3'		=> '#eec432',
		
					// Input fields (form's fields and textarea)
					'input_bg_color'	=> '#f7f7f7', //+
					'input_bg_hover'	=> '#f7f7f7', //+
					'input_bd_color'	=> '#f7f7f7', //+
					'input_bd_hover'	=> '#ffd223', //+
					'input_text'		=> '#a1a1a1', //+
					'input_light'		=> '#a1a1a1', //+
					'input_dark'		=> '#a1a1a1', //+
					
					// Inverse blocks (text and links on the 'text_link' background)
					'inverse_bd_color'	=> '#67bcc1',
					'inverse_bd_hover'	=> '#5aa4a9',
					'inverse_text'		=> '#1d1d1d',
					'inverse_light'		=> '#333333',
					'inverse_dark'		=> '#000000',
					'inverse_link'		=> '#ffffff',
					'inverse_hover'		=> '#1d1d1d'
				)
			),
		
			// Color scheme: 'dark'
			'dark' => array(
				'title'  => esc_html__('Dark', 'lighthouseschool'),
				'colors' => array(
					
					// Whole block border and background
					'bg_color'			=> '#0e0d12',
					'bd_color'			=> '#1c1b1f',
		
					// Text and links colors
					'text'				=> '#b7b7b7',
					'text_light'		=> '#5f5f5f',
					'text_dark'			=> '#ffffff',
                    'text_link'			=> '#43cec6', //+
                    'text_hover'		=> '#ffd223', //+
					'text_link2'		=> '#80d572',
					'text_hover2'		=> '#8be77c',
					'text_link3'		=> '#ddb837',
					'text_hover3'		=> '#eec432',

					// Alternative blocks (sidebar, tabs, alternative blocks, etc.)
					'alter_bg_color'	=> '#1e1d22',
					'alter_bg_hover'	=> '#28272e',
					'alter_bd_color'	=> '#313131',
					'alter_bd_hover'	=> '#3d3d3d',
					'alter_text'		=> '#a6a6a6',
					'alter_light'		=> '#5f5f5f',
					'alter_dark'		=> '#ffffff',
					'alter_link'		=> '#ffaa5f',
					'alter_hover'		=> '#fe7259',
					'alter_link2'		=> '#8be77c',
					'alter_hover2'		=> '#80d572',
					'alter_link3'		=> '#eec432',
					'alter_hover3'		=> '#ddb837',

					// Extra blocks (submenu, tabs, color blocks, etc.)
					'extra_bg_color'	=> '#1e1d22',
					'extra_bg_hover'	=> '#28272e',
					'extra_bd_color'	=> '#313131',
					'extra_bd_hover'	=> '#3d3d3d',
					'extra_text'		=> '#a6a6a6',
					'extra_light'		=> '#5f5f5f',
					'extra_dark'		=> '#ffffff',
					'extra_link'		=> '#ffaa5f',
					'extra_hover'		=> '#fe7259',
					'extra_link2'		=> '#80d572',
					'extra_hover2'		=> '#8be77c',
					'extra_link3'		=> '#ddb837',
					'extra_hover3'		=> '#eec432',

					// Input fields (form's fields and textarea)
					'input_bg_color'	=> '#2e2d32',
					'input_bg_hover'	=> '#2e2d32',
					'input_bd_color'	=> '#2e2d32',
					'input_bd_hover'	=> '#353535',
					'input_text'		=> '#b7b7b7',
					'input_light'		=> '#5f5f5f',
					'input_dark'		=> '#ffffff',
					
					// Inverse blocks (text and links on the 'text_link' background)
					'inverse_bd_color'	=> '#e36650',
					'inverse_bd_hover'	=> '#cb5b47',
					'inverse_text'		=> '#1d1d1d',
					'inverse_light'		=> '#5f5f5f',
					'inverse_dark'		=> '#162c5a', //+
					'inverse_link'		=> '#ffffff',
					'inverse_hover'		=> '#1d1d1d'
				)
			)
		
		));
		
		// Simple schemes substitution
		lighthouseschool_storage_set('schemes_simple', array(
			// Main color	// Slave elements and it's darkness koef.
			'text_link'		=> array('alter_hover' => 1,	'extra_link' => 1, 'inverse_bd_color' => 0.85, 'inverse_bd_hover' => 0.7),
			'text_hover'	=> array('alter_link' => 1,		'extra_hover' => 1),
			'text_link2'	=> array('alter_hover2' => 1,	'extra_link2' => 1),
			'text_hover2'	=> array('alter_link2' => 1,	'extra_hover2' => 1),
			'text_link3'	=> array('alter_hover3' => 1,	'extra_link3' => 1),
			'text_hover3'	=> array('alter_link3' => 1,	'extra_hover3' => 1)
		));
	}
}

			
// Additional (calculated) theme-specific colors
// Attention! Don't forget setup custom colors also in the theme.customizer.color-scheme.js
if (!function_exists('lighthouseschool_customizer_add_theme_colors')) {
	function lighthouseschool_customizer_add_theme_colors($colors) {
		if (substr($colors['text'], 0, 1) == '#') {
			$colors['bg_color_0']  = lighthouseschool_hex2rgba( $colors['bg_color'], 0 );
			$colors['bg_color_02']  = lighthouseschool_hex2rgba( $colors['bg_color'], 0.2 );
			$colors['bg_color_07']  = lighthouseschool_hex2rgba( $colors['bg_color'], 0.7 );
			$colors['bg_color_08']  = lighthouseschool_hex2rgba( $colors['bg_color'], 0.8 );
			$colors['bg_color_09']  = lighthouseschool_hex2rgba( $colors['bg_color'], 0.9 );
			$colors['alter_bg_color_07']  = lighthouseschool_hex2rgba( $colors['alter_bg_color'], 0.7 );
			$colors['alter_bg_color_04']  = lighthouseschool_hex2rgba( $colors['alter_bg_color'], 0.4 );
			$colors['alter_bg_color_02']  = lighthouseschool_hex2rgba( $colors['alter_bg_color'], 0.2 );
			$colors['alter_bd_color_02']  = lighthouseschool_hex2rgba( $colors['alter_bd_color'], 0.2 );
			$colors['extra_bg_color_07']  = lighthouseschool_hex2rgba( $colors['extra_bg_color'], 0.7 );
			$colors['text_dark_07']  = lighthouseschool_hex2rgba( $colors['text_dark'], 0.7 );
			$colors['text_dark_05']  = lighthouseschool_hex2rgba( $colors['text_dark'], 0.5 );
			$colors['text_link_02']  = lighthouseschool_hex2rgba( $colors['text_link'], 0.2 );
			$colors['text_link_07']  = lighthouseschool_hex2rgba( $colors['text_link'], 0.7 );
			$colors['text_link_blend'] = lighthouseschool_hsb2hex(lighthouseschool_hex2hsb( $colors['text_link'], 2, -5, 5 ));
			$colors['alter_link_blend'] = lighthouseschool_hsb2hex(lighthouseschool_hex2hsb( $colors['alter_link'], 2, -5, 5 ));
		} else {
			$colors['bg_color_0'] = '{{ data.bg_color_0 }}';
			$colors['bg_color_02'] = '{{ data.bg_color_02 }}';
			$colors['bg_color_07'] = '{{ data.bg_color_07 }}';
			$colors['bg_color_08'] = '{{ data.bg_color_08 }}';
			$colors['bg_color_09'] = '{{ data.bg_color_09 }}';
			$colors['alter_bg_color_07'] = '{{ data.alter_bg_color_07 }}';
			$colors['alter_bg_color_04'] = '{{ data.alter_bg_color_04 }}';
			$colors['alter_bg_color_02'] = '{{ data.alter_bg_color_02 }}';
			$colors['alter_bd_color_02'] = '{{ data.alter_bd_color_02 }}';
			$colors['extra_bg_color_07'] = '{{ data.extra_bg_color_07 }}';
			$colors['text_dark_07'] = '{{ data.text_dark_07 }}';
			$colors['text_dark_05'] = '{{ data.text_dark_05 }}';
			$colors['text_link_02'] = '{{ data.text_link_02 }}';
			$colors['text_link_07'] = '{{ data.text_link_07 }}';
			$colors['text_link_blend'] = '{{ data.text_link_blend }}';
			$colors['alter_link_blend'] = '{{ data.alter_link_blend }}';
		}
		return $colors;
	}
}


			
// Additional theme-specific fonts rules
// Attention! Don't forget setup fonts rules also in the theme.customizer.color-scheme.js
if (!function_exists('lighthouseschool_customizer_add_theme_fonts')) {
	function lighthouseschool_customizer_add_theme_fonts($fonts) {
		$rez = array();	
		foreach ($fonts as $tag => $font) {
			if (substr($font['font-family'], 0, 2) != '{{') {
				$rez[$tag.'_font-family'] 		= !empty($font['font-family']) && !lighthouseschool_is_inherit($font['font-family'])
														? 'font-family:' . trim($font['font-family']) . ';' 
														: '';
				$rez[$tag.'_font-size'] 		= !empty($font['font-size']) && !lighthouseschool_is_inherit($font['font-size'])
														? 'font-size:' . lighthouseschool_prepare_css_value($font['font-size']) . ";"
														: '';
				$rez[$tag.'_line-height'] 		= !empty($font['line-height']) && !lighthouseschool_is_inherit($font['line-height'])
														? 'line-height:' . trim($font['line-height']) . ";"
														: '';
				$rez[$tag.'_font-weight'] 		= !empty($font['font-weight']) && !lighthouseschool_is_inherit($font['font-weight'])
														? 'font-weight:' . trim($font['font-weight']) . ";"
														: '';
				$rez[$tag.'_font-style'] 		= !empty($font['font-style']) && !lighthouseschool_is_inherit($font['font-style'])
														? 'font-style:' . trim($font['font-style']) . ";"
														: '';
				$rez[$tag.'_text-decoration'] 	= !empty($font['text-decoration']) && !lighthouseschool_is_inherit($font['text-decoration'])
														? 'text-decoration:' . trim($font['text-decoration']) . ";"
														: '';
				$rez[$tag.'_text-transform'] 	= !empty($font['text-transform']) && !lighthouseschool_is_inherit($font['text-transform'])
														? 'text-transform:' . trim($font['text-transform']) . ";"
														: '';
				$rez[$tag.'_letter-spacing'] 	= !empty($font['letter-spacing']) && !lighthouseschool_is_inherit($font['letter-spacing'])
														? 'letter-spacing:' . trim($font['letter-spacing']) . ";"
														: '';
				$rez[$tag.'_margin-top'] 		= !empty($font['margin-top']) && !lighthouseschool_is_inherit($font['margin-top'])
														? 'margin-top:' . lighthouseschool_prepare_css_value($font['margin-top']) . ";"
														: '';
				$rez[$tag.'_margin-bottom'] 	= !empty($font['margin-bottom']) && !lighthouseschool_is_inherit($font['margin-bottom'])
														? 'margin-bottom:' . lighthouseschool_prepare_css_value($font['margin-bottom']) . ";"
														: '';
			} else {
				$rez[$tag.'_font-family']		= '{{ data["'.$tag.'_font-family"] }}';
				$rez[$tag.'_font-size']			= '{{ data["'.$tag.'_font-size"] }}';
				$rez[$tag.'_line-height']		= '{{ data["'.$tag.'_line-height"] }}';
				$rez[$tag.'_font-weight']		= '{{ data["'.$tag.'_font-weight"] }}';
				$rez[$tag.'_font-style']		= '{{ data["'.$tag.'_font-style"] }}';
				$rez[$tag.'_text-decoration']	= '{{ data["'.$tag.'_text-decoration"] }}';
				$rez[$tag.'_text-transform']	= '{{ data["'.$tag.'_text-transform"] }}';
				$rez[$tag.'_letter-spacing']	= '{{ data["'.$tag.'_letter-spacing"] }}';
				$rez[$tag.'_margin-top']		= '{{ data["'.$tag.'_margin-top"] }}';
				$rez[$tag.'_margin-bottom']		= '{{ data["'.$tag.'_margin-bottom"] }}';
			}
		}
		return $rez;
	}
}




//-------------------------------------------------------
//-- Thumb sizes
//-------------------------------------------------------

if ( !function_exists('lighthouseschool_customizer_theme_setup') ) {
	add_action( 'after_setup_theme', 'lighthouseschool_customizer_theme_setup' );
	function lighthouseschool_customizer_theme_setup() {

		// Enable support for Post Thumbnails
		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size(370, 0, false);
		
		// Add thumb sizes
		// ATTENTION! If you change list below - check filter's names in the 'trx_addons_filter_get_thumb_size' hook
		$thumb_sizes = apply_filters('lighthouseschool_filter_add_thumb_sizes', array(
			'lighthouseschool-thumb-huge'		=> array(1170, 658, true),
			'lighthouseschool-thumb-big' 		=> array( 760, 381, true),
			'lighthouseschool-thumb-med' 		=> array( 370, 233, true),
			'lighthouseschool-thumb-events' 	=> array( 213, 213, true),
			'lighthouseschool-thumb-blogger'	=> array( 770, 370, true),
			'lighthouseschool-thumb-tiny' 		=> array( 158, 158, true),
			'lighthouseschool-thumb-masonry-big'=> array( 760,   0, false),		// Only downscale, not crop
			'lighthouseschool-thumb-masonry'	=> array( 570,   0, false),		// Only downscale, not crop
			)
		);
		$mult = lighthouseschool_get_theme_option('retina_ready', 1);
		if ($mult > 1) $GLOBALS['content_width'] = apply_filters( 'lighthouseschool_filter_content_width', 1170*$mult);
		foreach ($thumb_sizes as $k=>$v) {
			// Add Original dimensions
			add_image_size( $k, $v[0], $v[1], $v[2]);
			// Add Retina dimensions
			if ($mult > 1) add_image_size( $k.'-@retina', $v[0]*$mult, $v[1]*$mult, $v[2]);
		}

	}
}

if ( !function_exists('lighthouseschool_customizer_image_sizes') ) {
	add_filter( 'image_size_names_choose', 'lighthouseschool_customizer_image_sizes' );
	function lighthouseschool_customizer_image_sizes( $sizes ) {
		$thumb_sizes = apply_filters('lighthouseschool_filter_add_thumb_sizes', array(
			'lighthouseschool-thumb-huge'		=> esc_html__( 'Huge image', 'lighthouseschool' ),
			'lighthouseschool-thumb-big'			=> esc_html__( 'Large image', 'lighthouseschool' ),
			'lighthouseschool-thumb-med'			=> esc_html__( 'Medium image', 'lighthouseschool' ),
			'lighthouseschool-thumb-tiny'		=> esc_html__( 'Small square avatar', 'lighthouseschool' ),
			'lighthouseschool-thumb-masonry-big'	=> esc_html__( 'Masonry Large (scaled)', 'lighthouseschool' ),
			'lighthouseschool-thumb-masonry'		=> esc_html__( 'Masonry (scaled)', 'lighthouseschool' ),
			)
		);
		$mult = lighthouseschool_get_theme_option('retina_ready', 1);
		foreach($thumb_sizes as $k=>$v) {
			$sizes[$k] = $v;
			if ($mult > 1) $sizes[$k.'-@retina'] = $v.' '.esc_html__('@2x', 'lighthouseschool' );
		}
		return $sizes;
	}
}

// Remove some thumb-sizes from the ThemeREX Addons list
if ( !function_exists( 'lighthouseschool_customizer_trx_addons_add_thumb_sizes' ) ) {
	add_filter( 'trx_addons_filter_add_thumb_sizes', 'lighthouseschool_customizer_trx_addons_add_thumb_sizes');
	function lighthouseschool_customizer_trx_addons_add_thumb_sizes($list=array()) {
		if (is_array($list)) {
			foreach ($list as $k=>$v) {
				if (in_array($k, array(
								'trx_addons-thumb-huge',
								'trx_addons-thumb-big',
								'trx_addons-thumb-medium',
								'trx_addons-thumb-tiny',
								'trx_addons-thumb-masonry-big',
								'trx_addons-thumb-masonry',
								)
							)
						) unset($list[$k]);
			}
		}
		return $list;
	}
}

// and replace removed styles with theme-specific thumb size
if ( !function_exists( 'lighthouseschool_customizer_trx_addons_get_thumb_size' ) ) {
	add_filter( 'trx_addons_filter_get_thumb_size', 'lighthouseschool_customizer_trx_addons_get_thumb_size');
	function lighthouseschool_customizer_trx_addons_get_thumb_size($thumb_size='') {
		return str_replace(array(
							'trx_addons-thumb-huge',
							'trx_addons-thumb-huge-@retina',
							'trx_addons-thumb-big',
							'trx_addons-thumb-big-@retina',
							'trx_addons-thumb-medium',
							'trx_addons-thumb-medium-@retina',
                            'trx_addons-thumb-events',
							'trx_addons-thumb-events-@retina',
                            'trx_addons-thumb-blogger',
							'trx_addons-thumb-blogger-@retina',
							'trx_addons-thumb-tiny',
							'trx_addons-thumb-tiny-@retina',
							'trx_addons-thumb-masonry-big',
							'trx_addons-thumb-masonry-big-@retina',
							'trx_addons-thumb-masonry',
							'trx_addons-thumb-masonry-@retina',
							),
							array(
							'lighthouseschool-thumb-huge',
							'lighthouseschool-thumb-huge-@retina',
							'lighthouseschool-thumb-big',
							'lighthouseschool-thumb-big-@retina',
							'lighthouseschool-thumb-med',
							'lighthouseschool-thumb-med-@retina',
                            'lighthouseschool-thumb-events',
							'lighthouseschool-thumb-events-@retina',
                            'lighthouseschool-thumb-blogger',
							'lighthouseschool-thumb-blogger-@retina',
							'lighthouseschool-thumb-tiny',
							'lighthouseschool-thumb-tiny-@retina',
							'lighthouseschool-thumb-masonry-big',
							'lighthouseschool-thumb-masonry-big-@retina',
							'lighthouseschool-thumb-masonry',
							'lighthouseschool-thumb-masonry-@retina',
							),
							$thumb_size);
	}
}




//------------------------------------------------------------------------
// One-click import support
//------------------------------------------------------------------------

// Set theme specific importer options
if ( !function_exists( 'lighthouseschool_importer_set_options' ) ) {
	add_filter( 'trx_addons_filter_importer_options', 'lighthouseschool_importer_set_options', 9 );
	function lighthouseschool_importer_set_options($options=array()) {
		if (is_array($options)) {
			// Save or not installer's messages to the log-file
			$options['debug'] = false;
			// Prepare demo data
			$options['demo_url'] = esc_url(lighthouseschool_get_protocol() . '://demofiles.ancorathemes.com/lighthouse/');
			// Required plugins
			$options['required_plugins'] = array_keys(lighthouseschool_storage_get('required_plugins'));
			// Default demo
			$options['files']['default']['title'] = esc_html__('Lighthouse Demo', 'lighthouseschool');
			$options['files']['default']['domain_dev']  = lighthouseschool_add_protocol( '//lighthouse.dv.ancorathemes.com' );                // Developers domain
			$options['files']['default']['domain_demo'] = lighthouseschool_add_protocol( lighthouseschool_storage_get( 'theme_demo_url' ) );   // Demo-site domain
			// If theme need more demo - just copy 'default' and change required parameter
		}
		return $options;
	}
}



// -----------------------------------------------------------------
// -- Theme options for customizer
// -----------------------------------------------------------------
if (!function_exists('lighthouseschool_create_theme_options')) {

	function lighthouseschool_create_theme_options() {

		// Message about options override. 
		// Attention! Not need esc_html() here, because this message put in wp_kses_data() below
		$msg_override = __('<b>Attention!</b> Some of these options can be overridden in the following sections (Blog, Plugins settings, etc.) or in the settings of individual pages', 'lighthouseschool');

		lighthouseschool_storage_set('options', array(
		
			// 'Logo & Site Identity'
			'title_tagline' => array(
				"title" => esc_html__('Logo & Site Identity', 'lighthouseschool'),
				"desc" => '',
				"priority" => 10,
				"type" => "section"
				),
			'logo_info' => array(
				"title" => esc_html__('Logo in the header', 'lighthouseschool'),
				"desc" => '',
				"priority" => 20,
				"type" => "info",
				),
			'logo_text' => array(
				"title" => esc_html__('Use Site Name as Logo', 'lighthouseschool'),
				"desc" => wp_kses_data( __('Use the site title and tagline as a text logo if no image is selected', 'lighthouseschool') ),
				"class" => "lighthouseschool_column-1_2 lighthouseschool_new_row",
				"priority" => 30,
				"std" => 1,
				"type" => LIGHTHOUSESCHOOL_THEME_FREE ? "hidden" : "checkbox"
				),
			'logo_retina_enabled' => array(
				"title" => esc_html__('Allow retina display logo', 'lighthouseschool'),
				"desc" => wp_kses_data( __('Show fields to select logo images for Retina display', 'lighthouseschool') ),
				"class" => "lighthouseschool_column-1_2",
				"refresh" => false,
				"std" => 0,
				"type" => LIGHTHOUSESCHOOL_THEME_FREE ? "hidden" : "checkbox"
				),
			'logo' => array(
				"title" => esc_html__('Logo', 'lighthouseschool'),
				"desc" => wp_kses_data( __('Select or upload site logo', 'lighthouseschool') ),
				"class" => "lighthouseschool_column-1_2 lighthouseschool_new_row",
				"std" => '',
				"type" => "image"
				),
			'logo_retina' => array(
				"title" => esc_html__('Logo for Retina', 'lighthouseschool'),
				"desc" => wp_kses_data( __('Select or upload site logo used on Retina displays (if empty - use default logo from the field above)', 'lighthouseschool') ),
				"class" => "lighthouseschool_column-1_2",
				"dependency" => array(
					'logo_retina_enabled' => array(1)
				),
				"std" => '',
				"type" => LIGHTHOUSESCHOOL_THEME_FREE ? "hidden" : "image"
				),
			'logo_mobile' => array(
				"title" => esc_html__('Logo mobile', 'lighthouseschool'),
				"desc" => wp_kses_data( __('Select or upload site logo to display it in the mobile menu', 'lighthouseschool') ),
				"class" => "lighthouseschool_column-1_2 lighthouseschool_new_row",
				"std" => '',
				"type" => "image"
				),
			'logo_mobile_retina' => array(
				"title" => esc_html__('Logo mobile for Retina', 'lighthouseschool'),
				"desc" => wp_kses_data( __('Select or upload site logo used on Retina displays (if empty - use default logo from the field above)', 'lighthouseschool') ),
				"class" => "lighthouseschool_column-1_2",
				"dependency" => array(
					'logo_retina_enabled' => array(1)
				),
				"std" => '',
				"type" => LIGHTHOUSESCHOOL_THEME_FREE ? "hidden" : "image"
				),
			'logo_side' => array(
				"title" => esc_html__('Logo side', 'lighthouseschool'),
				"desc" => wp_kses_data( __('Select or upload site logo (with vertical orientation) to display it in the side menu', 'lighthouseschool') ),
				"class" => "lighthouseschool_column-1_2 lighthouseschool_new_row",
				"std" => '',
				"type" => "image"
				),
			'logo_side_retina' => array(
				"title" => esc_html__('Logo side for Retina', 'lighthouseschool'),
				"desc" => wp_kses_data( __('Select or upload site logo (with vertical orientation) to display it in the side menu on Retina displays (if empty - use default logo from the field above)', 'lighthouseschool') ),
				"class" => "lighthouseschool_column-1_2",
				"dependency" => array(
					'logo_retina_enabled' => array(1)
				),
				"std" => '',
				"type" => LIGHTHOUSESCHOOL_THEME_FREE ? "hidden" : "image"
				),
			
		
		
			// 'General settings'
			'general' => array(
				"title" => esc_html__('General Settings', 'lighthouseschool'),
				"desc" => wp_kses_data( __('Settings for the entire site', 'lighthouseschool') )
							. '<br>'
							. wp_kses_data( $msg_override ),
				"priority" => 20,
				"type" => "section",
				),

			'general_layout_info' => array(
				"title" => esc_html__('Layout', 'lighthouseschool'),
				"desc" => '',
				"type" => "info",
				),
			'body_style' => array(
				"title" => esc_html__('Body style', 'lighthouseschool'),
				"desc" => wp_kses_data( __('Select width of the body content', 'lighthouseschool') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Content', 'lighthouseschool')
				),
				"refresh" => false,
				"std" => 'boxed',
				"options" => array(
					'boxed'		=> esc_html__('Boxed',		'lighthouseschool'),
					'wide'		=> esc_html__('Wide',		'lighthouseschool'),
					'fullwide'	=> esc_html__('Fullwide',	'lighthouseschool'),
					'fullscreen'=> esc_html__('Fullscreen',	'lighthouseschool')
				),
				"type" => "select"
				),
			'boxed_bg_image' => array(
				"title" => esc_html__('Boxed bg image', 'lighthouseschool'),
				"desc" => wp_kses_data( __('Select or upload image, used as background in the boxed body', 'lighthouseschool') ),
				"dependency" => array(
					'body_style' => array('boxed')
				),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Content', 'lighthouseschool')
				),
				"std" => '',
				"hidden" => true,
				"type" => "image"
				),
			'remove_margins' => array(
				"title" => esc_html__('Remove margins', 'lighthouseschool'),
				"desc" => wp_kses_data( __('Remove margins above and below the content area', 'lighthouseschool') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Content', 'lighthouseschool')
				),
				"refresh" => false,
				"std" => 0,
				"type" => "checkbox"
				),

			'general_sidebar_info' => array(
				"title" => esc_html__('Sidebar', 'lighthouseschool'),
				"desc" => '',
				"type" => "info",
				),
			'sidebar_position' => array(
				"title" => esc_html__('Sidebar position', 'lighthouseschool'),
				"desc" => wp_kses_data( __('Select position to show sidebar', 'lighthouseschool') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Widgets', 'lighthouseschool')
				),
				"std" => 'right',
				"options" => array(),
				"type" => "switch"
				),
			'sidebar_widgets' => array(
				"title" => esc_html__('Sidebar widgets', 'lighthouseschool'),
				"desc" => wp_kses_data( __('Select default widgets to show in the sidebar', 'lighthouseschool') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Widgets', 'lighthouseschool')
				),
				"dependency" => array(
					'sidebar_position' => array('left', 'right')
				),
				"std" => 'sidebar_widgets',
				"options" => array(),
				"type" => "select"
				),
			'expand_content' => array(
				"title" => esc_html__('Expand content', 'lighthouseschool'),
				"desc" => wp_kses_data( __('Expand the content width if the sidebar is hidden', 'lighthouseschool') ),
				"refresh" => false,
				"std" => 1,
				"type" => "checkbox"
				),


			'general_widgets_info' => array(
				"title" => esc_html__('Additional widgets', 'lighthouseschool'),
				"desc" => '',
				"type" => LIGHTHOUSESCHOOL_THEME_FREE ? "hidden" : "info",
				),
			'widgets_above_page' => array(
				"title" => esc_html__('Widgets at the top of the page', 'lighthouseschool'),
				"desc" => wp_kses_data( __('Select widgets to show at the top of the page (above content and sidebar)', 'lighthouseschool') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Widgets', 'lighthouseschool')
				),
				"std" => 'hide',
				"options" => array(),
				"type" => LIGHTHOUSESCHOOL_THEME_FREE ? "hidden" : "select"
				),
			'widgets_above_content' => array(
				"title" => esc_html__('Widgets above the content', 'lighthouseschool'),
				"desc" => wp_kses_data( __('Select widgets to show at the beginning of the content area', 'lighthouseschool') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Widgets', 'lighthouseschool')
				),
				"std" => 'hide',
				"options" => array(),
				"type" => LIGHTHOUSESCHOOL_THEME_FREE ? "hidden" : "select"
				),
			'widgets_below_content' => array(
				"title" => esc_html__('Widgets below the content', 'lighthouseschool'),
				"desc" => wp_kses_data( __('Select widgets to show at the ending of the content area', 'lighthouseschool') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Widgets', 'lighthouseschool')
				),
				"std" => 'hide',
				"options" => array(),
				"type" => LIGHTHOUSESCHOOL_THEME_FREE ? "hidden" : "select"
				),
			'widgets_below_page' => array(
				"title" => esc_html__('Widgets at the bottom of the page', 'lighthouseschool'),
				"desc" => wp_kses_data( __('Select widgets to show at the bottom of the page (below content and sidebar)', 'lighthouseschool') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Widgets', 'lighthouseschool')
				),
				"std" => 'hide',
				"options" => array(),
				"type" => LIGHTHOUSESCHOOL_THEME_FREE ? "hidden" : "select"
				),

			'general_effects_info' => array(
				"title" => esc_html__('Design & Effects', 'lighthouseschool'),
				"desc" => '',
				"type" => "info",
				),
			'border_radius' => array(
				"title" => esc_html__('Border radius', 'lighthouseschool'),
				"desc" => wp_kses_data( __('Specify the border radius of the form fields and buttons in pixels or other valid CSS units', 'lighthouseschool') ),
				"std" => '2em',
				"type" => "text"
				),

			'general_misc_info' => array(
				"title" => esc_html__('Miscellaneous', 'lighthouseschool'),
				"desc" => '',
				"type" => LIGHTHOUSESCHOOL_THEME_FREE ? "hidden" : "info",
				),
			'seo_snippets' => array(
				"title" => esc_html__('SEO snippets', 'lighthouseschool'),
				"desc" => wp_kses_data( __('Add structured data markup to the single posts and pages', 'lighthouseschool') ),
				"std" => 0,
				"type" => LIGHTHOUSESCHOOL_THEME_FREE ? "hidden" : "checkbox"
				),
			'privacy_text' => array(
				"title" => esc_html__("Text with Privacy Policy link", 'lighthouseschool'),
				"desc"  => wp_kses_data( __("Specify text with Privacy Policy link for the checkbox 'I agree ...'", 'lighthouseschool') ),
				"std"   => wp_kses( __( 'I agree that my submitted data is being collected and stored.', 'lighthouseschool'), 'lighthouseschool_kses_content' ),
				"type"  => "text"
			),
		
		
			// 'Header'
			'header' => array(
				"title" => esc_html__('Header', 'lighthouseschool'),
				"desc" => wp_kses_data( $msg_override ),
				"priority" => 30,
				"type" => "section"
				),

			'header_style_info' => array(
				"title" => esc_html__('Header style', 'lighthouseschool'),
				"desc" => '',
				"type" => "info"
				),
			'header_style' => array(
				"title" => esc_html__('Header style', 'lighthouseschool'),
				"desc" => wp_kses_data( __('Select style to display the site header', 'lighthouseschool') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Header', 'lighthouseschool')
				),
				"std" => LIGHTHOUSESCHOOL_THEME_FREE ? 'header-default' : 'header-default',
				"options" => array(),
				"type" => "select"
				),
			'header_position' => array(
				"title" => esc_html__('Header position', 'lighthouseschool'),
				"desc" => wp_kses_data( __('Select position to display the site header', 'lighthouseschool') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Header', 'lighthouseschool')
				),
				"std" => 'default',
				"options" => array(),
				"type" => LIGHTHOUSESCHOOL_THEME_FREE ? "hidden" : "switch"
				),
			'header_fullheight' => array(
				"title" => esc_html__('Header fullheight', 'lighthouseschool'),
				"desc" => wp_kses_data( __("Enlarge header area to fill whole screen. Used only if header have a background image", 'lighthouseschool') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Header', 'lighthouseschool')
				),
				"std" => 0,
				"type" => LIGHTHOUSESCHOOL_THEME_FREE ? "hidden" : "checkbox"
				),
			'header_wide' => array(
				"title" => esc_html__('Header fullwide', 'lighthouseschool'),
				"desc" => wp_kses_data( __('Do you want to stretch the header widgets area to the entire window width?', 'lighthouseschool') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Header', 'lighthouseschool')
				),
				"dependency" => array(
					'header_style' => array('header-default')
				),
				"std" => 1,
				"type" => LIGHTHOUSESCHOOL_THEME_FREE ? "hidden" : "checkbox"
				),

			'header_widgets_info' => array(
				"title" => esc_html__('Header widgets', 'lighthouseschool'),
				"desc" => wp_kses_data( __('Here you can place a widget slider, advertising banners, etc.', 'lighthouseschool') ),
				"type" => "info"
				),
			'header_widgets' => array(
				"title" => esc_html__('Header widgets', 'lighthouseschool'),
				"desc" => wp_kses_data( __('Select set of widgets to show in the header on each page', 'lighthouseschool') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Header', 'lighthouseschool'),
					"desc" => wp_kses_data( __('Select set of widgets to show in the header on this page', 'lighthouseschool') ),
				),
				"std" => 'hide',
				"options" => array(),
				"type" => "select"
				),
			'header_columns' => array(
				"title" => esc_html__('Header columns', 'lighthouseschool'),
				"desc" => wp_kses_data( __('Select number columns to show widgets in the Header. If 0 - autodetect by the widgets count', 'lighthouseschool') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Header', 'lighthouseschool')
				),
				"dependency" => array(
					'header_style' => array('header-default'),
					'header_widgets' => array('^hide')
				),
				"std" => 0,
				"options" => lighthouseschool_get_list_range(0,6),
				"type" => "select"
				),

			'menu_info' => array(
				"title" => esc_html__('Main menu', 'lighthouseschool'),
				"desc" => wp_kses_data( __('Select main menu style, position, color scheme and other parameters', 'lighthouseschool') ),
				"type" => LIGHTHOUSESCHOOL_THEME_FREE ? "hidden" : "info"
				),
			'menu_style' => array(
				"title" => esc_html__('Menu position', 'lighthouseschool'),
				"desc" => wp_kses_data( __('Select position of the main menu', 'lighthouseschool') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Header', 'lighthouseschool')
				),
				"std" => 'top',
				"options" => array(
					'top'	=> esc_html__('Top',	'lighthouseschool'),
					'left'	=> esc_html__('Left',	'lighthouseschool'),
					'right'	=> esc_html__('Right',	'lighthouseschool')
				),
				"type" => LIGHTHOUSESCHOOL_THEME_FREE ? "hidden" : "switch"
				),
			'menu_side_stretch' => array(
				"title" => esc_html__('Stretch sidemenu', 'lighthouseschool'),
				"desc" => wp_kses_data( __('Stretch sidemenu to window height (if menu items number >= 5)', 'lighthouseschool') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Header', 'lighthouseschool')
				),
				"dependency" => array(
					'menu_style' => array('left', 'right')
				),
				"std" => 0,
				"type" => LIGHTHOUSESCHOOL_THEME_FREE ? "hidden" : "checkbox"
				),
			'menu_side_icons' => array(
				"title" => esc_html__('Iconed sidemenu', 'lighthouseschool'),
				"desc" => wp_kses_data( __('Get icons from anchors and display it in the sidemenu or mark sidemenu items with simple dots', 'lighthouseschool') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Header', 'lighthouseschool')
				),
				"dependency" => array(
					'menu_style' => array('left', 'right')
				),
				"std" => 1,
				"type" => LIGHTHOUSESCHOOL_THEME_FREE ? "hidden" : "checkbox"
				),
			'menu_mobile_fullscreen' => array(
				"title" => esc_html__('Mobile menu fullscreen', 'lighthouseschool'),
				"desc" => wp_kses_data( __('Display mobile and side menus on full screen (if checked) or slide narrow menu from the left or from the right side (if not checked)', 'lighthouseschool') ),
				"std" => 1,
				"type" => LIGHTHOUSESCHOOL_THEME_FREE ? "hidden" : "checkbox"
				),

			'header_image_info' => array(
				"title" => esc_html__('Header image', 'lighthouseschool'),
				"desc" => '',
				"type" => LIGHTHOUSESCHOOL_THEME_FREE ? "hidden" : "info"
				),
			'header_image_override' => array(
				"title" => esc_html__('Header image override', 'lighthouseschool'),
				"desc" => wp_kses_data( __("Allow override the header image with the page's/post's/product's/etc. featured image", 'lighthouseschool') ),
				"override" => array(
					'mode' => 'page',
					'section' => esc_html__('Header', 'lighthouseschool')
				),
				"std" => 0,
				"type" => LIGHTHOUSESCHOOL_THEME_FREE ? "hidden" : "checkbox"
				),


		
			// 'Footer'
			'footer' => array(
				"title" => esc_html__('Footer', 'lighthouseschool'),
				"desc" => wp_kses_data( __('Select set of widgets and columns number in the site footer', 'lighthouseschool') )
							. '<br>'
							. wp_kses_data( $msg_override ),
				"priority" => 50,
				"type" => "section"
				),
			'footer_style' => array(
				"title" => esc_html__('Footer style', 'lighthouseschool'),
				"desc" => wp_kses_data( __('Select style to display the site footer', 'lighthouseschool') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Footer', 'lighthouseschool')
				),
				"std" => LIGHTHOUSESCHOOL_THEME_FREE ? 'footer-default' : 'footer-default',
				"options" => array(),
				"type" => "select"
				),
			'footer_widgets' => array(
				"title" => esc_html__('Footer widgets', 'lighthouseschool'),
				"desc" => wp_kses_data( __('Select set of widgets to show in the footer', 'lighthouseschool') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Footer', 'lighthouseschool')
				),
				"dependency" => array(
					'footer_style' => array('footer-default')
				),
				"std" => 'footer_widgets',
				"options" => array(),
				"type" => "select"
				),
			'footer_columns' => array(
				"title" => esc_html__('Footer columns', 'lighthouseschool'),
				"desc" => wp_kses_data( __('Select number columns to show widgets in the footer. If 0 - autodetect by the widgets count', 'lighthouseschool') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Footer', 'lighthouseschool')
				),
				"dependency" => array(
					'footer_style' => array('footer-default'),
					'footer_widgets' => array('^hide')
				),
				"std" => 0,
				"options" => lighthouseschool_get_list_range(0,6),
				"type" => "select"
				),
			'footer_wide' => array(
				"title" => esc_html__('Footer fullwide', 'lighthouseschool'),
				"desc" => wp_kses_data( __('Do you want to stretch the footer to the entire window width?', 'lighthouseschool') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Footer', 'lighthouseschool')
				),
				"dependency" => array(
					'footer_style' => array('footer-default')
				),
				"std" => 0,
				"type" => "checkbox"
				),
			'logo_in_footer' => array(
				"title" => esc_html__('Show logo', 'lighthouseschool'),
				"desc" => wp_kses_data( __('Show logo in the footer', 'lighthouseschool') ),
				'refresh' => false,
				"dependency" => array(
					'footer_style' => array('footer-default')
				),
				"std" => 0,
				"type" => "checkbox"
				),
			'logo_footer' => array(
				"title" => esc_html__('Logo for footer', 'lighthouseschool'),
				"desc" => wp_kses_data( __('Select or upload site logo to display it in the footer', 'lighthouseschool') ),
				"dependency" => array(
					'footer_style' => array('footer-default'),
					'logo_in_footer' => array(1)
				),
				"std" => '',
				"type" => "image"
				),
			'logo_footer_retina' => array(
				"title" => esc_html__('Logo for footer (Retina)', 'lighthouseschool'),
				"desc" => wp_kses_data( __('Select or upload logo for the footer area used on Retina displays (if empty - use default logo from the field above)', 'lighthouseschool') ),
				"dependency" => array(
					'footer_style' => array('footer-default'),
					'logo_in_footer' => array(1),
					'logo_retina_enabled' => array(1)
				),
				"std" => '',
				"type" => LIGHTHOUSESCHOOL_THEME_FREE ? "hidden" : "image"
				),
			'socials_in_footer' => array(
				"title" => esc_html__('Show social icons', 'lighthouseschool'),
				"desc" => wp_kses_data( __('Show social icons in the footer (under logo or footer widgets)', 'lighthouseschool') ),
				"dependency" => array(
					'footer_style' => array('footer-default')
				),
				"std" => 0,
				"type" => "checkbox"
				),
			'copyright' => array(
				"title" => esc_html__('Copyright', 'lighthouseschool'),
				"desc" => wp_kses_data( __('Copyright text in the footer. Use {Y} to insert current year and press "Enter" to create a new line', 'lighthouseschool') ),
				"std" => esc_html__('AncoraThemes &copy; {Y}. All rights reserved. Terms of use and Privacy Policy', 'lighthouseschool'),
				"dependency" => array(
					'footer_style' => array('footer-default')
				),
				"refresh" => false,
				"type" => "textarea"
				),
			
		
		
			// 'Blog'
			'blog' => array(
				"title" => esc_html__('Blog', 'lighthouseschool'),
				"desc" => wp_kses_data( __('Options of the the blog archive', 'lighthouseschool') ),
				"priority" => 70,
				"type" => "panel",
				),
		
				// Blog - Posts page
				'blog_general' => array(
					"title" => esc_html__('Posts page', 'lighthouseschool'),
					"desc" => wp_kses_data( __('Style and components of the blog archive', 'lighthouseschool') ),
					"type" => "section",
					),
				'blog_general_info' => array(
					"title" => esc_html__('General settings', 'lighthouseschool'),
					"desc" => '',
					"type" => "info",
					),
				'blog_style' => array(
					"title" => esc_html__('Blog style', 'lighthouseschool'),
					"desc" => '',
					"override" => array(
						'mode' => 'page',
						'section' => esc_html__('Content', 'lighthouseschool')
					),
					"dependency" => array(
						'#page_template' => array( 'blog.php' ),
						'.components-select-control:not(.post-author-selector) select' => array( 'blog.php' ),
					),
					"std" => 'excerpt',
					"options" => array(),
					"type" => "select"
					),
				'first_post_large' => array(
					"title" => esc_html__('First post large', 'lighthouseschool'),
					"desc" => wp_kses_data( __('Make your first post stand out by making it bigger', 'lighthouseschool') ),
					"override" => array(
						'mode' => 'page',
						'section' => esc_html__('Content', 'lighthouseschool')
					),
					"dependency" => array(
						'#page_template' => array( 'blog.php' ),
						'.components-select-control:not(.post-author-selector) select' => array( 'blog.php' ),
						'blog_style' => array('classic', 'masonry')
					),
					"std" => 0,
					"type" => "checkbox"
					),
				"blog_content" => array( 
					"title" => esc_html__('Posts content', 'lighthouseschool'),
					"desc" => wp_kses_data( __("Display either post excerpts or the full post content", 'lighthouseschool') ),
					"std" => "excerpt",
					"dependency" => array(
						'blog_style' => array('excerpt')
					),
					"options" => array(
						'excerpt'	=> esc_html__('Excerpt',	'lighthouseschool'),
						'fullpost'	=> esc_html__('Full post',	'lighthouseschool')
					),
					"type" => "switch"
					),
				'excerpt_length' => array(
					"title" => esc_html__('Excerpt length', 'lighthouseschool'),
					"desc" => wp_kses_data( __("Length (in words) to generate excerpt from the post content. Attention! If the post excerpt is explicitly specified - it appears unchanged", 'lighthouseschool') ),
					"dependency" => array(
						'blog_style' => array('excerpt'),
						'blog_content' => array('excerpt')
					),
					"std" => 60,
					"type" => "text"
					),
				'blog_columns' => array(
					"title" => esc_html__('Blog columns', 'lighthouseschool'),
					"desc" => wp_kses_data( __('How many columns should be used in the blog archive (from 2 to 4)?', 'lighthouseschool') ),
					"std" => 2,
					"options" => lighthouseschool_get_list_range(2,4),
					"type" => "hidden"
					),
				'post_type' => array(
					"title" => esc_html__('Post type', 'lighthouseschool'),
					"desc" => wp_kses_data( __('Select post type to show in the blog archive', 'lighthouseschool') ),
					"override" => array(
						'mode' => 'page',
						'section' => esc_html__('Content', 'lighthouseschool')
					),
					"dependency" => array(
						'#page_template' => array( 'blog.php' ),
						'.components-select-control:not(.post-author-selector) select' => array( 'blog.php' ),
					),
					"linked" => 'parent_cat',
					"refresh" => false,
					"hidden" => true,
					"std" => 'post',
					"options" => array(),
					"type" => "select"
					),
				'parent_cat' => array(
					"title" => esc_html__('Category to show', 'lighthouseschool'),
					"desc" => wp_kses_data( __('Select category to show in the blog archive', 'lighthouseschool') ),
					"override" => array(
						'mode' => 'page',
						'section' => esc_html__('Content', 'lighthouseschool')
					),
					"dependency" => array(
						'#page_template' => array( 'blog.php' ),
						'.components-select-control:not(.post-author-selector) select' => array( 'blog.php' ),
					),
					"refresh" => false,
					"hidden" => true,
					"std" => '0',
					"options" => array(),
					"type" => "select"
					),
				'posts_per_page' => array(
					"title" => esc_html__('Posts per page', 'lighthouseschool'),
					"desc" => wp_kses_data( __('How many posts will be displayed on this page', 'lighthouseschool') ),
					"override" => array(
						'mode' => 'page',
						'section' => esc_html__('Content', 'lighthouseschool')
					),
					"dependency" => array(
						'#page_template' => array( 'blog.php' ),
						'.components-select-control:not(.post-author-selector) select' => array( 'blog.php' ),
					),
					"hidden" => true,
					"std" => '',
					"type" => "text"
					),
				"blog_pagination" => array( 
					"title" => esc_html__('Pagination style', 'lighthouseschool'),
					"desc" => wp_kses_data( __('Show Older/Newest posts or Page numbers below the posts list', 'lighthouseschool') ),
					"override" => array(
						'mode' => 'page',
						'section' => esc_html__('Content', 'lighthouseschool')
					),
					"std" => "pages",
					"options" => array(
						'pages'	=> esc_html__("Page numbers", 'lighthouseschool'),
						'links'	=> esc_html__("Older/Newest", 'lighthouseschool'),
						'more'	=> esc_html__("Load more", 'lighthouseschool'),
						'infinite' => esc_html__("Infinite scroll", 'lighthouseschool')
					),
					"type" => "select"
					),
				'show_filters' => array(
					"title" => esc_html__('Show filters', 'lighthouseschool'),
					"desc" => wp_kses_data( __('Show categories as tabs to filter posts', 'lighthouseschool') ),
					"override" => array(
						'mode' => 'page',
						'section' => esc_html__('Content', 'lighthouseschool')
					),
					"dependency" => array(
						'#page_template' => array( 'blog.php' ),
						'.components-select-control:not(.post-author-selector) select' => array( 'blog.php' ),
						'blog_style' => array('portfolio', 'gallery')
					),
					"hidden" => true,
					"std" => 0,
					"type" => LIGHTHOUSESCHOOL_THEME_FREE ? "hidden" : "checkbox"
					),
	
				'blog_sidebar_info' => array(
					"title" => esc_html__('Sidebar', 'lighthouseschool'),
					"desc" => '',
					"type" => "info",
					),
				'sidebar_position_blog' => array(
					"title" => esc_html__('Sidebar position', 'lighthouseschool'),
					"desc" => wp_kses_data( __('Select position to show sidebar', 'lighthouseschool') ),
					"std" => 'right',
					"options" => array(),
					"type" => "switch"
					),
				'sidebar_widgets_blog' => array(
					"title" => esc_html__('Sidebar widgets', 'lighthouseschool'),
					"desc" => wp_kses_data( __('Select default widgets to show in the sidebar', 'lighthouseschool') ),
					"dependency" => array(
						'sidebar_position_blog' => array('left', 'right')
					),
					"std" => 'sidebar_widgets',
					"options" => array(),
					"type" => "select"
					),
				'expand_content_blog' => array(
					"title" => esc_html__('Expand content', 'lighthouseschool'),
					"desc" => wp_kses_data( __('Expand the content width if the sidebar is hidden', 'lighthouseschool') ),
					"refresh" => false,
					"std" => 1,
					"type" => "checkbox"
					),
	
	
				'blog_widgets_info' => array(
					"title" => esc_html__('Additional widgets', 'lighthouseschool'),
					"desc" => '',
					"type" => LIGHTHOUSESCHOOL_THEME_FREE ? "hidden" : "info",
					),
				'widgets_above_page_blog' => array(
					"title" => esc_html__('Widgets at the top of the page', 'lighthouseschool'),
					"desc" => wp_kses_data( __('Select widgets to show at the top of the page (above content and sidebar)', 'lighthouseschool') ),
					"std" => 'hide',
					"options" => array(),
					"type" => LIGHTHOUSESCHOOL_THEME_FREE ? "hidden" : "select"
					),
				'widgets_above_content_blog' => array(
					"title" => esc_html__('Widgets above the content', 'lighthouseschool'),
					"desc" => wp_kses_data( __('Select widgets to show at the beginning of the content area', 'lighthouseschool') ),
					"std" => 'hide',
					"options" => array(),
					"type" => LIGHTHOUSESCHOOL_THEME_FREE ? "hidden" : "select"
					),
				'widgets_below_content_blog' => array(
					"title" => esc_html__('Widgets below the content', 'lighthouseschool'),
					"desc" => wp_kses_data( __('Select widgets to show at the ending of the content area', 'lighthouseschool') ),
					"std" => 'hide',
					"options" => array(),
					"type" => LIGHTHOUSESCHOOL_THEME_FREE ? "hidden" : "select"
					),
				'widgets_below_page_blog' => array(
					"title" => esc_html__('Widgets at the bottom of the page', 'lighthouseschool'),
					"desc" => wp_kses_data( __('Select widgets to show at the bottom of the page (below content and sidebar)', 'lighthouseschool') ),
					"std" => 'hide',
					"options" => array(),
					"type" => LIGHTHOUSESCHOOL_THEME_FREE ? "hidden" : "select"
					),

				'blog_advanced_info' => array(
					"title" => esc_html__('Advanced settings', 'lighthouseschool'),
					"desc" => '',
					"type" => "info",
					),
				'no_image' => array(
					"title" => esc_html__('Image placeholder', 'lighthouseschool'),
					"desc" => wp_kses_data( __('Select or upload an image used as placeholder for posts without a featured image', 'lighthouseschool') ),
					"std" => '',
					"type" => "image"
					),
				'time_diff_before' => array(
					"title" => esc_html__('Easy Readable Date Format', 'lighthouseschool'),
					"desc" => wp_kses_data( __("For how many days to show the easy-readable date format (e.g. '3 days ago') instead of the standard publication date", 'lighthouseschool') ),
					"std" => 5,
					"type" => "text"
					),
				'sticky_style' => array(
					"title" => esc_html__('Sticky posts style', 'lighthouseschool'),
					"desc" => wp_kses_data( __('Select style of the sticky posts output', 'lighthouseschool') ),
					"std" => 'inherit',
					"options" => array(
						'inherit' => esc_html__('Decorated posts', 'lighthouseschool'),
						'columns' => esc_html__('Mini-cards',	'lighthouseschool')
					),
					"type" => LIGHTHOUSESCHOOL_THEME_FREE ? "hidden" : "select"
					),
				"blog_animation" => array( 
					"title" => esc_html__('Animation for the posts', 'lighthouseschool'),
					"desc" => wp_kses_data( __('Select animation to show posts in the blog. Attention! Do not use any animation on pages with the "wheel to the anchor" behaviour (like a "Chess 2 columns")!', 'lighthouseschool') ),
					"override" => array(
						'mode' => 'page',
						'section' => esc_html__('Content', 'lighthouseschool')
					),
					"dependency" => array(
						'#page_template' => array( 'blog.php' ),
						'.components-select-control:not(.post-author-selector) select' => array( 'blog.php' ),
					),
					"std" => "none",
					"options" => array(),
					"type" => LIGHTHOUSESCHOOL_THEME_FREE ? "hidden" : "select"
					),
				'meta_parts' => array(
					"title" => esc_html__('Post meta', 'lighthouseschool'),
					"desc" => wp_kses_data( __("If your blog page is created using the 'Blog archive' page template, set up the 'Post Meta' settings in the 'Theme Options' section of that page.", 'lighthouseschool') )
								. '<br>'
								. wp_kses_data( __("<b>Tip:</b> Drag items to change their order.", 'lighthouseschool') ),
					"override" => array(
						'mode' => 'page',
						'section' => esc_html__('Content', 'lighthouseschool')
					),
					"dependency" => array(
						'#page_template' => array( 'blog.php' ),
						'.components-select-control:not(.post-author-selector) select' => array( 'blog.php' ),
					),
					"dir" => 'vertical',
					"sortable" => true,
					"std" => 'categories=1|date=0|counters=1|author=1|share=0|edit=0',
					"options" => array(
						'categories' => esc_html__('Categories', 'lighthouseschool'),
						'date'		 => esc_html__('Post date', 'lighthouseschool'),
						'author'	 => esc_html__('Post author', 'lighthouseschool'),
						'counters'	 => esc_html__('Views, Likes and Comments', 'lighthouseschool'),
						'share'		 => esc_html__('Share links', 'lighthouseschool'),
						'edit'		 => esc_html__('Edit link', 'lighthouseschool')
					),
					"type" => LIGHTHOUSESCHOOL_THEME_FREE ? "hidden" : "checklist"
				),
				'counters' => array(
					"title" => esc_html__('Views, Likes and Comments', 'lighthouseschool'),
					"desc" => wp_kses_data( __("Likes and Views are available only if ThemeREX Addons is active", 'lighthouseschool') ),
					"override" => array(
						'mode' => 'page',
						'section' => esc_html__('Content', 'lighthouseschool')
					),
					"dependency" => array(
						'#page_template' => array( 'blog.php' ),
						'.components-select-control:not(.post-author-selector) select' => array( 'blog.php' ),
					),
					"dir" => 'vertical',
					"sortable" => true,
					"std" => 'views=0|likes=0|comments=1',
					"options" => array(
						'views' => esc_html__('Views', 'lighthouseschool'),
						'likes' => esc_html__('Likes', 'lighthouseschool'),
						'comments' => esc_html__('Comments', 'lighthouseschool')
					),
					"type" => LIGHTHOUSESCHOOL_THEME_FREE ? "hidden" : "checklist"
				),

				
				// Blog - Single posts
				'blog_single' => array(
					"title" => esc_html__('Single posts', 'lighthouseschool'),
					"desc" => wp_kses_data( __('Settings of the single post', 'lighthouseschool') ),
					"type" => "section",
					),
				'hide_featured_on_single' => array(
					"title" => esc_html__('Hide featured image on the single post', 'lighthouseschool'),
					"desc" => wp_kses_data( __("Hide featured image on the single post's pages", 'lighthouseschool') ),
					"override" => array(
						'mode' => 'page,post',
						'section' => esc_html__('Content', 'lighthouseschool')
					),
					"std" => 0,
					"type" => "checkbox"
					),
				'hide_sidebar_on_single' => array(
					"title" => esc_html__('Hide sidebar on the single post', 'lighthouseschool'),
					"desc" => wp_kses_data( __("Hide sidebar on the single post's pages", 'lighthouseschool') ),
					"std" => 0,
					"type" => "checkbox"
					),
				'show_post_meta' => array(
					"title" => esc_html__('Show post meta', 'lighthouseschool'),
					"desc" => wp_kses_data( __("Display block with post's meta: date, categories, counters, etc.", 'lighthouseschool') ),
					"std" => 1,
					"type" => "checkbox"
					),
				'meta_parts_post' => array(
					"title" => esc_html__('Post meta', 'lighthouseschool'),
					"desc" => wp_kses_data( __("Meta parts for single posts.", 'lighthouseschool') ),
					"dependency" => array(
						'show_post_meta' => array(1)
					),
					"dir" => 'vertical',
					"sortable" => true,
					"std" => 'categories=1|date=0|counters=1|author=1|share=0|edit=0',
					"options" => array(
						'categories' => esc_html__('Categories', 'lighthouseschool'),
						'date'		 => esc_html__('Post date', 'lighthouseschool'),
						'author'	 => esc_html__('Post author', 'lighthouseschool'),
						'counters'	 => esc_html__('Views, Likes and Comments', 'lighthouseschool'),
						'share'		 => esc_html__('Share links', 'lighthouseschool'),
						'edit'		 => esc_html__('Edit link', 'lighthouseschool')
					),
					"type" => LIGHTHOUSESCHOOL_THEME_FREE ? "hidden" : "checklist"
				),
				'counters_post' => array(
					"title" => esc_html__('Views, Likes and Comments', 'lighthouseschool'),
					"desc" => wp_kses_data( __("Likes and Views are available only if ThemeREX Addons is active", 'lighthouseschool') ),
					"dependency" => array(
						'show_post_meta' => array(1)
					),
					"dir" => 'vertical',
					"sortable" => true,
					"std" => 'views=0|likes=0|comments=1',
					"options" => array(
						'views' => esc_html__('Views', 'lighthouseschool'),
						'likes' => esc_html__('Likes', 'lighthouseschool'),
						'comments' => esc_html__('Comments', 'lighthouseschool')
					),
					"type" => LIGHTHOUSESCHOOL_THEME_FREE ? "hidden" : "checklist"
				),
				'show_share_links' => array(
					"title" => esc_html__('Show share links', 'lighthouseschool'),
					"desc" => wp_kses_data( __("Display share links on the single post", 'lighthouseschool') ),
					"std" => 1,
					"type" => "checkbox"
					),
				'show_author_info' => array(
					"title" => esc_html__('Show author info', 'lighthouseschool'),
					"desc" => wp_kses_data( __("Display block with information about post's author", 'lighthouseschool') ),
					"std" => 1,
					"type" => "checkbox"
					),
				'blog_single_related_info' => array(
					"title" => esc_html__('Related posts', 'lighthouseschool'),
					"desc" => '',
					"type" => "info",
					),
				'show_related_posts' => array(
					"title" => esc_html__('Show related posts', 'lighthouseschool'),
					"desc" => wp_kses_data( __("Show section 'Related posts' on the single post's pages", 'lighthouseschool') ),
					"override" => array(
						'mode' => 'page,post',
						'section' => esc_html__('Content', 'lighthouseschool')
					),
					"std" => 0,
					"type" => "checkbox"
					),
				'related_posts' => array(
					"title" => esc_html__('Related posts', 'lighthouseschool'),
					"desc" => wp_kses_data( __('How many related posts should be displayed in the single post? If 0 - no related posts showed.', 'lighthouseschool') ),
					"dependency" => array(
						'show_related_posts' => array(1)
					),
					"std" => 2,
					"options" => lighthouseschool_get_list_range(1,9),
					"type" => LIGHTHOUSESCHOOL_THEME_FREE ? "hidden" : "select"
					),
				'related_columns' => array(
					"title" => esc_html__('Related columns', 'lighthouseschool'),
					"desc" => wp_kses_data( __('How many columns should be used to output related posts in the single page (from 2 to 4)?', 'lighthouseschool') ),
					"dependency" => array(
						'show_related_posts' => array(1)
					),
					"std" => 2,
					"options" => lighthouseschool_get_list_range(1,4),
					"type" => LIGHTHOUSESCHOOL_THEME_FREE ? "hidden" : "switch"
					),
				'related_style' => array(
					"title" => esc_html__('Related posts style', 'lighthouseschool'),
					"desc" => wp_kses_data( __('Select style of the related posts output', 'lighthouseschool') ),
					"dependency" => array(
						'show_related_posts' => array(1)
					),
					"std" => 2,
					"options" => lighthouseschool_get_list_styles(1,2),
					"type" => LIGHTHOUSESCHOOL_THEME_FREE ? "hidden" : "switch"
					),
			'blog_end' => array(
				"type" => "panel_end",
				),
			
		
		
			// 'Colors'
			'panel_colors' => array(
				"title" => esc_html__('Colors', 'lighthouseschool'),
				"desc" => '',
				"priority" => 300,
				"type" => "section"
				),

			'color_schemes_info' => array(
				"title" => esc_html__('Color schemes', 'lighthouseschool'),
				"desc" => wp_kses_data( __('Color schemes for various parts of the site. "Inherit" means that this block is used the Site color scheme (the first parameter)', 'lighthouseschool') ),
				"type" => "info",
				),
			'color_scheme' => array(
				"title" => esc_html__('Site Color Scheme', 'lighthouseschool'),
				"desc" => '',
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Colors', 'lighthouseschool')
				),
				"std" => 'default',
				"options" => array(),
				"refresh" => false,
				"type" => "switch"
				),
			'sidebar_scheme' => array(
				"title" => esc_html__('Sidebar Color Scheme', 'lighthouseschool'),
				"desc" => '',
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Colors', 'lighthouseschool')
				),
				"std" => 'default',
				"options" => array(),
				"refresh" => false,
				"type" => "switch"
				),
			'header_scheme' => array(
				"title" => esc_html__('Header Color Scheme', 'lighthouseschool'),
				"desc" => '',
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Colors', 'lighthouseschool')
				),
				"std" => 'inherit',
				"options" => array(),
				"refresh" => false,
				"type" => "switch"
				),
			'menu_scheme' => array(
				"title" => esc_html__('Menu Color Scheme', 'lighthouseschool'),
				"desc" => '',
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Colors', 'lighthouseschool')
				),
				"std" => 'inherit',
				"options" => array(),
				"refresh" => false,
				"type" => LIGHTHOUSESCHOOL_THEME_FREE ? "hidden" : "switch"
				),
			'footer_scheme' => array(
				"title" => esc_html__('Footer Color Scheme', 'lighthouseschool'),
				"desc" => '',
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Colors', 'lighthouseschool')
				),
				"std" => 'default',
				"options" => array(),
				"refresh" => false,
				"type" => "switch"
				),

			'color_scheme_editor_info' => array(
				"title" => esc_html__('Color scheme editor', 'lighthouseschool'),
				"desc" => wp_kses_data(__('Select color scheme to modify. Attention! Only those sections in the site will be changed which this scheme was assigned to', 'lighthouseschool') ),
				"type" => "info",
				),
			'scheme_storage' => array(
				"title" => esc_html__('Color scheme editor', 'lighthouseschool'),
				"desc" => '',
				"std" => '$lighthouseschool_get_scheme_storage',
				"refresh" => false,
				"colorpicker" => "tiny",
				"type" => "scheme_editor"
				),


			// 'Hidden'
			'media_title' => array(
				"title" => esc_html__('Media title', 'lighthouseschool'),
				"desc" => wp_kses_data( __('Used as title for the audio and video item in this post', 'lighthouseschool') ),
				"override" => array(
					'mode' => 'post',
					'section' => esc_html__('Content', 'lighthouseschool')
				),
				"hidden" => true,
				"std" => '',
				"type" => LIGHTHOUSESCHOOL_THEME_FREE ? "hidden" : "text"
				),
			'media_author' => array(
				"title" => esc_html__('Media author', 'lighthouseschool'),
				"desc" => wp_kses_data( __('Used as author name for the audio and video item in this post', 'lighthouseschool') ),
				"override" => array(
					'mode' => 'post',
					'section' => esc_html__('Content', 'lighthouseschool')
				),
				"hidden" => true,
				"std" => '',
				"type" => LIGHTHOUSESCHOOL_THEME_FREE ? "hidden" : "text"
				),


			// Internal options.
			// Attention! Don't change any options in the section below!
			'reset_options' => array(
				"title" => '',
				"desc" => '',
				"std" => '0',
				"type" => "hidden",
				),

			'last_option' => array(
				"title" => '',
				"desc" => '',
				"std" => 1,
				"type" => "hidden",
				),

		));


		// Prepare panel 'Fonts'
		$fonts = array(
		
			// 'Fonts'
			'fonts' => array(
				"title" => esc_html__('Typography', 'lighthouseschool'),
				"desc" => '',
				"priority" => 200,
				"type" => "panel"
				),

			// Fonts - Load_fonts
			'load_fonts' => array(
				"title" => esc_html__('Load fonts', 'lighthouseschool'),
				"desc" => wp_kses_data( __('Specify fonts to load when theme start. You can use them in the base theme elements: headers, text, menu, links, input fields, etc.', 'lighthouseschool') )
						. '<br>'
						. wp_kses_data( __('<b>Attention!</b> Press "Refresh" button to reload preview area after the all fonts are changed', 'lighthouseschool') ),
				"type" => "section"
				),
			'load_fonts_subset' => array(
				"title" => esc_html__('Google fonts subsets', 'lighthouseschool'),
				"desc" => wp_kses_data( __('Specify comma separated list of the subsets which will be load from Google fonts', 'lighthouseschool') )
						. '<br>'
						. wp_kses_data( __('Available subsets are: latin,latin-ext,cyrillic,cyrillic-ext,greek,greek-ext,vietnamese', 'lighthouseschool') ),
				"class" => "lighthouseschool_column-1_3 lighthouseschool_new_row",
				"refresh" => false,
				"std" => '$lighthouseschool_get_load_fonts_subset',
				"type" => "text"
				)
		);

		for ($i=1; $i<=lighthouseschool_get_theme_setting('max_load_fonts'); $i++) {
			if (lighthouseschool_get_value_gp('page') != 'theme_options') {
				$fonts["load_fonts-{$i}-info"] = array(
					"title" => esc_html(sprintf(__('Font %s', 'lighthouseschool'), $i)),
					"desc" => '',
					"type" => "info",
					);
			}
			$fonts["load_fonts-{$i}-name"] = array(
				"title" => esc_html__('Font name', 'lighthouseschool'),
				"desc" => '',
				"class" => "lighthouseschool_column-1_3 lighthouseschool_new_row",
				"refresh" => false,
				"std" => '$lighthouseschool_get_load_fonts_option',
				"type" => "text"
				);
			$fonts["load_fonts-{$i}-family"] = array(
				"title" => esc_html__('Font family', 'lighthouseschool'),
				"desc" => $i==1 
							? wp_kses_data( __('Select font family to use it if font above is not available', 'lighthouseschool') )
							: '',
				"class" => "lighthouseschool_column-1_3",
				"refresh" => false,
				"std" => '$lighthouseschool_get_load_fonts_option',
				"options" => array(
					'inherit' => esc_html__("Inherit", 'lighthouseschool'),
					'serif' => esc_html__('serif', 'lighthouseschool'),
					'sans-serif' => esc_html__('sans-serif', 'lighthouseschool'),
					'monospace' => esc_html__('monospace', 'lighthouseschool'),
					'cursive' => esc_html__('cursive', 'lighthouseschool'),
					'fantasy' => esc_html__('fantasy', 'lighthouseschool')
				),
				"type" => "select"
				);
			$fonts["load_fonts-{$i}-styles"] = array(
				"title" => esc_html__('Font styles', 'lighthouseschool'),
				"desc" => $i==1 
							? wp_kses_data( __('Font styles used only for the Google fonts. This is a comma separated list of the font weight and styles. For example: 400,400italic,700', 'lighthouseschool') )
								. '<br>'
								. wp_kses_data( __('<b>Attention!</b> Each weight and style increase download size! Specify only used weights and styles.', 'lighthouseschool') )
							: '',
				"class" => "lighthouseschool_column-1_3",
				"refresh" => false,
				"std" => '$lighthouseschool_get_load_fonts_option',
				"type" => "text"
				);
		}
		$fonts['load_fonts_end'] = array(
			"type" => "section_end"
			);

		// Fonts - H1..6, P, Info, Menu, etc.
		$theme_fonts = lighthouseschool_get_theme_fonts();
		foreach ($theme_fonts as $tag=>$v) {
			$fonts["{$tag}_section"] = array(
				"title" => !empty($v['title']) 
								? $v['title'] 
								: esc_html(sprintf(__('%s settings', 'lighthouseschool'), $tag)),
				"desc" => !empty($v['description']) 
								? $v['description'] 
								: wp_kses( sprintf(__('Font settings of the "%s" tag.', 'lighthouseschool'), $tag), 'lighthouseschool_kses_content' ),
				"type" => "section",
				);
	
			foreach ($v as $css_prop=>$css_value) {
				if (in_array($css_prop, array('title', 'description'))) continue;
				$options = '';
				$type = 'text';
				$title = ucfirst(str_replace('-', ' ', $css_prop));
				if ($css_prop == 'font-family') {
					$type = 'select';
					$options = array();
				} else if ($css_prop == 'font-weight') {
					$type = 'select';
					$options = array(
						'inherit' => esc_html__("Inherit", 'lighthouseschool'),
						'100' => esc_html__('100 (Light)', 'lighthouseschool'), 
						'200' => esc_html__('200 (Light)', 'lighthouseschool'), 
						'300' => esc_html__('300 (Thin)',  'lighthouseschool'),
						'400' => esc_html__('400 (Normal)', 'lighthouseschool'),
						'500' => esc_html__('500 (Semibold)', 'lighthouseschool'),
						'600' => esc_html__('600 (Semibold)', 'lighthouseschool'),
						'700' => esc_html__('700 (Bold)', 'lighthouseschool'),
						'800' => esc_html__('800 (Black)', 'lighthouseschool'),
						'900' => esc_html__('900 (Black)', 'lighthouseschool')
					);
				} else if ($css_prop == 'font-style') {
					$type = 'select';
					$options = array(
						'inherit' => esc_html__("Inherit", 'lighthouseschool'),
						'normal' => esc_html__('Normal', 'lighthouseschool'), 
						'italic' => esc_html__('Italic', 'lighthouseschool')
					);
				} else if ($css_prop == 'text-decoration') {
					$type = 'select';
					$options = array(
						'inherit' => esc_html__("Inherit", 'lighthouseschool'),
						'none' => esc_html__('None', 'lighthouseschool'), 
						'underline' => esc_html__('Underline', 'lighthouseschool'),
						'overline' => esc_html__('Overline', 'lighthouseschool'),
						'line-through' => esc_html__('Line-through', 'lighthouseschool')
					);
				} else if ($css_prop == 'text-transform') {
					$type = 'select';
					$options = array(
						'inherit' => esc_html__("Inherit", 'lighthouseschool'),
						'none' => esc_html__('None', 'lighthouseschool'), 
						'uppercase' => esc_html__('Uppercase', 'lighthouseschool'),
						'lowercase' => esc_html__('Lowercase', 'lighthouseschool'),
						'capitalize' => esc_html__('Capitalize', 'lighthouseschool')
					);
				}
				$fonts["{$tag}_{$css_prop}"] = array(
					"title" => $title,
					"desc" => '',
					"class" => "lighthouseschool_column-1_5",
					"refresh" => false,
					"std" => '$lighthouseschool_get_theme_fonts_option',
					"options" => $options,
					"type" => $type
				);
			}
			
			$fonts["{$tag}_section_end"] = array(
				"type" => "section_end"
				);
		}

		$fonts['fonts_end'] = array(
			"type" => "panel_end"
			);

		// Add fonts parameters to Theme Options
		lighthouseschool_storage_set_array_before('options', 'panel_colors', $fonts);

		// Add Header Video if WP version < 4.7
		if (!function_exists('get_header_video_url')) {
			lighthouseschool_storage_set_array_after('options', 'header_image_override', 'header_video', array(
				"title" => esc_html__('Header video', 'lighthouseschool'),
				"desc" => wp_kses_data( __("Select video to use it as background for the header", 'lighthouseschool') ),
				"override" => array(
					'mode' => 'page',
					'section' => esc_html__('Header', 'lighthouseschool')
				),
				"std" => '',
				"type" => "video"
				)
			);
		}
	}
}


// Returns a list of options that can be overridden for CPT
if (!function_exists('lighthouseschool_options_get_list_cpt_options')) {
	function lighthouseschool_options_get_list_cpt_options($cpt, $title='') {
		if (empty($title)) $title = ucfirst($cpt);
		return array(
					"header_info_{$cpt}" => array(
						"title" => esc_html__('Header', 'lighthouseschool'),
						"desc" => '',
						"type" => "info",
						),
					"header_style_{$cpt}" => array(
						"title" => esc_html__('Header style', 'lighthouseschool'),
						"desc" => wp_kses_data( sprintf(__('Select style to display the site header on the %s pages', 'lighthouseschool'), $title) ),
						"std" => 'inherit',
						"options" => array(),
						"type" => LIGHTHOUSESCHOOL_THEME_FREE ? "hidden" : "select"
						),
					"header_position_{$cpt}" => array(
						"title" => esc_html__('Header position', 'lighthouseschool'),
						"desc" => wp_kses_data( sprintf(__('Select position to display the site header on the %s pages', 'lighthouseschool'), $title) ),
						"std" => 'inherit',
						"options" => array(),
						"type" => LIGHTHOUSESCHOOL_THEME_FREE ? "hidden" : "switch"
						),
					"header_image_override_{$cpt}" => array(
						"title" => esc_html__('Header image override', 'lighthouseschool'),
						"desc" => wp_kses_data( __("Allow override the header image with the post's featured image", 'lighthouseschool') ),
						"std" => 0,
						"type" => LIGHTHOUSESCHOOL_THEME_FREE ? "hidden" : "checkbox"
						),
					"header_widgets_{$cpt}" => array(
						"title" => esc_html__('Header widgets', 'lighthouseschool'),
						"desc" => wp_kses_data( sprintf(__('Select set of widgets to show in the header on the %s pages', 'lighthouseschool'), $title) ),
						"std" => 'hide',
						"options" => array(),
						"type" => "select"
						),
						
					"sidebar_info_{$cpt}" => array(
						"title" => esc_html__('Sidebar', 'lighthouseschool'),
						"desc" => '',
						"type" => "info",
						),
					"sidebar_position_{$cpt}" => array(
						"title" => esc_html__('Sidebar position', 'lighthouseschool'),
						"desc" => wp_kses_data( sprintf(__('Select position to show sidebar on the %s pages', 'lighthouseschool'), $title) ),
						"refresh" => false,
						"std" => 'left',
						"options" => array(),
						"type" => "switch"
						),
					"sidebar_widgets_{$cpt}" => array(
						"title" => esc_html__('Sidebar widgets', 'lighthouseschool'),
						"desc" => wp_kses_data( sprintf(__('Select sidebar to show on the %s pages', 'lighthouseschool'), $title) ),
						"dependency" => array(
							"sidebar_position_{$cpt}" => array('left', 'right')
						),
						"std" => 'hide',
						"options" => array(),
						"type" => "select"
						),
					"hide_sidebar_on_single_{$cpt}" => array(
						"title" => esc_html__('Hide sidebar on the single pages', 'lighthouseschool'),
						"desc" => wp_kses_data( __("Hide sidebar on the single page", 'lighthouseschool') ),
						"std" => 0,
						"type" => "checkbox"
						),
						
					"footer_info_{$cpt}" => array(
						"title" => esc_html__('Footer', 'lighthouseschool'),
						"desc" => '',
						"type" => "info",
						),
					'footer_style_{$cpt}' => array(
						"title" => esc_html__('Footer style', 'lighthouseschool'),
						"desc" => wp_kses_data( __('Select style to display the site footer', 'lighthouseschool') ),
						"std" => 'inherit',
						"options" => array(),
						"type" => LIGHTHOUSESCHOOL_THEME_FREE ? "hidden" : "select"
						),
					"footer_widgets_{$cpt}" => array(
						"title" => esc_html__('Footer widgets', 'lighthouseschool'),
						"desc" => wp_kses_data( __('Select set of widgets to show in the footer', 'lighthouseschool') ),
						"std" => 'footer_widgets',
						"options" => array(),
						"type" => "select"
						),
					"footer_columns_{$cpt}" => array(
						"title" => esc_html__('Footer columns', 'lighthouseschool'),
						"desc" => wp_kses_data( __('Select number columns to show widgets in the footer. If 0 - autodetect by the widgets count', 'lighthouseschool') ),
						"dependency" => array(
							"footer_widgets_{$cpt}" => array('^hide')
						),
						"std" => 0,
						"options" => lighthouseschool_get_list_range(0,6),
						"type" => "select"
						),
					"footer_wide_{$cpt}" => array(
						"title" => esc_html__('Footer fullwide', 'lighthouseschool'),
						"desc" => wp_kses_data( __('Do you want to stretch the footer to the entire window width?', 'lighthouseschool') ),
						"std" => 0,
						"type" => "checkbox"
						),
						
					"widgets_info_{$cpt}" => array(
						"title" => esc_html__('Additional panels', 'lighthouseschool'),
						"desc" => '',
						"type" => LIGHTHOUSESCHOOL_THEME_FREE ? "hidden" : "info",
						),
					"widgets_above_page_{$cpt}" => array(
						"title" => esc_html__('Widgets at the top of the page', 'lighthouseschool'),
						"desc" => wp_kses_data( __('Select widgets to show at the top of the page (above content and sidebar)', 'lighthouseschool') ),
						"std" => 'hide',
						"options" => array(),
						"type" => LIGHTHOUSESCHOOL_THEME_FREE ? "hidden" : "select"
						),
					"widgets_above_content_{$cpt}" => array(
						"title" => esc_html__('Widgets above the content', 'lighthouseschool'),
						"desc" => wp_kses_data( __('Select widgets to show at the beginning of the content area', 'lighthouseschool') ),
						"std" => 'hide',
						"options" => array(),
						"type" => LIGHTHOUSESCHOOL_THEME_FREE ? "hidden" : "select"
						),
					"widgets_below_content_{$cpt}" => array(
						"title" => esc_html__('Widgets below the content', 'lighthouseschool'),
						"desc" => wp_kses_data( __('Select widgets to show at the ending of the content area', 'lighthouseschool') ),
						"std" => 'hide',
						"options" => array(),
						"type" => LIGHTHOUSESCHOOL_THEME_FREE ? "hidden" : "select"
						),
					"widgets_below_page_{$cpt}" => array(
						"title" => esc_html__('Widgets at the bottom of the page', 'lighthouseschool'),
						"desc" => wp_kses_data( __('Select widgets to show at the bottom of the page (below content and sidebar)', 'lighthouseschool') ),
						"std" => 'hide',
						"options" => array(),
						"type" => LIGHTHOUSESCHOOL_THEME_FREE ? "hidden" : "select"
						)
					);
	}
}


// Return lists with choises when its need in the admin mode
if (!function_exists('lighthouseschool_options_get_list_choises')) {
	add_filter('lighthouseschool_filter_options_get_list_choises', 'lighthouseschool_options_get_list_choises', 10, 2);
	function lighthouseschool_options_get_list_choises($list, $id) {
		if (is_array($list) && count($list)==0) {
			if (strpos($id, 'header_style')===0)
				$list = lighthouseschool_get_list_header_styles(strpos($id, 'header_style_')===0);
			else if (strpos($id, 'header_position')===0)
				$list = lighthouseschool_get_list_header_positions(strpos($id, 'header_position_')===0);
			else if (strpos($id, 'header_widgets')===0)
				$list = lighthouseschool_get_list_sidebars(strpos($id, 'header_widgets_')===0, true);
			else if (substr($id, -7) == '_scheme')
				$list = lighthouseschool_get_list_schemes($id!='color_scheme');
			else if (strpos($id, 'sidebar_widgets')===0)
				$list = lighthouseschool_get_list_sidebars(strpos($id, 'sidebar_widgets_')===0, true);
			else if (strpos($id, 'sidebar_position')===0)
				$list = lighthouseschool_get_list_sidebars_positions(strpos($id, 'sidebar_position_')===0);
			else if (strpos($id, 'widgets_above_page')===0)
				$list = lighthouseschool_get_list_sidebars(strpos($id, 'widgets_above_page_')===0, true);
			else if (strpos($id, 'widgets_above_content')===0)
				$list = lighthouseschool_get_list_sidebars(strpos($id, 'widgets_above_content_')===0, true);
			else if (strpos($id, 'widgets_below_page')===0)
				$list = lighthouseschool_get_list_sidebars(strpos($id, 'widgets_below_page_')===0, true);
			else if (strpos($id, 'widgets_below_content')===0)
				$list = lighthouseschool_get_list_sidebars(strpos($id, 'widgets_below_content_')===0, true);
			else if (strpos($id, 'footer_style')===0)
				$list = lighthouseschool_get_list_footer_styles(strpos($id, 'footer_style_')===0);
			else if (strpos($id, 'footer_widgets')===0)
				$list = lighthouseschool_get_list_sidebars(strpos($id, 'footer_widgets_')===0, true);
			else if (strpos($id, 'blog_style')===0)
				$list = lighthouseschool_get_list_blog_styles(strpos($id, 'blog_style_')===0);
			else if (strpos($id, 'post_type')===0)
				$list = lighthouseschool_get_list_posts_types();
			else if (strpos($id, 'parent_cat')===0)
				$list = lighthouseschool_array_merge(array(0 => esc_html__('- Select category -', 'lighthouseschool')), lighthouseschool_get_list_categories());
			else if (strpos($id, 'blog_animation')===0)
				$list = lighthouseschool_get_list_animations_in();
			else if ($id == 'color_scheme_editor')
				$list = lighthouseschool_get_list_schemes();
			else if (strpos($id, '_font-family') > 0)
				$list = lighthouseschool_get_list_load_fonts(true);
		}
		return $list;
	}
}
?>