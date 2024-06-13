<?php
/**
 * The template to display the page title and breadcrumbs
 *
 * @package WordPress
 * @subpackage LIGHTHOUSESCHOOL
 * @since LIGHTHOUSESCHOOL 1.0
 */

// Page (category, tag, archive, author) title

if ( lighthouseschool_need_page_title() ) {
	lighthouseschool_sc_layouts_showed('title', true);
	?>
	<div class="top_panel_title sc_layouts_row sc_layouts_row_type_normal scheme_dark">
		<div class="content_wrap">
			<div class="sc_layouts_column sc_layouts_column_align_center">
				<div class="sc_layouts_item">
					<div class="sc_layouts_title sc_align_center">
						<?php

						// Blog/Post title
						?><div class="sc_layouts_title_title"><?php
							$lighthouseschool_blog_title = lighthouseschool_get_blog_title();
							$lighthouseschool_blog_title_text = $lighthouseschool_blog_title_class = $lighthouseschool_blog_title_link = $lighthouseschool_blog_title_link_text = '';
							if (is_array($lighthouseschool_blog_title)) {
								$lighthouseschool_blog_title_text = $lighthouseschool_blog_title['text'];
								$lighthouseschool_blog_title_class = !empty($lighthouseschool_blog_title['class']) ? ' '.$lighthouseschool_blog_title['class'] : '';
								$lighthouseschool_blog_title_link = !empty($lighthouseschool_blog_title['link']) ? $lighthouseschool_blog_title['link'] : '';
								$lighthouseschool_blog_title_link_text = !empty($lighthouseschool_blog_title['link_text']) ? $lighthouseschool_blog_title['link_text'] : '';
							} else
								$lighthouseschool_blog_title_text = $lighthouseschool_blog_title;
							?>
							<h1 itemprop="headline" class="sc_layouts_title_caption<?php echo esc_attr($lighthouseschool_blog_title_class); ?>"><?php
								$lighthouseschool_top_icon = lighthouseschool_get_category_icon();
								if (!empty($lighthouseschool_top_icon)) {
									$lighthouseschool_attr = lighthouseschool_getimagesize($lighthouseschool_top_icon);
									?><img src="<?php echo esc_url($lighthouseschool_top_icon); ?>"  <?php if (!empty($lighthouseschool_attr[3])) lighthouseschool_show_layout($lighthouseschool_attr[3]);?>><?php
								}
								echo wp_kses($lighthouseschool_blog_title_text, 'lighthouseschool_kses_content');
							?></h1>
							<?php
							if (!empty($lighthouseschool_blog_title_link) && !empty($lighthouseschool_blog_title_link_text)) {
								?><a href="<?php echo esc_url($lighthouseschool_blog_title_link); ?>" class="theme_button theme_button_small sc_layouts_title_link"><?php echo esc_html($lighthouseschool_blog_title_link_text); ?></a><?php
							}
							
							// Category/Tag description
							if ( is_category() || is_tag() || is_tax() ) 
								the_archive_description( '<div class="sc_layouts_title_description">', '</div>' );
		
						?></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
}
?>