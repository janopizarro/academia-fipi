<?php
/**
 * The style "default" of the Events
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.2
 */
$args = get_query_var('trx_addons_args_sc_events');

if ($args['slider']) {
?><div class="swiper-slide"><?php
    } else if ((int)$args['columns'] > 1) {
    ?><div class="<?php echo esc_attr(trx_addons_get_column_class(1, $args['columns'])); ?>"><?php
        }
        ?><div class="sc_events_item"><?php

            if ( has_post_thumbnail() ) {
                ?><div class="event-featured"><?php
                // Featured image
                lighthouseschool_show_post_featured(array( 'thumb_size' => lighthouseschool_get_thumb_size( 'events' ) ));
                ?></div><?php
            }
            ?><div class="event-content"><?php

            // Event's title
            ?><h6 class="sc_events_title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h6><div class="event-date"><?php

            // Event's date
            $date = tribe_get_start_date(null, true, 'd.m');
            if (empty($date)) $date = get_the_date('d.m');
            ?><span class="sc_events_date"><?php
                echo esc_html($date);
                ?></span><?php

            // Event's time
            $time = tribe_get_start_time(null, 'g:i A');
            $time1 = tribe_get_end_time(null, 'g:i A');
            ?><span class="sc_events_time"><?php
                echo esc_html($time).' - '.esc_html($time1);
            ?></span></div><?php
            ?></div></div><?php

        if ($args['slider'] || (int)$args['columns'] > 1) {
        ?></div><?php
}

?>