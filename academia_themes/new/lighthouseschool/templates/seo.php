<?php
/**
 * The template to display the Structured Data Snippets
 *
 * @package WordPress
 * @subpackage LIGHTHOUSESCHOOL
 * @since LIGHTHOUSESCHOOL 1.0.30
 */

// Structured data snippets
if (lighthouseschool_is_on(lighthouseschool_get_theme_option('seo_snippets'))) {
	?><div class="structured_data_snippets">
		<meta itemprop="headline" content="<?php the_title_attribute(); ?>">
		<meta itemprop="datePublished" content="<?php echo esc_attr(get_the_date('Y-m-d')); ?>">
		<meta itemprop="dateModified" content="<?php echo esc_attr(get_the_modified_date('Y-m-d')); ?>">
		<div itemscope itemprop="publisher" itemtype="//schema.org/Organization">
			<meta itemprop="name" content="<?php echo esc_attr(get_bloginfo( 'name' )); ?>">
			<meta itemprop="telephone" content="">
			<meta itemprop="address" content="">
			<?php
			$lighthouseschool_logo_image = lighthouseschool_get_retina_multiplier() > 1 
								? lighthouseschool_get_theme_option( 'logo_retina' )
								: lighthouseschool_get_theme_option( 'logo' );
			if (!empty($lighthouseschool_logo_image)) {
				?><meta itemprop="logo" itemtype="//schema.org/logo" content="<?php echo esc_url($lighthouseschool_logo_image); ?>"><?php
			}
			?>
		</div>
	</div>
	<?php
}
?>