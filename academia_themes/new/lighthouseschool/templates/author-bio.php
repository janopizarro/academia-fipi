<?php
/**
 * The template to display the Author bio
 *
 * @package WordPress
 * @subpackage LIGHTHOUSESCHOOL
 * @since LIGHTHOUSESCHOOL 1.0
 */
?>

<div class="author_info scheme_default author vcard" itemprop="author" itemscope itemtype="//schema.org/Person">

	<div class="author_avatar" itemprop="image">
		<?php 
		$lighthouseschool_mult = lighthouseschool_get_retina_multiplier();
		echo get_avatar( get_the_author_meta( 'user_email' ), 102*$lighthouseschool_mult );
		?>
	</div><!-- .author_avatar -->

	<div class="author_description">
		<div class="author_title_preview"><?php echo esc_html__('About Author', 'lighthouseschool'); ?></div>
		<h6 class="author_title" itemprop="name"><?php echo wp_kses_data(sprintf(__('%s', 'lighthouseschool'), '<span class="fn">'.get_the_author().'</span>')); ?></h6>

		<div class="author_bio" itemprop="description">
			<?php echo wp_kses(wpautop(get_the_author_meta( 'description' )), 'lighthouseschool_kses_content'); ?>
			<?php do_action('lighthouseschool_action_user_meta'); ?>
		</div><!-- .author_bio -->

	</div><!-- .author_description -->

</div><!-- .author_info -->
