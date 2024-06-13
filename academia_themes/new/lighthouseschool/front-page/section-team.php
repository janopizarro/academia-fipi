<div class="front_page_section front_page_section_team<?php
			$lighthouseschool_scheme = lighthouseschool_get_theme_option('front_page_team_scheme');
			if (!lighthouseschool_is_inherit($lighthouseschool_scheme)) echo ' scheme_'.esc_attr($lighthouseschool_scheme);
			echo ' front_page_section_paddings_'.esc_attr(lighthouseschool_get_theme_option('front_page_team_paddings'));
		?>"<?php
		$lighthouseschool_css = '';
		$lighthouseschool_bg_image = lighthouseschool_get_theme_option('front_page_team_bg_image');
		if (!empty($lighthouseschool_bg_image)) 
			$lighthouseschool_css .= 'background-image: url('.esc_url(lighthouseschool_get_attachment_url($lighthouseschool_bg_image)).');';
		if (!empty($lighthouseschool_css))
			echo " style=\"{$lighthouseschool_css}\"";
?>><?php
	// Add anchor
	$lighthouseschool_anchor_icon = lighthouseschool_get_theme_option('front_page_team_anchor_icon');	
	$lighthouseschool_anchor_text = lighthouseschool_get_theme_option('front_page_team_anchor_text');	
	if ((!empty($lighthouseschool_anchor_icon) || !empty($lighthouseschool_anchor_text)) && shortcode_exists('trx_sc_anchor')) {
		echo do_shortcode('[trx_sc_anchor id="front_page_section_team"'
										. (!empty($lighthouseschool_anchor_icon) ? ' icon="'.esc_attr($lighthouseschool_anchor_icon).'"' : '')
										. (!empty($lighthouseschool_anchor_text) ? ' title="'.esc_attr($lighthouseschool_anchor_text).'"' : '')
										. ']');
	}
	?>
	<div class="front_page_section_inner front_page_section_team_inner<?php
			if (lighthouseschool_get_theme_option('front_page_team_fullheight'))
				echo ' lighthouseschool-full-height sc_layouts_flex sc_layouts_columns_middle';
			?>"<?php
			$lighthouseschool_css = '';
			$lighthouseschool_bg_mask = lighthouseschool_get_theme_option('front_page_team_bg_mask');
			$lighthouseschool_bg_color = lighthouseschool_get_theme_option('front_page_team_bg_color');
			if (!empty($lighthouseschool_bg_color) && $lighthouseschool_bg_mask > 0)
				$lighthouseschool_css .= 'background-color: '.esc_attr($lighthouseschool_bg_mask==1
																	? $lighthouseschool_bg_color
																	: lighthouseschool_hex2rgba($lighthouseschool_bg_color, $lighthouseschool_bg_mask)
																).';';
			if (!empty($lighthouseschool_css))
				echo " style=\"{$lighthouseschool_css}\"";
	?>>
		<div class="front_page_section_content_wrap front_page_section_team_content_wrap content_wrap">
			<?php
			// Caption
			$lighthouseschool_caption = lighthouseschool_get_theme_option('front_page_team_caption');
			if (!empty($lighthouseschool_caption) || (current_user_can('edit_theme_options') && is_customize_preview())) {
				?><h2 class="front_page_section_caption front_page_section_team_caption front_page_block_<?php echo !empty($lighthouseschool_caption) ? 'filled' : 'empty'; ?>"><?php echo wp_kses($lighthouseschool_caption, 'lighthouseschool_kses_content'); ?></h2><?php
			}
		
			// Description (text)
			$lighthouseschool_description = lighthouseschool_get_theme_option('front_page_team_description');
			if (!empty($lighthouseschool_description) || (current_user_can('edit_theme_options') && is_customize_preview())) {
				?><div class="front_page_section_description front_page_section_team_description front_page_block_<?php echo !empty($lighthouseschool_description) ? 'filled' : 'empty'; ?>"><?php echo wpautop(wp_kses($lighthouseschool_description, 'lighthouseschool_kses_content')); ?></div><?php
			}
		
			// Content (widgets)
			?><div class="front_page_section_output front_page_section_team_output"><?php 
				if (is_active_sidebar('front_page_team_widgets')) {
					dynamic_sidebar( 'front_page_team_widgets' );
				} else if (current_user_can( 'edit_theme_options' )) {
					if (!lighthouseschool_exists_trx_addons())
						lighthouseschool_customizer_need_trx_addons_message();
					else
						lighthouseschool_customizer_need_widgets_message('front_page_team_caption', 'ThemeREX Addons - Team');
				}
			?></div>
		</div>
	</div>
</div>