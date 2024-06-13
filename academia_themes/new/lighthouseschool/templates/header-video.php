<?php
/**
 * The template to display the background video in the header
 *
 * @package WordPress
 * @subpackage LIGHTHOUSESCHOOL
 * @since LIGHTHOUSESCHOOL 1.0.14
 */
$lighthouseschool_header_video = lighthouseschool_get_header_video();
$lighthouseschool_embed_video = '';
if (!empty($lighthouseschool_header_video) && !lighthouseschool_is_from_uploads($lighthouseschool_header_video)) {
	if (lighthouseschool_is_youtube_url($lighthouseschool_header_video) && preg_match('/[=\/]([^=\/]*)$/', $lighthouseschool_header_video, $matches) && !empty($matches[1])) {
		?><div id="background_video" data-youtube-code="<?php echo esc_attr($matches[1]); ?>"></div><?php
	} else {
		global $wp_embed;
		if (false && is_object($wp_embed)) {
			$lighthouseschool_embed_video = do_shortcode($wp_embed->run_shortcode( '[embed]' . trim($lighthouseschool_header_video) . '[/embed]' ));
			$lighthouseschool_embed_video = lighthouseschool_make_video_autoplay($lighthouseschool_embed_video);
		} else {
			$lighthouseschool_header_video = str_replace('/watch?v=', '/embed/', $lighthouseschool_header_video);
			$lighthouseschool_header_video = lighthouseschool_add_to_url($lighthouseschool_header_video, array(
				'feature' => 'oembed',
				'controls' => 0,
				'autoplay' => 1,
				'showinfo' => 0,
				'modestbranding' => 1,
				'wmode' => 'transparent',
				'enablejsapi' => 1,
				'origin' => home_url(),
				'widgetid' => 1
			));
			$lighthouseschool_embed_video = '<iframe src="' . esc_url($lighthouseschool_header_video) . '" width="1170" height="658" allowfullscreen="0" frameborder="0"></iframe>';
		}
		?><div id="background_video"><?php lighthouseschool_show_layout($lighthouseschool_embed_video); ?></div><?php
	}
}
?>