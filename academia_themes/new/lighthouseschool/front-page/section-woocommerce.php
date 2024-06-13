<div class="front_page_section front_page_section_woocommerce<?php
			$lighthouseschool_scheme = lighthouseschool_get_theme_option('front_page_woocommerce_scheme');
			if (!lighthouseschool_is_inherit($lighthouseschool_scheme)) echo ' scheme_'.esc_attr($lighthouseschool_scheme);
			echo ' front_page_section_paddings_'.esc_attr(lighthouseschool_get_theme_option('front_page_woocommerce_paddings'));
		?>"<?php
		$lighthouseschool_css = '';
		$lighthouseschool_bg_image = lighthouseschool_get_theme_option('front_page_woocommerce_bg_image');
		if (!empty($lighthouseschool_bg_image)) 
			$lighthouseschool_css .= 'background-image: url('.esc_url(lighthouseschool_get_attachment_url($lighthouseschool_bg_image)).');';
		if (!empty($lighthouseschool_css))
			echo " style=\"{$lighthouseschool_css}\"";
?>><?php
	// Add anchor
	$lighthouseschool_anchor_icon = lighthouseschool_get_theme_option('front_page_woocommerce_anchor_icon');	
	$lighthouseschool_anchor_text = lighthouseschool_get_theme_option('front_page_woocommerce_anchor_text');	
	if ((!empty($lighthouseschool_anchor_icon) || !empty($lighthouseschool_anchor_text)) && shortcode_exists('trx_sc_anchor')) {
		echo do_shortcode('[trx_sc_anchor id="front_page_section_woocommerce"'
										. (!empty($lighthouseschool_anchor_icon) ? ' icon="'.esc_attr($lighthouseschool_anchor_icon).'"' : '')
										. (!empty($lighthouseschool_anchor_text) ? ' title="'.esc_attr($lighthouseschool_anchor_text).'"' : '')
										. ']');
	}
	?>
	<div class="front_page_section_inner front_page_section_woocommerce_inner<?php
			if (lighthouseschool_get_theme_option('front_page_woocommerce_fullheight'))
				echo ' lighthouseschool-full-height sc_layouts_flex sc_layouts_columns_middle';
			?>"<?php
			$lighthouseschool_css = '';
			$lighthouseschool_bg_mask = lighthouseschool_get_theme_option('front_page_woocommerce_bg_mask');
			$lighthouseschool_bg_color = lighthouseschool_get_theme_option('front_page_woocommerce_bg_color');
			if (!empty($lighthouseschool_bg_color) && $lighthouseschool_bg_mask > 0)
				$lighthouseschool_css .= 'background-color: '.esc_attr($lighthouseschool_bg_mask==1
																	? $lighthouseschool_bg_color
																	: lighthouseschool_hex2rgba($lighthouseschool_bg_color, $lighthouseschool_bg_mask)
																).';';
			if (!empty($lighthouseschool_css))
				echo " style=\"{$lighthouseschool_css}\"";
	?>>
		<div class="front_page_section_content_wrap front_page_section_woocommerce_content_wrap content_wrap woocommerce">
			<?php
			// Content wrap with title and description
			$lighthouseschool_caption = lighthouseschool_get_theme_option('front_page_woocommerce_caption');
			$lighthouseschool_description = lighthouseschool_get_theme_option('front_page_woocommerce_description');
			if (!empty($lighthouseschool_caption) || !empty($lighthouseschool_description) || (current_user_can('edit_theme_options') && is_customize_preview())) {
				// Caption
				if (!empty($lighthouseschool_caption) || (current_user_can('edit_theme_options') && is_customize_preview())) {
					?><h2 class="front_page_section_caption front_page_section_woocommerce_caption front_page_block_<?php echo !empty($lighthouseschool_caption) ? 'filled' : 'empty'; ?>"><?php
						echo wp_kses($lighthouseschool_caption, 'lighthouseschool_kses_content');
					?></h2><?php
				}
			
				// Description (text)
				if (!empty($lighthouseschool_description) || (current_user_can('edit_theme_options') && is_customize_preview())) {
					?><div class="front_page_section_description front_page_section_woocommerce_description front_page_block_<?php echo !empty($lighthouseschool_description) ? 'filled' : 'empty'; ?>"><?php
						echo wpautop(wp_kses($lighthouseschool_description, 'lighthouseschool_kses_content'));
					?></div><?php
				}
			}
		
			// Content (widgets)
			?><div class="front_page_section_output front_page_section_woocommerce_output list_products shop_mode_thumbs"><?php 
				$lighthouseschool_woocommerce_sc = lighthouseschool_get_theme_option('front_page_woocommerce_products');
				if ($lighthouseschool_woocommerce_sc == 'products') {
					$lighthouseschool_woocommerce_sc_ids = lighthouseschool_get_theme_option('front_page_woocommerce_products_per_page');
					$lighthouseschool_woocommerce_sc_per_page = count(explode(',', $lighthouseschool_woocommerce_sc_ids));
				} else {
					$lighthouseschool_woocommerce_sc_per_page = max(1, (int) lighthouseschool_get_theme_option('front_page_woocommerce_products_per_page'));
				}
				$lighthouseschool_woocommerce_sc_columns = max(1, min($lighthouseschool_woocommerce_sc_per_page, (int) lighthouseschool_get_theme_option('front_page_woocommerce_products_columns')));
				echo do_shortcode("[{$lighthouseschool_woocommerce_sc}"
									. ($lighthouseschool_woocommerce_sc == 'products' 
											? ' ids="'.esc_attr($lighthouseschool_woocommerce_sc_ids).'"' 
											: '')
									. ($lighthouseschool_woocommerce_sc == 'product_category' 
											? ' category="'.esc_attr(lighthouseschool_get_theme_option('front_page_woocommerce_products_categories')).'"' 
											: '')
									. ($lighthouseschool_woocommerce_sc != 'best_selling_products' 
											? ' orderby="'.esc_attr(lighthouseschool_get_theme_option('front_page_woocommerce_products_orderby')).'"'
											  . ' order="'.esc_attr(lighthouseschool_get_theme_option('front_page_woocommerce_products_order')).'"' 
											: '')
									. ' per_page="'.esc_attr($lighthouseschool_woocommerce_sc_per_page).'"' 
									. ' columns="'.esc_attr($lighthouseschool_woocommerce_sc_columns).'"' 
									. ']');
			?></div>
		</div>
	</div>
</div>