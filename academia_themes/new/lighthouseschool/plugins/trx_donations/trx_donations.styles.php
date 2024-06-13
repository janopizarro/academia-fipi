<?php
// Add plugin-specific colors and fonts to the custom CSS
if ( !function_exists( 'lighthouseschool_trx_donations_get_css' ) ) {
	add_filter( 'lighthouseschool_filter_get_css', 'lighthouseschool_trx_donations_get_css', 10, 4 );
	function lighthouseschool_trx_donations_get_css($css, $colors, $fonts, $scheme='') {
		if (isset($css['fonts']) && $fonts) {
			$css['fonts'] .= <<<CSS
.post_type_donation.post_item_single .post_sidebar .post_help,
.sc_donations_info .sc_donations_supporters_item_amount_value,
.sc_donations_info .sc_donations_supporters_item_name {
	{$fonts['h5_font-family']}
}
.post_type_donation .post_info_item.post_date {
	{$fonts['h1_font-family']}
}
CSS;
		}

		if (isset($css['colors']) && $colors) {
			$css['colors'] .= <<<CSS
.sc_donations_info .sc_donations_data_number {
	color: {$colors['text_dark']};
}
.sc_donations_info .sc_donations_supporters_item_amount_inner,
.sc_donations_info .sc_donations_supporters_item_info_inner {
	background-color: {$colors['alter_bg_color']};
}
.sc_donations_info .sc_donations_supporters_item:hover .sc_donations_supporters_item_amount_inner,
.sc_donations_info .sc_donations_supporters_item:hover .sc_donations_supporters_item_info_inner {
	background-color: {$colors['alter_bg_hover']};
}
.sc_donations_info .sc_donations_supporters_item_amount_value {
	color: {$colors['alter_link']};
}
.sc_donations_info .sc_donations_supporters_item_name {
	color: {$colors['alter_dark']};
}
.sc_donations_info .sc_donations_supporters_item_amount_date,
.sc_donations_info .sc_donations_supporters_item_message {
	color: {$colors['alter_text']};
}
.post_type_donation .post_body {
	background-color: {$colors['alter_bg_color']};
}
.post_type_donation .post_info_item.post_date {
	color: {$colors['text_hover']};
}
.post_type_donation .post_meta .post_categories a {
	color: {$colors['bg_color']};
	background-color: {$colors['text_dark']};
}
.post_type_donation .post_meta .post_categories a:hover {
	color: {$colors['bg_color']};
	background-color: {$colors['text_link']};
}
.sc_donations_form_field.sc_donations_form_field_note {
	color: {$colors['bg_color']};
	background-color: {$colors['text_dark']};
}
.sc_donations_form_field_note:before {
	color: {$colors['bg_color']};
}
.post_type_donation.post_item_single .post_footer {
	background-color: {$colors['alter_bg_color']};
}
.post_type_donation .post_footer .post_categories a {
	color: {$colors['text']};
}
.post_type_donation .post_footer .post_categories a:hover {
	color: {$colors['text_dark']};
}
.post_type_donation.post_item_single .sc_socials_share_item {
	color: {$colors['bg_color']};
	background-color: {$colors['text_link']};
}
.post_type_donation.post_item_single .sc_socials_share_item:hover {
	color: {$colors['text_dark']};
	background-color: {$colors['text_hover']};
}
.post_type_donation.post_item_single .post_sidebar {
    background-color: {$colors['alter_bg_color']};
}
.post_type_donation.post_item_single .post_sidebar .post_raised_title {
	color: {$colors['text_dark']};
	background-color: {$colors['text_hover']};
}
.post_type_donation.post_item_single .post_sidebar .post_goal_title {
    color: {$colors['text_hover']};
}
.post_type_donation.post_item_single .post_sidebar .post_raised .post_raised_amount {
    color: {$colors['text_dark']};
}

CSS;
		}
		
		return $css;
	}
}
?>