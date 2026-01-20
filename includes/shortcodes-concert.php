<?php
/**
 * Concert calendar shortcode
 */

function rsm_display_concert_calendar() {
    $today = date('Ymd');
    
    $concerts = new WP_Query(array(
        'post_type' => 'concert',
        'posts_per_page' => -1,
        'meta_key' => 'event_date',
        'orderby' => 'meta_value_num',
        'order' => 'ASC',
        'meta_query' => array(
            array(
                'key' => 'event_date',
                'value' => $today,
                'compare' => '>=',
                'type' => 'DATE'
            )
        )
    ));
    
    if (!$concerts->have_posts()) {
        return '<p class="no-concerts">No upcoming concerts at this time. Check back soon!</p>';
    }
    
    // Group concerts by month
    $concerts_by_month = array();
    while ($concerts->have_posts()) {
        $concerts->the_post();
        $event_date = get_field('event_date');
        $month_year = date('F Y', strtotime($event_date));
        
        if (!isset($concerts_by_month[$month_year])) {
            $concerts_by_month[$month_year] = array();
        }
        
        $concerts_by_month[$month_year][] = array(
            'id' => get_the_ID(),
            'title' => get_the_title(),
            'concert_image' => get_field('concert_image'),
            'event_date' => $event_date,
            'venue_name' => get_field('venue_name'),
            'city' => get_field('city'),
            'state' => get_field('state'),
            'ticket_link' => get_field('ticket_link'),
            'show_time' => get_field('show_time')
        );
    }
    wp_reset_postdata();
    
    ob_start();
    ?>
    <div class="concert-calendar-wrapper">
        <?php foreach ($concerts_by_month as $month => $concerts_list): ?>
            <div class="concert-month-section">
                <h2 class="concert-month-heading"><?php echo esc_html($month); ?></h2>
                <div class="concert-calendar">
                    <?php foreach ($concerts_list as $concert): 
                        $date_formatted = date('F j, Y', strtotime($concert['event_date']));
                        $day_of_week = date('l', strtotime($concert['event_date']));
                    ?>
                    <div class="concert-item">
                        <?php if ($concert['concert_image']): ?>
                            <div class="concert-image">
                                <img src="<?php echo esc_url($concert['concert_image']['url']); ?>" alt="<?php echo esc_attr($concert['title']); ?>">
                            </div>
                        <?php endif; ?>
                        
                        <div class="concert-details">
                            <h3 class="concert-title"><?php echo esc_html($concert['title']); ?></h3>
                            
                            <p class="concert-date">
                                <span class="day"><?php echo $day_of_week; ?></span>, <?php echo $date_formatted; ?><?php if ($concert['show_time']): ?>, <?php echo esc_html($concert['show_time']); ?><?php endif; ?>
                            </p>
                            
                            <p class="venue-name"><?php echo esc_html($concert['venue_name']); ?></p>
                            <p class="venue-location"><?php echo esc_html($concert['city']); ?>, <?php echo esc_html($concert['state']); ?></p>
                            
                            <?php if ($concert['ticket_link']): ?>
                                <a href="<?php echo esc_url($concert['ticket_link']); ?>" class="concert-ticket-btn" target="_blank" rel="noopener">Get Tickets</a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('concert_calendar', 'rsm_display_concert_calendar');