<?php
/**
 * The template to display the main menu
 *
 * @package WordPress
 * @subpackage LIGHTHOUSESCHOOL
 * @since LIGHTHOUSESCHOOL 1.0
 */
?>
<div class="top_panel_navi sc_layouts_row sc_layouts_row_type_compact sc_layouts_row_fixed
			scheme_<?php echo esc_attr(lighthouseschool_is_inherit(lighthouseschool_get_theme_option('menu_scheme')) 
												? (lighthouseschool_is_inherit(lighthouseschool_get_theme_option('header_scheme')) 
													? lighthouseschool_get_theme_option('color_scheme') 
													: lighthouseschool_get_theme_option('header_scheme')) 
												: lighthouseschool_get_theme_option('menu_scheme')); ?>">
		<div class="columns_wrap">
			<div class="sc_layouts_column sc_layouts_column_align_left sc_layouts_column_icons_position_left column-1_4">
				<?php
				// Logo
				?><div class="sc_layouts_item"><?php
					get_template_part( 'templates/header-logo' );
				?></div>
			</div><?php
			
			// Attention! Don't place any spaces between columns!
			?><div class="sc_layouts_column sc_layouts_column_align_center sc_layouts_column_icons_position_left column-1_2">
				<div class="sc_layouts_item">
					<?php
					// Main menu
					$lighthouseschool_menu_main = lighthouseschool_get_nav_menu(array(
						'location' => 'menu_main', 
						'class' => 'sc_layouts_menu sc_layouts_menu_default sc_layouts_hide_on_mobile'
						)
					);
					if (empty($lighthouseschool_menu_main)) {
						$lighthouseschool_menu_main = lighthouseschool_get_nav_menu(array(
							'class' => 'sc_layouts_menu sc_layouts_menu_default sc_layouts_hide_on_mobile'
							)
						);
					}
					lighthouseschool_show_layout($lighthouseschool_menu_main);
					// Mobile menu button
					?>
					<div class="sc_layouts_iconed_text sc_layouts_menu_mobile_button">
						<a class="sc_layouts_item_link sc_layouts_iconed_text_link" href="#">
							<span class="sc_layouts_item_icon sc_layouts_iconed_text_icon trx_addons_icon-menu"></span>
						</a>
					</div>
				</div><?php


				?>
			</div><?php

            // Attention! Don't place any spaces between columns!
            ?><div class="sc_layouts_column sc_layouts_column_align_right sc_layouts_column_icons_position_left column-1_4">
                <?php
                if (lighthouseschool_exists_trx_addons()) {
                    ?><div class="sc_layouts_item"><?php
                    // Display search field
                    do_action('lighthouseschool_action_search', 'fullscreen', 'header_search', false);
                    ?></div><?php
                }
                ?>
            </div>
		</div><!-- /.sc_layouts_row -->
</div><!-- /.top_panel_navi -->