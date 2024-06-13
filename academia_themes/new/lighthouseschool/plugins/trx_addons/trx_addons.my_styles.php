<?php
// Add plugin-specific colors and fonts to the custom CSS
if (!function_exists('lighthouseschool_trx_addons_my_get_css')) {
	add_filter('lighthouseschool_filter_get_css', 'lighthouseschool_trx_addons_my_get_css', 10, 4);
	function lighthouseschool_trx_addons_my_get_css($css, $colors, $fonts, $scheme='') {


        if (isset($css['fonts']) && $fonts) {
            $css['fonts'] .= <<<CSS

.sc_events_full .event-date .sc_events_date,
.sc_blogger .sc_blogger_item .post_meta_item.post_date,
.trx_addons_events_bold,
.trx_addons_promo_small,
.trx_addons_promo_large,
.trx_addons_shop_promo_small,
.trx_addons_shop_promo_large,
.sc_services_numbered .sc_services_item_title,
.sc_testimonials .sc_testimonials_item_content,
.sc_price_item_title,
.sc_team_item_title,
.trx_addons_timeline_title,
.trx_addons_events_text,
.sc_services_blinked .sc_services_item_title,            
.sc_skills_pie.sc_skills_compact_off .sc_skills_item_title,
.sc_services_default .with_image .sc_services_item_title,
.widget .post_item .post_title {
	{$fonts['h1_font-family']};
}
.trx_addons_events_italic,
.trx_addons_promo_italic,
.trx_addons_shop_promo_italic,
h6 .trx_addons_accent,
.sc_skills_pie.sc_skills_compact_off .sc_skills_total {
	{$fonts['special_font-family']};
}

CSS;
        }

		if (isset($css['colors']) && $colors) {
			$css['colors'] .= <<<CSS
.trx_addons_accent {
	color: {$colors['text_hover']};
}
.trx_addons_dropcap_style_1 {
	color: {$colors['bg_color']};
	background-color: {$colors['text_hover']};
}
.trx_addons_dropcap_style_2 {
	color: {$colors['text_link']};
	background-color: {$colors['bg_color_0']};
}
ul[class*="trx_addons_list"]>li:before {
	color: {$colors['text_dark']};
}
ol > li:before {
	color: {$colors['text_link']};
}
.sc_layouts_row_type_compact .sc_layouts_item_details_line1,
.sc_layouts_row_type_compact .sc_layouts_item_details_line2,
.scheme_self.sc_layouts_row_type_compact .sc_layouts_item_details_line1,
.scheme_self.sc_layouts_row_type_compact .sc_layouts_item_details_line2 {
	color: {$colors['text_dark']};
}
.sc_layouts_row_type_compact .sc_layouts_item_icon,
.scheme_self.sc_layouts_row_type_compact .sc_layouts_item_icon {
	color: {$colors['text_hover']};
}
.footer_wrap .socials_wrap .social_item .social_icon,
.scheme_self.footer_wrap .socials_wrap .social_item .social_icon {
	color: {$colors['text_dark']};
	background-color: {$colors['text_hover']};
}
.footer_wrap .socials_wrap .social_item:hover .social_icon,
.scheme_self.footer_wrap .socials_wrap .social_item:hover .social_icon {
	color: {$colors['bg_color']};
	background-color: {$colors['text_dark']};
}
.sc_skills_pie.sc_skills_compact_off .sc_skills_total {
	color: {$colors['text_hover']};
}
.sc_countdown .sc_countdown_separator {
	color: {$colors['bg_color_0']};
}
.sc_countdown_default .sc_countdown_digits span {
	color: {$colors['bg_color']};
	background-color: {$colors['bg_color_0']};
}
.sc_countdown .sc_countdown_label {
	color: {$colors['bg_color']};
}
.slider_container .slider_pagination_wrap .swiper-pagination-bullet,
.slider_outer .slider_pagination_wrap .swiper-pagination-bullet,
.swiper-pagination-custom .swiper-pagination-button {
	border-color: {$colors['bg_color']};
	background-color: {$colors['bg_color']};
}
.sc_testimonials .slider_container .slider_pagination_wrap .swiper-pagination-bullet,
.sc_testimonials .slider_outer .slider_pagination_wrap .swiper-pagination-bullet,
.sc_testimonials .swiper-pagination-custom .swiper-pagination-button {
	border-color: {$colors['text_hover']};
	background-color: {$colors['text_hover']};
}
.scheme_dark .slider_container .slider_pagination_wrap .swiper-pagination-bullet,
.scheme_dark .slider_outer .slider_pagination_wrap .swiper-pagination-bullet,
.scheme_dark .swiper-pagination-custom .swiper-pagination-button {
	border-color: {$colors['bg_color']};
	background-color: {$colors['bg_color']};
}
.swiper-pagination-custom .swiper-pagination-button.swiper-pagination-button-active,
.slider_container .slider_pagination_wrap .swiper-pagination-bullet.swiper-pagination-bullet-active,
.slider_outer .slider_pagination_wrap .swiper-pagination-bullet.swiper-pagination-bullet-active,
.slider_container .slider_pagination_wrap .swiper-pagination-bullet:hover,
.slider_outer .slider_pagination_wrap .swiper-pagination-bullet:hover {
	border-color: {$colors['text_hover']};
	background-color: {$colors['bg_color_0']};
}

.sc_price_item_price_after {
    color: {$colors['text']};
}
.sc_item_subtitle {
    color: {$colors['text_dark']};
}
.trx_addons_contacts_phone {
    color: {$colors['text_hover']};
}
.trx_addons_contacts_phone:hover {
    color: {$colors['text_dark']};
}
.trx_addons_contacts_title + a:hover{
	color: {$colors['text_dark']} !important;
}
.sc_googlemap_content_default a{
    color: {$colors['text']};
}
.sc_googlemap_content_default a.trx_addons_contacts_phone {
    color: {$colors['text_hover']};
}
.sc_googlemap_content_default a:hover {
    color: {$colors['text_dark']} !important;
}
.sc_services_hover .sc_services_item_icon,
.sc_services_hover .sc_services_item_title a:hover,
.sc_services_hover .sc_services_item_subtitle a:hover {
    color: {$colors['text_hover']};
}
.scheme_dark .sc_item_title {
    color: {$colors['bg_color']};
}
.scheme_dark .sc_item_subtitle {
    color: {$colors['text_dark']};
}
.sc_testimonials_item_content:before {
    color: {$colors['text_hover']};
}
.sc_testimonials_item_author_title {
    color: {$colors['text']};
}
.scheme_dark .sc_testimonials_item_author_title {
    color: {$colors['bg_color']};
}
.sc_services_default .sc_services_item:hover .sc_services_item_icon,
.sc_services_default .sc_services_item_icon {
    color: {$colors['text_dark']};
    background-color: {$colors['text_hover']};
}
.sc_services_default .sc_services_item:hover .sc_services_item_icon:hover,
.sc_services_default .sc_services_item_icon:hover {
    color: {$colors['bg_color']};
    background-color: {$colors['text_link']};
}

.sc_services_default.scheme_dark .sc_services_item_icon:hover{
	background-color: {$colors['text_dark']}!important;
}
.sc_services_default .sc_services_item {
    color: {$colors['text']};
    background-color: {$colors['bg_color_0']};
}
.sc_services_default .sc_services_item_title,
.sc_services_default .sc_services_item_title a {
    color: {$colors['text']};
}
.sc_services_default .sc_services_item_title a:hover {
    color: {$colors['text_dark']};
}
.sc_services_default .sc_services_item {
    color: {$colors['text']};
    background-color: {$colors['bg_color_0']};
}
.sc_services_numbered .sc_services_item {
    color: {$colors['text']};
    background-color: {$colors['bg_color']};
}
.sc_services_numbered .sc_services_item_number {
    color: {$colors['text_hover']};
}
.sc_services_default .sc_services_item_number {
    color: {$colors['text_dark']};
    background-color: {$colors['text_hover']};
}
.sc_services_default .with_number .sc_services_item_title a {
    color: {$colors['text']};
}
.sc_services_default .with_number .sc_services_item_title a:hover {
    color: {$colors['text_link']};
}
.trx_addons_promo_small,
.trx_addons_promo_large,
.trx_addons_shop_promo_small,
.trx_addons_shop_promo_large {
    color: {$colors['bg_color']};
}
.trx_addons_promo_italic,
.trx_addons_shop_promo_italic {
    color: {$colors['text_hover']};
}
.trx_addons_events_bold {
    color: {$colors['text_dark']};
}
.trx_addons_events_text,
.trx_addons_events_italic {
    color: {$colors['text_hover']};
}
.trx_addons_popup .trx_addons_tabs_content {
	background-color: {$colors['alter_bg_color']};
}
.sc_events_full .event-date .sc_events_date {
    color: {$colors['text_hover']};
}
.sc_events_full .sc_events_item {
   background-color: {$colors['bg_color']};
}
.sc_blogger .sc_blogger_item .post_meta_item.post_date a {
    color: {$colors['text_hover']};
}
.sc_blogger .sc_blogger_item .post_meta_item.post_date a:hover {
    color: {$colors['text_dark']};
}
.sc_blogger_plain .sc_blogger_item + .sc_blogger_item {
   border-top-color: {$colors['bd_color']};
}
.sc_blogger_item_title a:hover {
    color: {$colors['text_link']};
}
.sc_blogger_item {
   background-color: {$colors['bg_color']};
}
.sc_services_default .with_image .sc_services_item_info {
   background-color: {$colors['bg_color']};
}
.vc_row-has-fill .sc_team_default .sc_team_item_subtitle,
.vc_row-has-fill .sc_team_short .sc_team_item_subtitle,
.vc_row-has-fill .sc_team_featured .sc_team_item_subtitle {
    color: {$colors['bg_color']};
}
.sc_testimonials_simple .sc_testimonials_item_author .sc_testimonials_item_author_title {
    color: {$colors['text']};
}
.trx_addons_timeline_title {
    color: {$colors['text_dark']};
}
.trx_addons_timeline_year {
    color: {$colors['bg_color']};
}
.scheme_dark.sc_form {
    background-color: {$colors['bg_color_0']};
}
.scheme_dark.sc_form input[type="text"], .scheme_dark.sc_form textarea,
.scheme_dark .wpcf7-form .itm input, .scheme_dark .wpcf7-form textarea{
    background-color: {$colors['bg_color_02']};
    border-color: {$colors['bg_color_0']};
    color: {$colors['bg_color']};
}
.scheme_dark.sc_form input[type="text"]:active,
.scheme_dark.sc_form input[type="text"]:focus,
.scheme_dark.sc_form textarea:active,
.scheme_dark.sc_form textarea:focus,
.scheme_dark .wpcf7-form .itm input:active, 
.scheme_dark .wpcf7-form .itm input:focus, 
.scheme_dark .wpcf7-form textarea:active,
.scheme_dark .wpcf7-form textarea:focus {
    border-color: {$colors['bg_color']};
}
.scheme_dark.sc_form input[type="text"]::-webkit-input-placeholder { /* Chrome/Opera/Safari */
    color: {$colors['bg_color']};
}
.scheme_dark.sc_form input[type="text"]::-moz-placeholder { /* Firefox 19+ */
    color: {$colors['bg_color']};
}
.scheme_dark.sc_form input[type="text"]:-ms-input-placeholder { /* IE 10+ */
    color: {$colors['bg_color']};
}
.scheme_dark.sc_form input[type="text"]:-moz-placeholder { /* Firefox 18- */
    color: {$colors['bg_color']};
}
.scheme_dark.sc_form textarea::-webkit-input-placeholder { /* Chrome/Opera/Safari */
    color: {$colors['bg_color']};
}
.scheme_dark.sc_form textarea::-moz-placeholder { /* Firefox 19+ */
  color: {$colors['bg_color']};
}
.scheme_dark.sc_form textarea:-ms-input-placeholder { /* IE 10+ */
  color: {$colors['bg_color']};
}
.scheme_dark.sc_form textarea:-moz-placeholder { /* Firefox 18- */
  color: {$colors['bg_color']};
}
.scheme_dark .sc_form_field.sc_form_field_checkbox label {
	color: {$colors['bg_color']};
}
.scheme_dark.sc_form button {
  color: {$colors['text_dark']};    
}
.scheme_dark.sc_form button:hover {
  color: {$colors['bg_color']}!important;    
}
.sc_form .sc_form_field_checkbox label a{
	color: {$colors['text_dark']};  
}
.sc_form .sc_form_field_checkbox label a:hover{
	color: {$colors['text_hover']};  
}
.sc_team .sc_team_item_thumb .sc_team_item_socials .social_item .social_icon {
  color: {$colors['text_dark']}; 
  background-color: {$colors['text_hover']}; 
}
.sc_team .sc_team_item_thumb .sc_team_item_socials .social_item:hover .social_icon {
  color: {$colors['bg_color']}; 
  background-color: {$colors['text_link']}; 
}
.widget_instagram_images_item:after,
#sb_instagram .sbi_photo_wrap a:after {
  color: {$colors['text_dark']}; 
  background-color: {$colors['text_hover']}; 
}
.widget_instagram_images_item:after:hover,
#sb_instagram .sbi_photo_wrap a:after:hover {
  color: {$colors['bg_color']}; 
  background-color: {$colors['text_link']}; 
}
.trx_addons_scroll_to_top, 
.trx_addons_cv .trx_addons_scroll_to_top {
  color: {$colors['text_dark']}; 
}
.custom .tp-bullet {
  border-color: {$colors['bg_color']}; 
  background-color: {$colors['bg_color']}; 
}
.custom .tp-bullet.selected {
  border-color: {$colors['text_hover']}; 
  background-color: {$colors['bg_color_0']}; 
}
.sc_layouts_row_type_narrow .sc_layouts_item_icon,
.scheme_self.sc_layouts_row_type_narrow .sc_layouts_item_icon {
    color: {$colors['text_hover']}; 
}
.sc_layouts_row_type_narrow .sc_layouts_item_details_line1,
.sc_layouts_row_type_narrow .sc_layouts_item_details_line2,
.scheme_self.sc_layouts_row_type_narrow .sc_layouts_item_details_line1,
.scheme_self.sc_layouts_row_type_narrow .sc_layouts_item_details_line2 {
    color: {$colors['text']}; 
}
.scheme_dark.sc_testimonials_green .sc_testimonials_item_content {
    color: {$colors['text_dark']}; 
}
.sc_testimonials_green .slider_container .slider_pagination_wrap .swiper-pagination-bullet,
.sc_testimonials_green .slider_outer .slider_pagination_wrap .swiper-pagination-bullet,
.sc_testimonials_green .swiper-pagination-custom .swiper-pagination-button {
    border-color: {$colors['text_hover']}; 
    background-color: {$colors['text_hover']}; 
}
.header_position_over .sc_layouts_row_type_compact .sc_layouts_item a:not(.sc_button):not(.button),
.header_position_over .scheme_self.sc_layouts_row_type_compact .sc_layouts_item a:not(.sc_button):not(.button) {
    color: {$colors['bg_color']}; 
}
.header_position_over .sc_layouts_menu_nav>li.current-menu-item>a,
.header_position_over .sc_layouts_menu_nav>li.current-menu-parent>a,
.header_position_over .sc_layouts_menu_nav>li.current-menu-ancestor>a {
    color: {$colors['text_hover']} !important;
}
.header_position_over .sc_layouts_menu_nav>li>a:hover,
.header_position_over .sc_layouts_menu_nav>li.sfHover>a {
    color: {$colors['text_hover']} !important;
}
.header_position_over .sc_layouts_row_fixed_on {
    background-color: {$colors['text_link']} !important; 
}
.sc_layouts_menu_nav .menu-collapse>a:hover:after {
    background-color: {$colors['text_hover']};
}
.sc_services.sc_services_blinked .sc_services_item_number {
    color: {$colors['bg_color']} ;
}
.sc_services_default .without_content.with_icon .sc_services_item_title a {
    color: {$colors['text_dark']} ;
}
.sc_services_default .without_content.with_icon .sc_services_item_title a:hover {
    color: {$colors['text_hover']} ;
}
.sc_services_default .with_image .sc_services_item_title a {
    color: {$colors['text_dark']} ;
}
.sc_services_default .with_image .sc_services_item_title a:hover {
    color: {$colors['text_hover']} ;
}
a.breadcrumbs_item:hover,
.breadcrumbs_item.current {
    color: {$colors['text_hover']}!important; 
}
[class*="sc_input_hover_"].sc_input_hover_iconed input[type="password"],
[class*="sc_input_hover_"].sc_input_hover_iconed input[type="text"] {
    border-color: {$colors['text_link']}!important; 
}
.wpcf7-form .sc_button_hover_style_inverse .wpcf7-submit.sc_button_hover_slide_left {		
	background: linear-gradient(to right,	{$colors['inverse_dark']} 50%, {$colors['text_hover']} 50%) no-repeat scroll right bottom / 210% 100% {$colors['text_hover']} !important; 
	color: {$colors['text_dark']}; 
}
.wpcf7-form .sc_button_hover_style_inverse .wpcf7-submit.sc_button_hover_slide_left:hover {		
  color: {$colors['bg_color']} !important;    
}
.scheme_dark .wpcf7-form .sc_button_hover_style_inverse .wpcf7-submit.sc_button_hover_slide_left {
	color: {$colors['text_dark']}; 
}
.scheme_dark .wpcf7-form .sc_button_hover_style_inverse .wpcf7-submit.sc_button_hover_slide_left:hover {		
  color: {$colors['bg_color']} !important;    
}	

CSS;
		}

		return $css;
	}
}
?>