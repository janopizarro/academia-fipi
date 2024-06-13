<?php
// Add plugin-specific colors and fonts to the custom CSS
if (!function_exists('lighthouseschool_get_css')) {
	add_filter('lighthouseschool_filter_get_css', 'lighthouseschool_get_css', 10, 4);
	function lighthouseschool_get_css($css, $colors, $fonts, $scheme='') {
		
		if (isset($css['fonts']) && $fonts) {
			$css['fonts'] .= <<<CSS
					
		.give-form fieldset legend {
			{$fonts['h1_font-family']}
		}	
CSS;

		}

		
		if (isset($css['colors']) && $colors) {
			$css['colors'] .= <<<CSS

		.give-wrap {
			color: {$colors['text']};
		}
		
		.give-wrap .give-card {
			background-color: {$colors['bg_color']};
		}
		
		.give-wrap .give-card:hover .give-card__title{
			color: {$colors['text_link']} !important;
		}

		.ua_edge .give-card .give-card__title{
			color: {$colors['text_dark']} !important;
		}			
		.give-card:hover .give-card__title {
			color: {$colors['text_hover']};
		}		
		.ua_edge .give-card:hover .give-card__title {
			color: {$colors['text_hover']} !important;
		}
	
		.give-goal-progress .goal-text,
		.give-goal-progress .goal-text:hover{
			color: {$colors['text_dark']};
		}
		
		.give-goal-progress .income {
			color: {$colors['text_link']} ;
		}			
		.give-form input[type="radio"] + label:before {
			background-color: {$colors['inverse_link']};
			color: {$colors['text_link']};
			border-color: {$colors['bd_color']} !important;
		}
		form[id*="give-form"] .give-donation-amount #give-amount, 
		form[id*="give-form"] .give-donation-amount #give-amount-text	{
			color: {$colors['input_text']};
			border-color: {$colors['bd_color']};
		}	
		form[id*="give-form"] .give-donation-amount .give-currency-symbol {
			color: {$colors['inverse_text']};
			background-color: {$colors['text_link']};
		}
		
		form[id*="give-form"] #give-final-total-wrap .give-donation-total-label {
			color: {$colors['text_dark']};
			background-color: {$colors['text_link']};
		}	

		form[id*="give-form"] #give-final-total-wrap .give-final-total-amount {
			color: {$colors['text']};
		}		
				
		[id*="give-form"].give-fl-form .give-fl-is-required:before	{
			color: {$colors['text_link']};
		}				
		
		.give-wrap .give-card__text,
		.give-wrap .give-card .give-card__text,
		.give-card:hover .give-wrap .give-card__text {
			color: {$colors['text']};
			border-color: {$colors['bd_color']};
		}		
			
		.give-table strong,
		.give-form strong,
		.give-form-wrap strong{
			color: {$colors['inverse_dark']};
		}		
		
		.give_success {
			border-color: {$colors['text_hover']};
		}		
		.give_success:before {
			background-color: {$colors['text_hover']};
		}
		
		.give_error {
			border-color: {$colors['text_hover']};
		}		
		.give_error:before {
			background-color: {$colors['text_hover']};
		}
		
		.give-card .give-goal-progress .raised {
			color: {$colors['text']};
		}
		
		form[id*="give-form"] .give-donation-amount .give-currency-symbol {
			background-color: {$colors['text_link']};
		}
		
		form[id*="give-form"] .give-donation-amount #give-amount,
		form[id*="give-form"] #give-final-total-wrap .give-final-total-amount{
			background-color: {$colors['input_bg_color']};
		}
		.give-card .give-form-grid-content > p,
		.give-card .form-grid-raised__details > span {
			color: {$colors['text']}!important;
		}


CSS;
		}

		return $css;
	}
}
?>