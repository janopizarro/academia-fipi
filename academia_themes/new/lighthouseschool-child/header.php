<?php
/**
 * The Header: Logo and main menu
 *
 * @package WordPress
 * @subpackage LIGHTHOUSESCHOOL
 * @since LIGHTHOUSESCHOOL 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js scheme_<?php
										 // Class scheme_xxx need in the <html> as context for the <body>!
										 echo esc_attr(lighthouseschool_get_theme_option('color_scheme'));
										 ?>">
<head>
	<?php wp_head(); ?>


	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Arbutus+Slab&display=swap" rel="stylesheet">

	<link href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">


</head>

<body <?php	body_class(); ?>>

	<?php wp_body_open(); ?>

	<?php do_action( 'lighthouseschool_action_before' ); ?>

	<div class="body_wrap">

		<div class="page_wrap"><?php

			// Desktop header
			$lighthouseschool_header_style = lighthouseschool_get_theme_option("header_style");
			if (strpos($lighthouseschool_header_style, 'header-custom-')===0) 
				$lighthouseschool_header_style = lighthouseschool_is_layouts_available() ? 'header-custom' : 'header-default';
			get_template_part( "templates/{$lighthouseschool_header_style}");

			// Side menu
			if (in_array(lighthouseschool_get_theme_option('menu_style'), array('left', 'right'))) {
				get_template_part( 'templates/header-navi-side' );
			}

			// Mobile header
			get_template_part( 'templates/header-mobile');
			?>

			<div class="page_content_wrap scheme_<?php echo esc_attr(lighthouseschool_get_theme_option('color_scheme')); ?>">

				<?php if (lighthouseschool_get_theme_option('body_style') != 'fullscreen') { ?>
				<div class="content_wrap">
				<?php } ?>

					<?php
					// Widgets area above page content
					lighthouseschool_create_widgets_area('widgets_above_page');
					?>				

					<div class="content">
						<?php
						// Widgets area inside page content
						lighthouseschool_create_widgets_area('widgets_above_content');
						?>				
