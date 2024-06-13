<?php
/**
 * The template to display menu in the footer
 *
 * @package WordPress
 * @subpackage LIGHTHOUSESCHOOL
 * @since LIGHTHOUSESCHOOL 1.0.10
 */

// Footer menu
$lighthouseschool_menu_footer = lighthouseschool_get_nav_menu(array(
											'location' => 'menu_footer',
											'class' => 'sc_layouts_menu sc_layouts_menu_default'
											));
if (!empty($lighthouseschool_menu_footer)) {
	?>
	<div class="footer_menu_wrap">
		<div class="footer_menu_inner">
			<?php lighthouseschool_show_layout($lighthouseschool_menu_footer); ?>
		</div>
	</div>
	<?php
}
?>