<?php
/**
 * The template to display the copyright info in the footer
 *
 * @package WordPress
 * @subpackage LIGHTHOUSESCHOOL
 * @since LIGHTHOUSESCHOOL 1.0.10
 */

// Copyright area
$lighthouseschool_footer_scheme =  lighthouseschool_is_inherit(lighthouseschool_get_theme_option('footer_scheme')) ? lighthouseschool_get_theme_option('color_scheme') : lighthouseschool_get_theme_option('footer_scheme');
$lighthouseschool_copyright_scheme = lighthouseschool_is_inherit(lighthouseschool_get_theme_option('copyright_scheme')) ? $lighthouseschool_footer_scheme : lighthouseschool_get_theme_option('copyright_scheme');
?> 
<div class="footer_copyright_wrap scheme_<?php echo esc_attr($lighthouseschool_copyright_scheme); ?>">
	<div class="footer_copyright_inner">
		<div class="content_wrap">
			<div class="copyright_text"><?php
				$lighthouseschool_copyright = lighthouseschool_prepare_macros(lighthouseschool_get_theme_option('copyright'));
				if (!empty($lighthouseschool_copyright)) {
					lighthouseschool_show_layout(do_shortcode(str_replace(array('{{Y}}', '{Y}'), date('Y'), lighthouseschool_get_theme_option('copyright'))));
				}
				?></div>
		</div>
	</div>
</div>
