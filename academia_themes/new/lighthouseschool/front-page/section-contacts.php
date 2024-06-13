<div class="front_page_section front_page_section_contacts<?php
			$lighthouseschool_scheme = lighthouseschool_get_theme_option('front_page_contacts_scheme');
			if (!lighthouseschool_is_inherit($lighthouseschool_scheme)) echo ' scheme_'.esc_attr($lighthouseschool_scheme);
			echo ' front_page_section_paddings_'.esc_attr(lighthouseschool_get_theme_option('front_page_contacts_paddings'));
		?>"<?php
		$lighthouseschool_css = '';
		$lighthouseschool_bg_image = lighthouseschool_get_theme_option('front_page_contacts_bg_image');
		if (!empty($lighthouseschool_bg_image)) 
			$lighthouseschool_css .= 'background-image: url('.esc_url(lighthouseschool_get_attachment_url($lighthouseschool_bg_image)).');';
		if (!empty($lighthouseschool_css))
			echo " style=\"{$lighthouseschool_css}\"";
?>><?php
	// Add anchor
	$lighthouseschool_anchor_icon = lighthouseschool_get_theme_option('front_page_contacts_anchor_icon');	
	$lighthouseschool_anchor_text = lighthouseschool_get_theme_option('front_page_contacts_anchor_text');	
	if ((!empty($lighthouseschool_anchor_icon) || !empty($lighthouseschool_anchor_text)) && shortcode_exists('trx_sc_anchor')) {
		echo do_shortcode('[trx_sc_anchor id="front_page_section_contacts"'
										. (!empty($lighthouseschool_anchor_icon) ? ' icon="'.esc_attr($lighthouseschool_anchor_icon).'"' : '')
										. (!empty($lighthouseschool_anchor_text) ? ' title="'.esc_attr($lighthouseschool_anchor_text).'"' : '')
										. ']');
	}
	?>
	<div class="front_page_section_inner front_page_section_contacts_inner<?php
			if (lighthouseschool_get_theme_option('front_page_contacts_fullheight'))
				echo ' lighthouseschool-full-height sc_layouts_flex sc_layouts_columns_middle';
			?>"<?php
			$lighthouseschool_css = '';
			$lighthouseschool_bg_mask = lighthouseschool_get_theme_option('front_page_contacts_bg_mask');
			$lighthouseschool_bg_color = lighthouseschool_get_theme_option('front_page_contacts_bg_color');
			if (!empty($lighthouseschool_bg_color) && $lighthouseschool_bg_mask > 0)
				$lighthouseschool_css .= 'background-color: '.esc_attr($lighthouseschool_bg_mask==1
																	? $lighthouseschool_bg_color
																	: lighthouseschool_hex2rgba($lighthouseschool_bg_color, $lighthouseschool_bg_mask)
																).';';
			if (!empty($lighthouseschool_css))
				echo " style=\"{$lighthouseschool_css}\"";
	?>>
		<div class="front_page_section_content_wrap front_page_section_contacts_content_wrap content_wrap">
			<?php

			// Title and description
			$lighthouseschool_caption = lighthouseschool_get_theme_option('front_page_contacts_caption');
			$lighthouseschool_description = lighthouseschool_get_theme_option('front_page_contacts_description');
			if (!empty($lighthouseschool_caption) || !empty($lighthouseschool_description) || (current_user_can('edit_theme_options') && is_customize_preview())) {
				// Caption
				if (!empty($lighthouseschool_caption) || (current_user_can('edit_theme_options') && is_customize_preview())) {
					?><h2 class="front_page_section_caption front_page_section_contacts_caption front_page_block_<?php echo !empty($lighthouseschool_caption) ? 'filled' : 'empty'; ?>"><?php
						echo wp_kses($lighthouseschool_caption, 'lighthouseschool_kses_content');
					?></h2><?php
				}
			
				// Description
				if (!empty($lighthouseschool_description) || (current_user_can('edit_theme_options') && is_customize_preview())) {
					?><div class="front_page_section_description front_page_section_contacts_description front_page_block_<?php echo !empty($lighthouseschool_description) ? 'filled' : 'empty'; ?>"><?php
						echo wpautop(wp_kses($lighthouseschool_description, 'lighthouseschool_kses_content'));
					?></div><?php
				}
			}

			// Content (text)
			$lighthouseschool_content = lighthouseschool_get_theme_option('front_page_contacts_content');
			$lighthouseschool_layout = lighthouseschool_get_theme_option('front_page_contacts_layout');
			if ($lighthouseschool_layout == 'columns' && (!empty($lighthouseschool_content) || (current_user_can('edit_theme_options') && is_customize_preview()))) {
				?><div class="front_page_section_columns front_page_section_contacts_columns columns_wrap">
					<div class="column-1_3">
				<?php
			}

			if ((!empty($lighthouseschool_content) || (current_user_can('edit_theme_options') && is_customize_preview()))) {
				?><div class="front_page_section_content front_page_section_contacts_content front_page_block_<?php echo !empty($lighthouseschool_content) ? 'filled' : 'empty'; ?>"><?php
					echo wp_kses($lighthouseschool_content, 'lighthouseschool_kses_content');
				?></div><?php
			}

			if ($lighthouseschool_layout == 'columns' && (!empty($lighthouseschool_content) || (current_user_can('edit_theme_options') && is_customize_preview()))) {
				?></div><div class="column-2_3"><?php
			}
		
			// Shortcode output
			$lighthouseschool_sc = lighthouseschool_get_theme_option('front_page_contacts_shortcode');
			if (!empty($lighthouseschool_sc) || (current_user_can('edit_theme_options') && is_customize_preview())) {
				?><div class="front_page_section_output front_page_section_contacts_output front_page_block_<?php echo !empty($lighthouseschool_sc) ? 'filled' : 'empty'; ?>"><?php
					lighthouseschool_show_layout(do_shortcode($lighthouseschool_sc));
				?></div><?php
			}

			if ($lighthouseschool_layout == 'columns' && (!empty($lighthouseschool_content) || (current_user_can('edit_theme_options') && is_customize_preview()))) {
				?></div></div><?php
			}
			?>			
		</div>
	</div>
</div>