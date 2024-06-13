<?php
/**
 * The template to display Admin notices
 *
 * @package WordPress
 * @subpackage LIGHTHOUSESCHOOL
 * @since LIGHTHOUSESCHOOL 1.0.1
 */
 
$lighthouseschool_theme_obj = wp_get_theme();
?>
<div class="update-nag" id="lighthouseschool_admin_notice">
	<h3 class="lighthouseschool_notice_title"><?php echo sprintf(esc_html__('Welcome to %s v.%s', 'lighthouseschool'), $lighthouseschool_theme_obj->name.(LIGHTHOUSESCHOOL_THEME_FREE ? ' '.esc_html__('Free', 'lighthouseschool') : ''), $lighthouseschool_theme_obj->version); ?></h3>
	<?php
	if (!lighthouseschool_exists_trx_addons()) {
		?><p><?php echo wp_kses_data(__('<b>Attention!</b> Plugin "ThemeREX Addons is required! Please, install and activate it!', 'lighthouseschool')); ?></p><?php
	}
	?><p>
		<a href="<?php echo esc_url(admin_url().'themes.php?page=lighthouseschool_about'); ?>" class="button button-primary"><i class="dashicons dashicons-nametag"></i> <?php echo sprintf(esc_html__('About %s', 'lighthouseschool'), $lighthouseschool_theme_obj->name); ?></a>
		<?php
		if (lighthouseschool_get_value_gp('page')!='tgmpa-install-plugins') {
			?>
			<a href="<?php echo esc_url(admin_url().'themes.php?page=tgmpa-install-plugins'); ?>" class="button button-primary"><i class="dashicons dashicons-admin-plugins"></i> <?php esc_html_e('Install plugins', 'lighthouseschool'); ?></a>
			<?php
		}
		if (function_exists('lighthouseschool_exists_trx_addons') && lighthouseschool_exists_trx_addons() && class_exists('trx_addons_demo_data_importer')) {
			?>
			<a href="<?php echo esc_url(admin_url().'themes.php?page=trx_importer'); ?>" class="button button-primary"><i class="dashicons dashicons-download"></i> <?php esc_html_e('One Click Demo Data', 'lighthouseschool'); ?></a>
			<?php
		}
		?>
        <a href="<?php echo esc_url(admin_url().'customize.php'); ?>" class="button button-primary"><i class="dashicons dashicons-admin-appearance"></i> <?php esc_html_e('Theme Customizer', 'lighthouseschool'); ?></a>
		<span> <?php esc_html_e('or', 'lighthouseschool'); ?> </span>
        <a href="<?php echo esc_url(admin_url().'themes.php?page=theme_options'); ?>" class="button button-primary"><i class="dashicons dashicons-admin-appearance"></i> <?php esc_html_e('Theme Options', 'lighthouseschool'); ?></a>
        <a href="#" class="button lighthouseschool_hide_notice"><i class="dashicons dashicons-dismiss"></i> <?php esc_html_e('Hide Notice', 'lighthouseschool'); ?></a>
	</p>
</div>