<?php
/**
 * The style "chess" of the Services item
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.6.13
 */

$args = get_query_var('trx_addons_args_sc_services');

if (empty($args['id'])) $args['id'] = 'sc_services_'.str_replace('.', '', mt_rand());

$link = get_permalink();

if (!empty($args['slider'])) {
	?><div class="slider-slide swiper-slide"><?php
} else if ((int)$args['columns'] > 1) {
	?><div class="<?php echo esc_attr(trx_addons_get_column_class(1, $args['columns'])); ?> "><?php
}
?>
<div class="sc_services_item<?php
	echo !isset($args['hide_excerpt']) || (int) $args['hide_excerpt']==0 ? ' with_content' : ' without_content';
?>"><?php
	trx_addons_get_template_part('templates/tpl.featured.php',
									'trx_addons_args_featured',
									apply_filters('trx_addons_filter_args_featured', array(
												'class' => 'sc_services_item_header',
												'show_no_image' => true,
												'thumb_bg' => true,
												'thumb_size' => apply_filters('trx_addons_filter_thumb_size', trx_addons_get_thumb_size('masonry-big'), 'services-chess')
												),
												'services-chess'
												)
								);
	?>
	<div class="sc_services_item_content">
		<?php
		$title_tag = 'h6';
		if ((int)$args['columns'] == 1) $title_tag = 'h4';
		?>
		<<?php echo esc_attr($title_tag); ?> class="sc_services_item_title"><a href="<?php echo esc_url($link); ?>"><?php the_title(); ?></a></<?php echo esc_attr($title_tag); ?>>
		<?php if (!isset($args['hide_excerpt']) || (int) $args['hide_excerpt']==0) { ?>
			<div class="sc_services_item_text"><?php the_excerpt(); ?></div>
		<?php } ?>
    <div class="sc_services_item_button sc_item_button"><a href="<?php echo esc_url($link); ?>" class="sc_button sc_button_simple"><?php esc_html_e('Read Details', 'lighthouseschool'); ?></a></div>
	</div>
</div>
<?php
if (!empty($args['slider']) || (int)$args['columns'] > 1) {
	?></div><?php
}
?>